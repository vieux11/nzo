<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payement extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'montant',
        'devise',
        'date_payement',
        'status',
        'methode_paiement',
        'id_location',
        'id_locataire',
        'mois',
        'annee',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relations
    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location', 'id');
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class, 'id_locataire', 'id');
    }
}
