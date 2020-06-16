<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Helpers\Utilities;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function register(Request $request)
    {
        // Collect data sent by POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params) && !empty($params_array)) {
            // Received params

            // Clean data
            $params_array = array_map('trim', $params_array);

            // Validate data
            $validate = Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);

            if ($validate->fails()) {
                // Validation failed
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'The user was not been created',
                    'errors' => $validate->errors()
                );
            } else {
                // Correct validation

                // Code password
                $pwd = hash('sha256', $params->password);
                password_hash($params->password, PASSWORD_BCRYPT, ['cost' => 4]);

                // Create user
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;

                // Save user
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'The user was been created successfully',
                    'user' => $user
                );
            }

        } else {
            // No params have been received

            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'The data sent is not correct'
            );

        }

        return response()->json($data, $data['code']);
    }

    public function login(Request $request)
    {
        $jwtAuth = new JwtAuth();

        // Collect data sent by POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Valid data

        $validate = Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            // Validation failed

            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Unidentified user.',
                'errors' => $validate->errors()
            );

        } else {
            // Correct validation

            // Code password
            $pwd = hash('sha256', $params->password);

            // Return token or data
            $data = $jwtAuth->signup($params->email, $pwd);
            if (!empty($params->getToken)) {
                $data = $jwtAuth->signup($params->email, $pwd, true);
            }

        }

        return response()->json($data, 200);
    }

    public function update(Request $request)
    {
        // Check if user are identified
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Collect data sent by POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if ($checkToken && !empty($params_array)) {
            // Update user

            // Get identified user
            $user = $jwtAuth->checkToken($token, true);

            // Validate data
            $validate = Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users,' . $user->sub,
                'password' => 'required'
            ]);

            // Remove fields that will not be updated
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['create_at']);
            unset($params_array['remember_token']);

            // Actualize user un DataBase
            $userUpdate = DB::table('users')->where([
                ['id', '=', $user->sub]
            ])->update($params_array);

            // Return array with results
            $data = array(
                'code' => 200,
                'status' => 'success',
                'message' => $user,
                'changes' => $params_array
            );

        } else {
            // Unidentified user

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Unidentified user.'
            );

        }

        return response()->json($data, $data['code']);
    }

    public function upload(Request $request)
    {
        $utilities = new Utilities();
        return $utilities->uploadFile($request, 'required|image|mimes:jpg,jpeg,png,gif', 'users');
    }

    public function getFile($filename)
    {
        $utilities = new Utilities();
        return $utilities->getFile($filename, 'users');
    }

    public function detail($id)
    {
        $user = User::find($id);

        if (is_object($user)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'User does not exist.'
            );
        }

        return response()->json($data, $data['code']);
    }

}
