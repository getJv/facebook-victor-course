<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserImage as UserImageResource;
use Intervention\Image\Facades\Image;

class UserImageController extends Controller
{
    public function store()
    {

        $data = request()->validate([
            'image' => '',
            'width' => '',
            'height' => '',
            'location' => '',
        ]);

        # storage its not accessible from docker. use the comand: php artisan storage:link (inside of container)
        $image = $data['image']->store('user-images', 'public');
        #force the resize image
        Image::make($data['image'])
            ->fit($data['width'], $data['height'])
            ->save(storage_path('app/public/post-images/' . $data['image']->hashname()));

        $userImage = auth()->user()->images()->create([
            #need concat because docker workaround
            'path'  => 'storage/' . $image,
            'width' => $data['width'],
            'height'  => $data['height'],
            'location'  => $data['location'],
        ]);


        return new UserImageResource($userImage);
    }
}
