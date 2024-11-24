<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
    //
    protected $primaryKey = 'id_locataire';

    protected $fillable = ['user_id', 'id_proprietaire'];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function location(){
        return $this->hasOne(Location::class, 'id_location', 'id');
    }
    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'id_proprietaire');
    }
}
