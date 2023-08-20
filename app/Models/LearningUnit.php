<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class LearningUnit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'valid_from', 'valid_until', 'is_active', 'is_mandatory', 'tenant_id', 'created_by'];


    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function previousLearningUnits()
    {
        return $this->belongsTo(LearningUnit::class, 'previous_lesson_id');
    }

    public function subsequentLearningUnits()
    {
        return $this->hasMany(LearningUnit::class, 'previous_lesson_id');
    }
    
    public function contentTypes()
    {
        return $this->belongsToMany(ContentType::class);
    }

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
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
                $tenantId = 1; // Beispielwert
                $builder->where('tenant_id', $tenantId);
            }
        });
    }
}
