<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileImport extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'original_filename', 'tenant_id', 'content', 'type', 'created_by'];

    const IMPORT_CONTENT = [
        'Users' => 'Description.',
        'Companies' => 'Description.'
    ];

    const IMPORT_TYPES = [
        'Full Import' => 'Complete data import, replacing existing records.',
        'New Entries' => 'Only new records that do not exist in the current dataset.',
        'Updates Only' => 'Only updates to existing records.',
        'New and Updates' => 'Both new records and updates to existing ones.',
        'Deletion Entries' => 'Records indicating data that should be deleted.'
    ];
    

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function fileImportUsers()
    {
        return $this->hasMany(FileImportUser::class);
    }
}
