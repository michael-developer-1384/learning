<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

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
    
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by_user = auth()->id();
            }
        });

        static::addGlobalScope('tenant_id', function (Builder $builder) {
            // Überprüfen, ob die aktuelle Anfrage von Nova kommt
            if (!Request::is('nova-api*')) {
                // Hier setzen Sie die tenant_id, die Sie filtern möchten.
                $tenantId = 1; // Beispielwert
                $builder->where('tenant_id', $tenantId);
            }
        });
    }
}
