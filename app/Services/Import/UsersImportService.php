<?php

namespace App\Services\Import;

use App\Models\FileImport;
use App\Models\FileImportUser;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class UsersImportService implements ToModel, WithHeadingRow
{
    protected $fileImportId;
    protected $tempUsers = [];

    public function __construct($fileImportId)
    {
        $this->fileImportId = $fileImportId;
    }

    public function handle(FileImport $fileImport)
    {
        \Log::info('handle called');

        Excel::import($this, storage_path('app/' . $fileImport->filename));
        FileImportUser::insert($this->tempUsers);

        $this->checkForEntriesWithoutUserId($fileImport->id);
        $this->checkForDuplicateUserIds($fileImport->id);
        $this->checkForInvalidIds($fileImport->id);
        $this->checkForInvalidCompanyNames($fileImport->id);
        $this->checkForInvalidRoleNames($fileImport->id);
        $this->checkForMissingEntries($fileImport->id);
        $this->checkForMissingMandatoryFields($fileImport->id);
        $this->checkForUpdatedFields($fileImport->id);

        $fileImport->update(['status' => 'processed']);
    }

    public function model(array $row)
    {
        \Log::info('Model method called with row:', $row);

        $company = Company::where('name', $row['company_name'])->first();
        $roleNames = preg_split('/\s*,\s*/', $row['role_names']);
        $roles = Role::whereIn('name', $roleNames)->get();
        $dateOfBirth = Carbon::createFromFormat('d.m.Y', $row['date_of_birth'])->format('Y-m-d');

        $this->tempUsers[] = [
            'file_import_id' => $this->fileImportId,
            'name' => $row['name'],
            'email' => $row['email'],
            'date_of_birth' => $dateOfBirth,
            'phone' => $row['phone'],
            'address' => $row['address'],
            'company_name' => $row['company_name'],
            'role_names' => $row['role_names'],
            'company_id' => $company->id ?? null,
            'role_ids' => $roles->pluck('id')->implode(','),
            'user_id' => $row['id'],
            'test_result' => null,
            'user_action' => 'pending',
        ];
    }

    private function checkForEntriesWithoutUserId($importId)
    {
        $importedUsers = FileImportUser::where('file_import_id', $importId)->get();
        $newUsersWithoutId = collect($importedUsers)->filter(function ($importedUser) {
            return is_null($importedUser['user_id']);
        })->each(function ($user) {
            $user->update(['test_result' => 'new', 'test_result_description' => 'No ID']);
        });
    }

    private function checkForDuplicateUserIds($importId)
    {
        $groupedUsers = FileImportUser::where('file_import_id', $importId)->get()->groupBy('user_id');
        $groupedUsers->each(function ($usersGroup) {
            if ($usersGroup->count() > 1) {
                $usersGroup->slice(1)->each(function ($duplicateUser) {
                    $duplicateUser->update(['test_result' => 'invalid', 'test_result_description' => 'Duplicate ID']);
                });
            }
        });
    }

    private function checkForInvalidIds($importId)
    {
        $importedUsers = FileImportUser::where('file_import_id', $importId)->get();
        $newUsersWithInvalidId = collect($importedUsers)->filter(function ($importedUser) {
            return !is_null($importedUser['user_id']) && is_null(User::find($importedUser['user_id']));
        })->each(function ($user) {
            $user->update(['test_result' => 'invalid', 'test_result_description' => 'Invalid ID']);
        });
    }

    private function checkForInvalidRoleNames($importId)
    {
        $importedUsers = FileImportUser::where('file_import_id', $importId)->get();
        $existingRoleNames = Role::pluck('name')->all();

        foreach ($importedUsers as $user) {
            $roleNames = explode(',', $user->role_names);
            $invalidRoleNames = [];

            foreach ($roleNames as $roleName) {
                $roleName = trim($roleName); // Entfernen von Leerzeichen
                if (!in_array($roleName, $existingRoleNames)) {
                    $invalidRoleNames[] = $roleName;
                }
            }

            if (!empty($invalidRoleNames)) {
                $invalidNamesString = implode(', ', $invalidRoleNames);
                $user->update([
                    'test_result' => 'invalid',
                    'test_result_description' => "Invalid Role Names: $invalidNamesString"
                ]);
            }
        }
    }


    private function checkForInvalidCompanyNames($importId)
    {
        $importedCompanyNames = FileImportUser::where('file_import_id', $importId)->pluck('company_name')->unique()->filter()->all();
        $existingCompanyNames = Company::pluck('name')->all();
        $invalidCompanyNames = array_diff($importedCompanyNames, $existingCompanyNames);
        
        foreach ($invalidCompanyNames as $invalidCompanyName) {
            FileImportUser::where('file_import_id', $importId)
                        ->where('company_name', $invalidCompanyName)
                        ->update([
                            'test_result' => 'invalid',
                            'test_result_description' => "Invalid Company Name"
                        ]);
        }
    }


    private function checkForMissingEntries($importId)
    {
        $importedUsers = FileImportUser::where('file_import_id', $importId)->get();
        $importedUserIds = $importedUsers->pluck('user_id')->filter()->all();
        $missingUsersFromDb = User::whereNotIn('id', $importedUserIds)->get();
        $missingUsersFromDb->each(function ($user) use ($importId) {
            FileImportUser::create([
                'file_import_id' => $importId,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'date_of_birth' => $user->date_of_birth,
                'phone' => $user->phone,
                'address' => $user->address,
                'company_name' => $user->company ? $user->company->name : null,
                'role_names' => $user->role ? $user->role->name : null,
                'role_id' => $user->role_id,
                'company_id' => $user->company_id,
                'test_result' => 'missing',
                'test_result_description' => 'ID Missing',
                'user_action' => 'pending'
            ]);
        });
    }

    private function checkForMissingMandatoryFields($importId)
    {
        $importedUsers = FileImportUser::where('file_import_id', $importId)->get();
        $usersWithMissingFields = collect($importedUsers)->map(function ($importedUser) {
            $missingFields = [];
            if (empty($importedUser['name'])) $missingFields[] = 'name';
            if (empty($importedUser['email'])) $missingFields[] = 'email';
            if (empty($importedUser['company_name'])) $missingFields[] = 'company_name';
            if (empty($importedUser['role_names'])) $missingFields[] = 'role_names';
            return ['user' => $importedUser, 'missingFields' => $missingFields];
        })->filter(function ($item) {
            return !empty($item['missingFields']);
        })->each(function ($item) {
            $description = 'Missing mandatory fields: ' . implode(', ', $item['missingFields']);
            $item['user']->update(['test_result' => 'invalid', 'test_result_description' => ucwords($description)]);
        });
    }

    private function checkForUpdatedFields($importId)
    {
        $importedUsers = FileImportUser::where('file_import_id', $importId)->get();
        $usersWithUpdatedFields = collect($importedUsers)->map(function ($importedUser) {
            $existingUser = User::find($importedUser['user_id']);
            $updatedFields = [];
            if ($existingUser) {
                if ($importedUser['name'] !== $existingUser->name) $updatedFields[] = 'name';
                if ($importedUser['email'] !== $existingUser->email) $updatedFields[] = 'email';
                if (Carbon::parse($importedUser['date_of_birth'])->toDateString() !== $existingUser->date_of_birth->toDateString()) $updatedFields[] = 'date_of_birth';
                if ($importedUser['phone'] !== $existingUser->phone) $updatedFields[] = 'phone';
                if ($importedUser['address'] !== $existingUser->address) $updatedFields[] = 'address';
                if ($importedUser['company_name'] && $existingUser->company && $importedUser['company_name'] !== $existingUser->company->name) $updatedFields[] = 'company_name';
                if ($importedUser['role_names'] && $existingUser->role && $importedUser['role_names'] !== $existingUser->role->name) $updatedFields[] = 'role_names';
            }
            return ['user' => $importedUser, 'updatedFields' => $updatedFields];
        })->filter(function ($item) {
            return !empty($item['updatedFields']);
        })->each(function ($item) {
            $description = 'Updated fields: ' . implode(', ', $item['updatedFields']);
            $item['user']->update(['test_result' => 'updated', 'test_result_description' => ucwords($description)]);
        });
    }
}
