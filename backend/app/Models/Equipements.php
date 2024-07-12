<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipements extends Model
{
    use HasFactory;
    protected $table = 'equipements';

    protected $fillable = [
        'nom',
        'description',
        'numero_serie',
        'modele',
        'marque',
        'coleur',
        'emplacement_id'
    ];

    // Define relationships (optional)
    public function emplacement()
    {
        return $this->belongsTo(Emplacements::class, "emplacement_id");
    }

    public function ordresDeTravail()
    {
        return $this->hasMany(OrdresDeTravail::class, "equipement_id");
    }

}
