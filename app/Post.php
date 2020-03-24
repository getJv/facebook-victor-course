<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ReverseScope;

class Post extends Model
{
    protected $guarded = [];

    //sobre escreve cada chamada aos metodo statico do model
    protected static function boot()
    {
        parent::boot();
        // usa o scope que criei na mão para modificar a consulta.
        static::addGlobalScope(new ReverseScope());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
