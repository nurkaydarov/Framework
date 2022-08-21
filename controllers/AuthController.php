<?php

namespace app\controllers;

use app\core\Request;

class AuthController extends \app\core\Controller
{
    public function login(){
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request){
        $this->setLayout('auth');
        if($request->isPOST())
        {
            return 'Handle submitted data';
        }
        return $this->render('register');
    }
}