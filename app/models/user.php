<?php

class User extends BaseModel {
    
    public $username, $password, $password_confirm, $email, $first_name, $sure_name, $validators;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_username', 'validate_password', 'validate_first_name',
            'validate_sure_name');  
    }
    
    public static function authenticate($usr, $pswd) {
        $query = DB::connection()->prepare("SELECT * FROM kalastaja WHERE"
                . " kayttajatunnus = :usr AND salasana = :pswd LIMIT 1");
        $query->execute(array('usr' => $usr, 'pswd' => $pswd));
        
        $resultRow = $query->fetch();
        
        if(!$resultRow) {   
            return null;
            
        } else {  
            $user = new User(array(
                'username'=> $resultRow['kayttajatunnus'],
                'first_name'=> $resultRow['etunimi'],
                'sure_name'=>$resultRow['sukunimi'],
            ));
            
            return $user;
        }
    }
    
 
    
    
    public static function friends($usr) {
        $query = DB::connection()->prepare('SELECT DISTINCT kalastaja '
                . 'FROM kalakaveri WHERE kalastaja = :usr');
        $query -> execute(array('usr' => $usr));
        
        $resultRows = $query ->fetchAll();
        $friends = array();
        
        foreach ($resultRows as $friend) {
            $friends[] = $friend['kayttajatunnus'];
        }
        
        return $friends;
    }
    
    public static function find($usr) {
        $query = DB::connection()->prepare("SELECT * FROM kalastaja WHERE"
                . " kayttajatunnus = :usr LIMIT 1");
        $query -> execute(array('usr'=> $usr));
        $resultRow = $query -> fetch();
        
        $user = new User(array(
            'username' => $resultRow['kayttajatunnus'],
            'password' => $resultRow['salasana'],
            'email' => $resultRow['email'],
            'first_name' => $resultRow['etunimi'],
            'sure_name' => $resultRow['sukunimi']
        ));
        
        return $user;
    }
    
    public static function all_usernames() {
        $query = DB::connection()->prepare("SELECT kayttajatunnus FROM kalastaja");
        $query->execute();
        $resultRow = $query->fetchAll();
        $usernames = array();
        
        foreach($resultRow as $username) {
            $usernames[] = $username['kayttajatunnus'];
        }
        
        return $usernames;
    }

    public function validate_username() {
        $query = DB::connection()->prepare("SELECT kayttajatunnus FROM kalastaja"
                ." WHERE kayttajatunnus = :new_username LIMIT 1");
        $query->execute(array('new_username' => $this->username));
        $resultRow = $query->fetch();
        $errors = array();

        if ($resultRow) {
            $errors[] = "Käyttäjänimi on jo varattu. Syötä uusi käyttäjänimi.";
        }

        return $errors;
    }
    
    public function validate_password() {
        $errors = array();
        if($this->password != $this->password_confirm) {
            $errors[] = "Salasana ja sen vahvistus eivät täsmää!";
        }
        if(!$this->str_length_between($this->password, 8, 32)) {
            $errors[] = "Salasanan pituuden tulee olla 8-32 merkkiä!";
        }
        return $errors;
    }
    
    public function validate_first_name() {
        $errors = array();
        if(!$this->str_length_between($this->first_name, 1, 32)) {
            $errors[] = "Etunimen pituuden tulee olla 1-32 merkkiä.";
        }
        return $errors;
    }
    
    public function validate_sure_name() {
        $errors = array();
        if(!$this->str_length_between($this->sure_name, 1, 32)) {
            $errors[] = "Sukunimen pituuden tulee olla 1-32 merkkiä.";
        }
        return $errors;
    }
    
    public function validate_email() {
        $errors = array();
        if(!($this->valid_mail($this->email))) {
            $errors[] = "Syöttämäsi sähköpostiosoite on virheellinen!";
        }
        return errors;
    }
    
    

}

