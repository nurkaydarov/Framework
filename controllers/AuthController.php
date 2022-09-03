<?php

namespace app\controllers;

use app\core\Request;
use app\models\RegisterModel;

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
            $registerModel = new RegisterModel();

            $registerModel->loadData($request->getBody());


            if($registerModel->validate() && $registerModel->register())
            {
                return 'Success';
            }
            echo '<pre>';
            var_dump($registerModel->errors);
            echo '</pre>';
            return 'Handle submitted data';
        }
        return $this->render('register');
    }
}