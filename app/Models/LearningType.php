<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_active'];

    const LEARNING_TYPES = [
        'Visual' => true,
        'Auditory' => true,
        'Kinesthetic' => false,
        'Reading/Writing' => true
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
