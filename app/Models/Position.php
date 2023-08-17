<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'tenant_id'];

    const POSITION_NAMES = [
        'Team Leader',
        'Technician',
        'Accountant',
        'Manager',
        'Sales Representative',
        'Software Developer',
        'Human Resources',
        'Marketing Specialist',
        'Customer Service Representative',
        'Administrator'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('start_date');
    }
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    protected $casts = [
        'start_date' => 'date',
    ];

}
