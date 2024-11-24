<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propriete extends Model
{
    //
    use HasFactory;

    /**
     * Les champs modifiables.
     */
    protected $fillable = [
        'adresse',
        'description',
        'id_proprietaire',
    ];

    /**
     * Relation : Une propriété appartient à un propriétaire.
     */
    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'id_proprietaire');
    }
    public function location(){
        return $this->hasOne(Location::class, 'id_propriete','id');
    }
}
