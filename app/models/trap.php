<?php

class Trap extends BaseModel {
    
    public $trap_id, $owner, $trap_type, $trap_model, $trap_size, $trap_color, $in_use;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM pyydys WHERE omistaja = :user");
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
                'in_use'=>$row['kaytossa'],
            ));
        }
        
        return $traps;
    }
    
    public static function types() {
        $query = DB::connection()->prepare("SELECT tyyppi FROM pyydys WHERE omistaja=:user");
        $query->execute(array('user'=>$_SESSION['user']));
        $resultRows = $query->fetchAll();
        
        $trap_types = array();
        
        foreach($resultRows as $row) {
            $trap_types[] = $row['tyyppi'];
        }
        
        return $trap_types;
    }
}

