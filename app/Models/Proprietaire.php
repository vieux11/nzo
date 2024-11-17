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
}
