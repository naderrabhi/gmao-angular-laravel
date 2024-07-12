<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectationDesOrdres extends Model
{
    use HasFactory;

    protected $table = 'affectation_des_ordres';

    protected $fillable = [
        'ordre_travail_id',
        'technicien_id',
        'date_resolution',
        'date_confirmation',
        'confirmer',
        'reparer',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'confirmer' => 'boolean',
        'reparer' => 'boolean',
    ];

    // Define relationships
    public function ordreTravail()
    {
        return $this->belongsTo(OrdresDeTravail::class, "ordre_travail_id");
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, "technicien_id");
    }

    // protected $fillable = [
    //     'id_technician',
    //     'id_peripheral',
    //     'confirmed',
    //     'fixed',
    //     'confirmed_at',
    //     'fixed_at',
    // ];

    // /**
    //  * The attributes that should be cast to native types.
    //  *
    //  * @var array
    //  */
    // protected $casts = [
    //     'confirmed' => 'boolean',
    //     'fixed' => 'boolean',
    //     'confirmed_at' => 'string',
    //     'fixed_at' => 'string',
    // ];

    // public function technician()
    // {
    //     return $this->belongsTo(User::class, 'id_technician');
    // }

    // public function peripheral()
    // {
    //     return $this->belongsTo(Peripheral::class, 'id_peripheral');
    // }
}
