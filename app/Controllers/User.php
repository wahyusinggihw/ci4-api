<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $users = new UserModel();
        return $this->respond(['users' => $users->findAll()], 200);
    }

    public function update()
    {


        $email = $this->request->getVar('email');
        $users = new UserModel();

        $user = $users->where('email', $email);

        $rules = [
            'new_username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
        ];

        if ($this->validate($rules)) {
            // update data
            $users = new UserModel();
            $data = [
                'username' => $this->request->getVar('new_username'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $user->set($data)->update();

            return $this->respond([
                'status' => true,
                'message' => 'User updated'
            ], 200);
        } else {
            $response = [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            return $this->fail($response, 409);
        }
    }

    public function delete()
    {
        $email = $this->request->getVar('email');
        $users = new UserModel();
        $user = $users->where('email', $email)->first();

        $rule = [
            'email' => 'required'
        ];

        if ($this->validate($rule)) {
            if ($user != null) {
                $users->where('email', $email)->delete();
                return $this->respond([
                    'status' => true,
                    'message' => 'User deleted.'
                ], 200);
            } else {
                return $this->respond([
                    'status' => true,
                    'message' => 'User not found.'
                ], 404);
            }
        } else {
            $response = [
                'status' => false,
                'message' => $this->validator->getErrors()
            ];
            return $this->fail($response, 409);
        }


        // try {
        //     $user->delete();
        // } catch (\Exception $e) {
        //     //throw $th;
        //     var_dump($e);
        //     die;
        // }


        // $users->

    }
}
