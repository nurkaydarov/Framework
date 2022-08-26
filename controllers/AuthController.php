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

            echo '<pre>';
            var_dump($registerModel);
            echo '</pre>';
            if($registerModel->validate() && $registerModel->register())
            {
                return 'Success';
            }
            return 'Handle submitted data';
        }
        return $this->render('register');
    }
}