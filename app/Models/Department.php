<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(User::class);
    }
}
