<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    use HasFactory;

    /**
     * Les champs modifiables.
     */
    protected $fillable = [
        'date',
        'description',
        'loyer_montant',
        'devise',
        'id_propriete',
        'id_locataire',
        'id_proprietaire',
        'confirm',
    ];
    protected $casts = [
        'confirm' => 'boolean',
    ];
    /**
     * Boot method to set default date automatically.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($location) {
            $location->date = now(); // Assigne automatiquement la date actuelle
            if ($location->propriete) {
                $location->id_proprietaire = $location->propriete->id_proprietaire;
            }
        });
    }
    /**
     * Les relations.
     */
    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'id_proprietaire','id');
    }

    // Une location est associée à une propriété
    public function propriete()
    {
        return $this->belongsTo(Propriete::class, 'id_propriete','id');
    }

    // Une location est associée à un locataire
    public function locataire()
    {
        return $this->belongsTo(Locataire::class, 'id_locataire', 'id');
    }
}
