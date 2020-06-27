<?php

namespace App\Services;

use App\Models\User;

class UserServices extends BaseServices
{
    public function getWebLoginResult ()
    {
        $user = $this->getOneUserByEmail();
        $password = $this->request->getPost('password', 'string');

        if ($this->checkPassword($user, $password) ) return $this->response->redirect("/shielded/dashboard/index");

        $this->flashSession->error('not permitted');
        return $this->response->redirect("/user/sign");

    }

    public function getApiLoginResult()
    {
        $user = $this->getOneUserByEmail();
        $password = $this->request->getPost('password', 'string');

        if ($this->checkPassword($user, $password) ) return json_encode(['status' => 'ok', "action" => $this->url->get(['for'=>'admin'])]);

        return json_encode([
            'error'         => 'not permitted',
            'msg'           => 'email or password is wrong',
            'newCsrfName'   => $this->security->getTokenKey(),
            'newCsrfValue'   => $this->security->getToken(),
        ]);
    }

    public function getOneUserByEmail()
    {
        $email = $this->request->getPost('email', 'string');
        return User::findFirst( "email = '{$email}'" );
    }

    private function checkPassword($user, $password)
    {
        if ( $this->security->checkHash($password, $user->password) ){
            $this->session->set('role', $user->role->title);
            $this->session->set('user', $user->id);
            return true;
        }
        return false;
    }
}