<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathUnit extends Model
{
    use HasFactory;

    protected $fillable = ['path_id', 'unit_id', 'unit_type'];

    public function path()
    {
        return $this->belongsTo(Path::class);
    }

    public function unit()
    {
        return $this->morphTo();
    }
}
