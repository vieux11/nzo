<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Proprietaire extends Model
{
    //
    protected $primaryKey = 'id_proprietaire';

    protected $fillable = ['user_id'];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function locations()
    {
        return $this->hasMany(Location::class, 'id_proprietaire', 'id');
    }
    public function proprietes()
    {
        return $this->hasMany(Propriete::class, 'id_proprietaire', 'id');
    }
    public function locataires()
    {
        return $this->hasMany(Locataire::class, 'id_proprietaire', 'id');
    }

}
