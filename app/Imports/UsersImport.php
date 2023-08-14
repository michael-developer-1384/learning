<?php
namespace App\Imports;

use App\Models\ImportedUser;
use App\Models\Company;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Str;


class UsersImport implements ToModel, WithHeadingRow
{
    protected $importId;

    public function __construct()
    {
        // Erzeugen Sie eine eindeutige ID für diesen Importvorgang
        $this->importId = (string) Str::uuid();
    }

    public function model(array $row)
    {
        // Find the company and role by their names
        $company = Company::where('name', $row['company_name'])->first();
        $role = Role::where('name', $row['role_name'])->first();

        // Convert date_of_birth
        $dateOfBirth = Carbon::createFromFormat('d.m.Y', $row['date_of_birth'])->format('Y-m-d');

        // Store the row data in ImportedUser model
        return new ImportedUser([
            'name' => $row['name'],
            'email' => $row['email'],
            'date_of_birth' => $dateOfBirth,
            'phone' => $row['phone'],
            'address' => $row['address'],
            'company_name' => $row['company_name'],
            'role_name' => $row['role_name'],
            'role_id' => $role->id ?? null,
            'company_id' => $company->id ?? null,
            'user_id' => $row['id'],
            'import_id' => $this->importId,
            'test_result' => null, // Default value
            'user_action' => 'pending', // Default value
        ]);
    }

    /**
     * Get the import ID.
     *
     * @return string
     */
    public function getImportId(): string
    {
        return $this->importId;
    }
}
