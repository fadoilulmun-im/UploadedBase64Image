<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Base64ImageController extends Controller
{
    public function upload(Request $req)
    {
        $rules = ['image' => 'required|base64image'];
        $validator = \Validator::make($req->all(), $rules);

        // validate the request form if no request has been sent
        // or the request is not in the form of base64image
        if ($validator->fails()) {
            return response()->json(
                [
                    'messagae' => 'Please fill in the form correctly',
                    'data' => $validator->errors(),
                ],
                400 //response status code
            );
        }

        // if it passes validation, it will continue to this code
        $path = storage_path('app/upload'); // path to save the image file

        // just in case if the path does not exist, it will be created
        if (!\File::exists($path)) {
            \File::makeDirectory($path, 0777, true, true);
        }

        // save image to path
        
        $imageName = \Str::random(10).'.'.'png';
        $path .= '/'.$imageName;
        $image = \Intervention\Image\Facades\Image::make($req->image)->save($path);

        // and then return the image has been successfully uploaded
        return response()->file($path);
    }
}
