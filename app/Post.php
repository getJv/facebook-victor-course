<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ReverseScope;

class Post extends Model
{
    protected $guarded = [];

    //sobre escreve cada chamada aos metodo statico do model
    protected static function boot(){
        parent::boot();
        // usa o scope que criei na mão para modificar a consulta.
        static::addGlobalScope(new ReverseScope());
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
