<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

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

        // $key = getenv('JWT_SECRET_KEY');
        // $iat = time();
        // $exp = $iat + (60 * 60);


        // $payload = [
        //     'iss' => 'CI4-API',
        //     'sub' => 'Postman-Token',
        //     'iat' => $iat,
        //     'exp' => $exp,
        //     'email' => $user['email']
        // ];

        // $token = JWT::encode($payload, $key, 'HS256');
        $_SESSION['isLogin'] = true;
        $response = [
            'status' => true,
            'message' => 'Login success',
            'isLogin' => $_SESSION['isLogin'],
        ];

        return $this->respond($response, 200);
    }

    public function logout()
    {

        $_SESSION['isLogin'] = false;

        // session_destroy();
        return $this->respond([
            'status' => true,
            'message' => 'Logout success',
            'isLogin' => $_SESSION['isLogin'],
        ], 200);
    }
}
