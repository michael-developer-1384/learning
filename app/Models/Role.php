<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{ 
    use HasFactory;

    protected $fillable = [
        'name', // Beispiel für ein Attribut
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
