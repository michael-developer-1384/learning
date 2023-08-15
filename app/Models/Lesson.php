<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'valid_from', 'valid_until', 'is_active', 'is_mandatory', 'order', 'tenant_id', 'previous_lesson_id', 'must_complete_previous', 'chapter_id', 'created_by_user'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function course()
    {
        return $this->belongsToThrough(Course::class, Chapter::class);
    }

    public function tenant()
    {
        return $this->belongsToThrough(Tenant::class, Chapter::class, Course::class);
    }
    
    public function previousLesson()
    {
        return $this->belongsTo(Lesson::class, 'previous_lesson_id');
    }

    public function subsequentLessons()
    {
        return $this->hasMany(Lesson::class, 'previous_lesson_id');
    }
    
    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
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
            // Überprüfen, ob die aktuelle Anfrage von Nova kommt
            if (!Request::is('nova-api*')) {
                // Hier setzen Sie die tenant_id, die Sie filtern möchten.
                $tenantId = 1; // Beispielwert
                $builder->where('tenant_id', $tenantId);
            }
        });
    }
}
