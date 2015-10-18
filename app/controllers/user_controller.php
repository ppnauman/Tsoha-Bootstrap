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
            $_SESSION['friend_of'] = User::friend_of($_SESSION['user']);
            //Kint::trace();
            //Kint::dump($_SESSION['user']);
            //Kint::dump($_SESSION['friend_of']);
            Redirect::to('/catchList');
        }
        
    }
    
    public static function registration() {
        $users = User::all_usernames();
        View::make('/user/registration.html', array('users'=>$users));
    }
    
    
    public static function store_user() {
        $user_params = $_POST;
        $attributes = array(
            'username' => $user_params['username'],
            'password' => $user_params['password'],
            'password_confirm' => $user_params['password_confirm'],
            'first_name' => $user_params['first_name'],
            'sure_name' => $user_params['sure_name'],
            'email' => $user_params['email']
        );
        
        if(isset($user_params['friends'])){
            $attributes['friends'] = $user_params['friends'];
        }
        
        $user = new User($attributes);
        $errors = $user->errors();
        
        if(count($errors) === 0) {
            $user->save();
            Redirect::to('/login', array('msg'=>"Uusi käyttäjätili luotu tunnuksella ".$user->username));
        } else {
            $users = User::all_usernames();
            View::make('user/registration.html', array('attributes'=> $attributes, 'users'=>$users, 'errors'=> $errors));
        }   
    }
    
    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('msg'=>"Olet nyt kirjautunut ulos!"));
    }
    
    
    
    public static function viewUpdate() {
        self::check_logged_in();
        $user = self::get_user_logged_in();
        $attributes = array(
            'username' => $user->username,
            'password' => $user->password,
            'password_confirm' => $user->password_confirm,
            'first_name' => $user->first_name,
            'sure_name' => $user->sure_name,
            'email' => $user->email
        );
        $attributes['friends'] = User::friends($_SESSION['user']);
        $users = User::all_usernames();
        
        View::make('user/updateUser.html', array('attributes'=>$attributes, 'users'=>$users));
    }
    
    
    public static function update() {
        self::get_user_logged_in();
        $params = $_POST;
        $attributes = array(
            'username' => $_SESSION['user'],
            'password' => $params['password'],
            'password_confirm' => $params['password_confirm'],
            'first_name' => $params['first_name'],
            'sure_name' => $params['sure_name'],
            'email' => $params['email']
        );
        
        if(isset($params['friends'])) {
            $attributes['friends'] = $params['friends'];
        }
        
        $user = new User($attributes);
        $user->update();
        Redirect::to('/catchList', array('message'=>"Käyttäjätiedot päivitetty!"));
        
    }
}


