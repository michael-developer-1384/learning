<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_id', 'name', 'email', 'phone', 'address', 'date_of_birth', 
        'company_name', 'role_name', 'role_id', 'company_id', 'tenant_id', 'user_id',
        'test_result', 'test_result_description', 'user_action'
    ];
    

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
