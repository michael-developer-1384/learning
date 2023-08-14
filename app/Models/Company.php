<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class Company extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'email',
        'phone',
        'address',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user');
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
