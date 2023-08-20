<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'valid_from', 'valid_until', 'is_active', 'is_mandatory', 'tenant_id', 'created_by'];


    public function pathUnits()
    {
        return $this->morphMany(PathUnit::class, 'unit');
    }

    /* 
    public function paths()
{
    return $this->morphToMany(Path::class, 'childable', 'path_children')
                ->withPivot('order')
                ->withTimestamps();
}

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class, 'childable', 'chapter_children')
                    ->withPivot('order')
                    ->withTimestamps();
    } */
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function previousChapter()
    {
        return $this->belongsTo(Chapter::class, 'previous_chapter_id');
    }

    public function subsequentChapters()
    {
        return $this->hasMany(Chapter::class, 'previous_chapter_id');
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
