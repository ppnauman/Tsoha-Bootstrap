<?php

class User extends BaseModel {
    
    public $username, $password, $email, $first_name, $sure_name;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function authenticate($usr, $pswd) {
        $query = DB::connection()->prepare("SELECT * FROM kalastaja WHERE kayttajatunnus = :usr AND salasana = :pswd LIMIT 1");
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
        $query = DB::connection()->prepare("SELECT * FROM kalastaja WHERE kayttajatunnus = :usr LIMIT 1");
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
}

