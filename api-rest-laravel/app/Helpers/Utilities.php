<?php

namespace App\Helpers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Utilities
{

    /**
     * Get user with token
     *
     * @param Request $request
     * @return bool|object|null
     */
    public function getIdentified(Request $request)
    {
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization');
        return $jwtAuth->checkToken($token, true);
    }

    /**
     * @param Request $request
     * @param string $validation
     * @param string $disk
     * @return JsonResponse
     */
    public function uploadFile(Request $request, string $validation, string $disk)
    {

        // Get file
        $image = $request->file('file0');

        // Image validation
        $validate = Validator::make($request->all(), [
            'file0' => $validation
        ]);

        // Save image
        if ($image || !$validate->fails()) {

            $image_name = time() . $image->getClientOriginalName();
            Storage::disk($disk)->put($image_name, File::get($image));

            // Return result
            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );

        } else {

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'File upload error.'
            );

        }

        return response()->json($data, $data['code']);
    }

    public function getFile(string $fileName, string $disk)
    {

        try {
            $file = Storage::disk($disk)->get($fileName);
            return new Response($file, 200);
        } catch (FileNotFoundException $e) {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'The file does not exist.'
            );
        }

        return response()->json($data, $data['code']);
    }

}
