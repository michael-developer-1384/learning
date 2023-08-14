<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\ImportedUser;



class EventController extends Controller
{
    public function index()
    {
        return view('events.index');
    }

    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'users_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $path = $request->file('users_file')->store('temp');
        
        $import = new UsersImport();
        Excel::import($import, $path);
        $importId = $import->getImportId();

        $this->checkForEntriesWithoutUserId($importId);
        $this->checkForDuplicateUserIds($importId);
        $this->checkForInvalidIds($importId);
        $this->checkForInvalidNames($importId, Company::class, 'company_name');
        $this->checkForInvalidNames($importId, Role::class, 'role_name');
        $this->checkForMissingEntries($importId, $import);
        $this->checkForMissingMandatoryFields($importId);
        $this->checkForUpdatedFields($importId);

        return redirect()->route('event.import_results');

    }

    public function showImportResults()
    {
        // Holen Sie den neuesten Import basierend auf dem angemeldeten Benutzer
        $latestImportId = ImportedUser::where('created_by_user', auth()->id())->latest('created_at')->first()->import_id;
        $importedUsers = ImportedUser::where('import_id', $latestImportId)->whereNotNull('test_result')->get();
    
        // Gruppieren Sie die Benutzer nach ihrem test_result
        $groupedUsers = $importedUsers->groupBy('test_result');
    
        return view('events.import-results', ['groupedUsers' => $groupedUsers]);
    }




    // FUNCTIONS

    private function checkForEntriesWithoutUserId($importId)
    {
        // Get the imported users based on the importId
        $importedUsers = ImportedUser::where('import_id', $importId)->whereNull('test_result')->get();

        // Funktion, die Einträge ohne user_id prüft
        $newUsersWithoutId = collect($importedUsers)->filter(function ($importedUser) {
            return is_null($importedUser['user_id']);
        })->each(function ($user) {
            $user->update(['test_result' => 'new', 'test_result_description' => 'No ID']);
        });
    }

    private function checkForDuplicateUserIds($importId)
    {
        // Gruppieren Sie die importierten Benutzer nach user_id und filtern Sie nur diejenigen mit test_result null
        $groupedUsers = ImportedUser::where('import_id', $importId)->whereNull('test_result')->get()->groupBy('user_id');

        // Überprüfen Sie jede Gruppe auf Duplikate
        $groupedUsers->each(function ($usersGroup) {
            // Wenn es mehr als einen Benutzer in der Gruppe gibt, markieren Sie alle außer dem ersten als "invalid"
            if ($usersGroup->count() > 1) {
                $usersGroup->slice(1)->each(function ($duplicateUser) {
                    $duplicateUser->update(['test_result' => 'invalid', 'test_result_description' => 'Duplicate ID']);
                });
            }
        });
    }

    private function checkForInvalidIds($importId)
    {
        // Get the imported users based on the importId
        $importedUsers = ImportedUser::where('import_id', $importId)->whereNull('test_result')->get();

        // Funktion, die Einträge mit einer user_id prüft, für die es keine passende ID in der users-Tabelle gibt
        $newUsersWithInvalidId = collect($importedUsers)->filter(function ($importedUser) {
            return !is_null($importedUser['user_id']) && is_null(User::find($importedUser['user_id']));
        })->each(function ($user) {
            $user->update(['test_result' => 'invalid', 'test_result_description' => 'Invalid ID']);
        });
    }

    private function checkForMissingEntries($importId, $import)
    {
        // Sammeln Sie die user_ids der importierten Benutzer mit test_result gleich null
        $importedUsers = ImportedUser::where('import_id', $importId)->whereNull('test_result')->get();
        $importedUserIds = $importedUsers->pluck('user_id')->filter()->all();

        // Finden Sie Benutzer, die zum aktuellen Mandanten gehören, aber nicht in der importierten Liste sind
        $missingUsersFromDb = User::whereNotIn('id', $importedUserIds)->get();

        // Für jeden fehlenden Benutzer, erstellen Sie einen neuen Eintrag in der ImportedUser-Tabelle
        $missingUsersFromDb->each(function ($user) use ($import) {
            $missingUser = ImportedUser::create([
                'import_id' => $import->getImportId(),
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'date_of_birth' => $user->date_of_birth,
                'phone' => $user->phone,
                'address' => $user->address,
                'company_name' => $user->company ? $user->company->name : null,
                'role_name' => $user->role ? $user->role->name : null,
                'role_id' => $user->role_id,
                'company_id' => $user->company_id,
                'tenant_id' => $user->tenant_id,
                'test_result' => 'missing',
                'test_result_description' => 'ID Missing',
                'user_action' => 'pending'
            ]);
        });
    }

    private function checkForMissingMandatoryFields($importId)
    {
        // Get the imported users based on the importId
        $importedUsers = ImportedUser::where('import_id', $importId)->whereNull('test_result')->get();
    
        // Funktion, die überprüft, ob Pflichtfelder in der Excel-Datei nicht gefüllt sind
        $usersWithMissingFields = collect($importedUsers)->map(function ($importedUser) {
            $missingFields = [];
    
            if (empty($importedUser['name'])) {
                $missingFields[] = 'name';
            }
            if (empty($importedUser['email'])) {
                $missingFields[] = 'email';
            }
            if (empty($importedUser['company_name'])) {
                $missingFields[] = 'company_name';
            }
            if (empty($importedUser['role_name'])) {
                $missingFields[] = 'role_name';
            }
    
            return [
                'user' => $importedUser,
                'missingFields' => $missingFields
            ];
        })->filter(function ($item) {
            return !empty($item['missingFields']);
        })->each(function ($item) {
            $description = 'Missing mandatory fields: ' . implode(', ', $item['missingFields']);
            $item['user']->update(['test_result' => 'invalid', 'test_result_description' => ucwords($description)]);
        });
    }

    private function checkForUpdatedFields($importId)
    {
        // Get the imported users based on the importId
        $importedUsers = ImportedUser::where('import_id', $importId)->whereNull('test_result')->get();
    
        // Funktion, die überprüft, ob es Abweichungen zwischen den importierten Daten und den Daten in der Datenbank gibt
        $usersWithUpdatedFields = collect($importedUsers)->map(function ($importedUser) {
            $existingUser = User::find($importedUser['user_id']);
            $updatedFields = [];
    
            if ($existingUser) {
                
                if ($importedUser['name'] !== $existingUser->name) {
                    $updatedFields[] = 'name';
                }
                if ($importedUser['email'] !== $existingUser->email) {
                    $updatedFields[] = 'email';
                }
                if (Carbon::parse($importedUser['date_of_birth'])->toDateString() !== $existingUser->date_of_birth->toDateString()) {
                    $updatedFields[] = 'date_of_birth';
                } 
                if ($importedUser['phone'] !== $existingUser->phone) {
                    $updatedFields[] = 'phone';
                }
                if ($importedUser['address'] !== $existingUser->address) {
                    $updatedFields[] = 'address';
                }
                if ($importedUser['company_name'] && $existingUser->company && $importedUser['company_name'] !== $existingUser->company->name) {
                    $updatedFields[] = 'company_name';
                }
                if ($importedUser['role_name'] && $existingUser->role && $importedUser['role_name'] !== $existingUser->role->name) {
                    $updatedFields[] = 'role_name';
                }
            }
    
            return [
                'user' => $importedUser,
                'updatedFields' => $updatedFields
            ];
        })->filter(function ($item) {
            return !empty($item['updatedFields']);
        })->each(function ($item) {
            $description = 'Updated fields: ' . implode(', ', $item['updatedFields']);
            $item['user']->update(['test_result' => 'updated', 'test_result_description' => ucwords($description)]);
        });
    }
 
    private function checkForInvalidNames($importId, $model, $columnName)
    {
        // Holen Sie sich alle eindeutigen Namen aus den importierten Benutzern für den gegebenen importId und Spaltennamen
        $importedNames = ImportedUser::where('import_id', $importId)
                                     ->whereNull('test_result')
                                     ->pluck($columnName)
                                     ->unique()
                                     ->filter()
                                     ->all();
    
        // Holen Sie sich alle existierenden Namen aus dem gegebenen Modell
        $existingNames = $model::pluck('name')->all();
    
        // Finden Sie die Namen, die in den importierten Daten existieren, aber nicht im gegebenen Modell
        $invalidNames = array_diff($importedNames, $existingNames);
    
        // Aktualisieren Sie die test_result und test_result_description für jeden Benutzer mit einem ungültigen Namen
        foreach ($invalidNames as $invalidName) {
            ImportedUser::where('import_id', $importId)
                        ->where($columnName, $invalidName)
                        ->update([
                            'test_result' => 'invalid',
                            'test_result_description' => "Invalid ".ucwords($columnName)
                        ]);
        }
    }
    
}
