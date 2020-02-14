<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                'code'  => 404,
                'title' => 'User not Found',
                'detail' => 'The user can be found with given information'
            ]
        ], 404);
    }
}
