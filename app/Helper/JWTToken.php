<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Middleware\VerifyCsrfToken;

class JWTToken
{

    static function createToken($userEmail, $user_id): string
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel_token',
            'iat' => time(),
            'exp' => time() + 60 * 60,
            'userEmail' => $userEmail,
            'user_id' => $user_id
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    static function createTokenForResetPassword($userEmail, $user_id): string
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel_token',
            'iat' => time(),
            'exp' => time() + 60 * 10,
            'userEmail' => $userEmail,
            'user_id' => '0'
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
    static function verifyToken($token): string|object
    {
        try {

            if ($token == null) {
                return "unauthorized";
            } else {
                $key = env('JWT_KEY');
                $decode = JWT::decode($token, new Key($key, 'HS256'));
                return $decode;
            }

        } catch (Exception $e) {

            return "unauthorized";
        }

    }
}
