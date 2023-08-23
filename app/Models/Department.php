<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'tenant_id'];

    const DEPARTMENT_NAMES = [
        'Human Resources',
        'Finance & Accounting',
        'Marketing',
        'Sales',
        'Research & Development',
        'Information Technology',
        'Operations',
        'Customer Service',
        'Logistics & Supply Chain',
        'Legal',
        'Public Relations',
        'Procurement',
        'Administration',
        'Production or Manufacturing',
        'Quality Assurance'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('start_date');
    }

    protected $casts = [
        'start_date' => 'date',
    ];
    
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::addGlobalScope('tenant_id', function (Builder $builder) {
            // Überprüfen, ob die aktuelle Anfrage von Nova kommt
            if (!Request::is('nova-api*')) {
                // Hier setzen Sie die tenant_id, die Sie filtern möchten.
                $tenantId = 1; 
                $builder->where('tenant_id', $tenantId);
            }
        });
    }
}
