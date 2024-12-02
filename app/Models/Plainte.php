<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plainte extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'sujet',
        'description',
        //'date_plainte',
        'status',
        'id_locataire',
        'id_proprietaire',
    ];

    public function locataire()
    {
        return $this->belongsTo(Locataire::class, 'id_locataire');
    }

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'id_proprietaire');
    }
}
