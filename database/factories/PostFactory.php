<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\User;
use App\Post;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'body' => $faker->sentence,
        'image' => 'image.jpg',
    ];
});
