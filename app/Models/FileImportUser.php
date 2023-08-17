<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileImportUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_import_id',
        'name',
        'email',
        'password',
        'tenant_id',
        'phone',
        'address',
        'date_of_birth',
        'current_team_id',
        'profile_photo_path',
        'tags',
        'start_date',
        'role_names',
        'role_ids',
        'company_name',
        'company_id',
        'test_result',
        'test_result_description',
        'user_action',
    ];

    public function file_import()
    {
        return $this->belongsTo(FileImport::class);
    }
}
