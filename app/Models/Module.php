<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'description', 'tenant_id', 'created_by'];
    
    const LEARNING_CATEGORIES = [
        'Path',
        'Course',
        'Chapter',
        'Lesson',
        'Test'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function contentTypes()
    {
        return $this->belongsToMany(ContentType::class);
    }

    public function parents()
    {
        return $this->belongsToMany(Module::class, 'module_module', 'child_module_id', 'parent_module_id')
            ->withPivot('sort_order', 'is_assigned_to_module')
            ->withTimestamps();
    }

    public function children()
    {
        return $this->belongsToMany(Module::class, 'module_module', 'parent_module_id', 'child_module_id')
            ->withPivot('sort_order', 'is_assigned_to_module')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_assigned_to_user', 'is_mandatory', 'valid_from', 'to_be_done_until', 'filter_id')
            ->withTimestamps();
    }


    public function scopePath($query)
    {
        return $query->where('category', 'Path');
    }
    
    public function scopeCourse($query)
    {
        return $query->where('category', 'Course');
    }
    
    public function scopeChapter($query)
    {
        return $query->where('category', 'Chapter');
    }
    
    public function scopeLesson($query)
    {
        return $query->where('category', 'Lesson');
    }
    
    public function scopeTest($query)
    {
        return $query->where('category', 'Test');
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
