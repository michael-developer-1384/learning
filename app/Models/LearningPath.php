<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class LearningPath extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'valid_from', 'valid_until', 'is_active', 'is_mandatory', 'tenant_id', 'created_by_user'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by_user = auth()->id();
            }
        });

        static::addGlobalScope('tenant_id', function (Builder $builder) {
            if (!Request::is('nova-api*')) {
                $tenantId = 1; // Beispielwert
                $builder->where('tenant_id', $tenantId);
            }
        });
    }
}
