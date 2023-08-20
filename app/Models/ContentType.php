<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_active'];

    const CONTENT_TYPES = [
        'SCORM' => true,
        'Powerpoint' => true,
        'PDF' => false,
        'Video' => true,
        'Webinar' => true,
        'Training' => true
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class)
                    ->withPivot('is_assigned_to_module');
    }
}
