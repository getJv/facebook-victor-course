<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\PostCollection;

class UserPostController extends Controller
{
    public function index(User $user)
    {
        return new  PostCollection($user->posts);
    }
}
