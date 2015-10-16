<?php

class Trap extends BaseModel {
    
    public $trap_id, $owner, $trap_type, $trap_model, $trap_size, $trap_color, $picture_url, $in_use, $validators;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_trap_type', 'validate_trap_model', 'validate_trap_size',
                            'validate_trap_color', 'validate_picture_url');
    }

    
    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM pyydys WHERE omistaja = :user ORDER BY tyyppi ASC, kaytossa DESC, malli ASC, koko ASC, vari ASC");
        $query->execute(array('user'=>$_SESSION['user']));
        $resultRows = $query->fetchAll();
        $traps = array();
        
        foreach($resultRows as $row) {
            $traps[] = new Trap(array(
                'trap_id'=>$row['pyydysid'],
                'owner'=>$row['omistaja'],
                'trap_type'=>$row['tyyppi'],
                'trap_model'=>$row['malli'],
                'trap_size'=>$row['koko'],
                'trap_color'=>$row['vari'],
                'picture_url'=>$row['pyydyskuva'],
                'in_use'=>$row['kaytossa'],
            ));
        }
        
        
        Kint::dump($traps);
        return $traps;
    }
    
    
    public static function find($trap_id) {
        $query = DB::connection()->prepare("SELECT * from pyydys WHERE pyydysid=:id LIMIT 1");
        $query->execute(array('id'=>$trap_id));
        $resultRow = $query->fetch();
        
        $trap = new Trap(array(
            'trap_id'=>$resultRow['pyydysid'],
            'owner'=> $resultRow['omistaja'],
            'trap_type'=> $resultRow['tyyppi'],
            'trap_model'=> $resultRow['malli'],
            'trap_size'=> $resultRow['koko'],
            'trap_color'=> $resultRow['vari'],
            'picture_url'=> $resultRow['pyydyskuva'],
            'in_use'=> $resultRow['kaytossa']
        ));
        
        return $trap;
    }
 
    
    public function save() {
        try {
            $query = DB::connection()->prepare("INSERT INTO pyydys (omistaja, tyyppi, malli, koko, vari, pyydyskuva, kaytossa)"
                . " VALUES (:user, :trap_type, :trap_model, :trap_size, :trap_color, :picture_url, :in_use)"
                . " RETURNING pyydysid");
            $query->execute(array('user'=>$_SESSION['user'], 'trap_type'=>$this->trap_type,
                'trap_model' => $this->trap_model, 'trap_size'=>$this->trap_size, 'trap_color'=>$this->trap_color, 
                'picture_url' => $this->picture_url, 'in_use'=>$this->in_use));

            $resultRow = $query->fetch();
            $this->trap_id = $resultRow['pyydysid'];
        
        } catch (PDOException $e) {
            Kint::dump($e);
        }
    }    
    
    
    public function types() {
        $query = DB::connection()->prepare("SELECT DISTINCT tyyppi FROM pyydys WHERE omistaja=:user");
        $query->execute(array('user'=>$_SESSION['user']));
        $resultRows = $query->fetchAll();
        
        $trap_types = array();
        
        foreach($resultRows as $row) {
            $trap_types[] = $row['tyyppi'];
        }
        
        return $trap_types;
    }
    
    
    
    
    //user input validators
    public function  validate_trap_type() {
        $errors = array();
        
        if(!$this->str_length_between($this->trap_type, 1, 32)) {
            $errors[] = "Pyydystyyppi -kentän syötteen tulee olla 1-32 merkkiä pitkä";
        }
        
        return $errors;
    }
    
    public function validate_trap_model() {
        $errors = array();
        
        if(!$this->str_length_between($this->trap_model, 1, 32)) {
            $errors[] = "Pyydysmalli -kentän syötteen tulee olla 1-32 merkkiä pitkä";
        }
        
        return $errors;
    }
    
    public function validate_trap_size() {
        $errors = array();
        
        if(!$this->str_length_between($this->trap_size, 1, 16)) {
            $errors[] = "Pyydyksen koko -kentän syötteen tulee olla 1-16 merkkiä pitkä.";
        }
        
        return $errors;
    }
    
    public function validate_trap_color() {
        $errors = array();
        
        if(!$this->str_length_between($this->trap_color, 1, 32)) {
            $errors[] = "Pyydyksen väri -kentän syötteen tulee olla 1-32 merkkiä pitkä.";
        }
        
        return $errors;
    }
    
    public function validate_picture_url() {
        $errors = array();
        
        if(!$this->valid_url($this->picture_url) && !$this->picture_url=="") {
            $errors[] = "Pyydyskuvan osoitteena tulee olla oikea URL-osoite.";
        }
        
        return $errors;
    }
}

