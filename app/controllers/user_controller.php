<?php

class UserController extends BaseController {
    
    public static function login() {
        View::make('/user/login.html');
    }
    
    public static function handle_login() {
        $userparams = $_POST;
        
        $user = User::authenticate($userparams['username'], $userparams['password']);
        
        if(!$user) {
            View::make('/user/login.html', array('error'=>"Väärä salasana tai käyttäjätunnus!"));
        } else {
            $_SESSION['user'] = $user->username;
            $_SESSION['friends'] = User::friends($_SESSION['user']);
            Kint::trace();
            Kint::dump($_SESSION['user']);
            Kint::dump($_SESSION['friends']);
            //Redirect::to('/catchList');
        }
        
    }
}

