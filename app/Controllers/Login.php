<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;

class Login extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $userModel = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Email not found'
            ], 401);
        }

        $password_verify = password_verify($password, $user['password']);

        if (!$password_verify) {
            return $this->respond([
                'status' => false,
                'message' => 'Wrong password'
            ], 401);
        }

        $key = getenv('JWT_SECRET_KEY');
        $iat = time();
        $exp = $iat + (60 * 60);


        $payload = [
            'iss' => 'CI4-API',
            'sub' => 'Postman-Token',
            'iat' => $iat,
            'exp' => $exp,
            'email' => $user['email']
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'status' => true,
            'message' => 'Login success',
            'token' => $token
        ];

        return $this->respond($response, 200);
    }
}
