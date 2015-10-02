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
            //Kint::trace();
            //Kint::dump($_SESSION['user']);
            //Kint::dump($_SESSION['friends']);
            Redirect::to('/catchList');
        }
        
    }
    
    public function registration() {
        $users = User::all_usernames();
        View::make('/user/registration.html', array('users'=>$users));
    }
    
    
    public function store_user() {
        $user_params = $_POST;
        $attributes = array(
            'username' => $user_params['username'],
            'password' => $user_params['password'],
            'password_confirm' => $user_params['password_confirm'],
            'first_name' => $user_params['first_name'],
            'sure_name' => $user_params['sure_name'],
            'email' => $user_params['email'],
        );
        
        $user = new User($attributes);
        $errors = $user->errors();
        
        if(count($errors) === 0) {
            //save to database
        } else {
            View::make('user/registration.html', array('attributes'=> $attributes, 'errors'=> $errors));
        }   
    }
}


