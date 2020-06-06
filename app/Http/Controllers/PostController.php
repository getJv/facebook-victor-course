<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Post;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostCollection as PostCollection;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{

    public function index()
    {

        $friends = Friend::friendships();

        if ($friends->isEmpty()) {
            return new PostCollection(request()->user()->posts);
        }

        return new PostCollection(
            Post::whereIn(
                'user_id',
                [
                    $friends->pluck('user_id'),
                    $friends->pluck('friend_id')
                ]
            )->get()
        );
    }

    public function store()
    {
        $data = request()->validate([
            'body' => '',
            'image' => '',
            'width' => '',
            'height' => '',
        ]);

        if (isset($data['image'])) {
            $image = $data['image']->store('post-images', 'public');

            Image::make($data['image'])
                ->fit($data['width'], $data['height'])
                ->save(storage_path('app/public/post-images/' . $data['image']->hashname()));
        }

        $post = request()->user()->posts()->create([
            'body' => $data['body'],
            'image' => 'storage/' . $image   ?? null,
        ]);

        return new PostResource($post);
    }
}
