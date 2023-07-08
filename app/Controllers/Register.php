<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Register extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $rules = [
            'email' => ['rules' => 'required|valid_email|is_unique[users.email]'],
            'password' => ['rules' => 'required|min_length[8]'],
            'confirm_password' => ['rules' => 'required|matches[password]']
        ];

        if ($this->validate($rules)) {
            $model = new UserModel();
            $data = [
                'email' => $this->request->getVar('email'),
                'username' => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $model->save($data);

            return $this->respond([
                'status' => true,
                'message' => 'User created'
            ], 200);
        } else {
            $response = [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];

            return $this->fail($response, 409);
        }
    }
}
