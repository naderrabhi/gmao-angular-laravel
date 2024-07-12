<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emplacements extends Model
{
    use HasFactory;
    protected $table = 'emplacements';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function equipements()
    {
        return $this->hasMany(Equipements::class, "emplacement_id");
    }
}
