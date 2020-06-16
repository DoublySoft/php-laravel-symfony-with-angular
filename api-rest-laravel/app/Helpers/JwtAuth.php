<?php

namespace App\Helpers;

use DomainException;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use UnexpectedValueException;

class JwtAuth
{
    public $key;

    public function __construct()
    {
        $this->key = 'this_is_a_super_secret_key-123456789';
    }

    public function signup($email, $password, $getToken = null)
    {
        // Search if the user exists with their credentials
        $user = DB::table('users')->where([
            ['email', '=', $email],
            ['password', '=', $password]
        ])->first();

        // Check if credentials are corrects
        $signup = false;

        if (is_object($user)) {
            $signup = true;
        }


        // Generate token with identified user data
        if ($signup) {

            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);


            // Return decoded data or token depending on the parameter
            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
                'status' => 'error',
                'message' => 'Login incorrecto'
            );
        }


        return $data;
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (UnexpectedValueException $e) {
            $auth = false;
        } catch (DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }

}
