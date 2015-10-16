<?php

class TrapController extends BaseController {
    
    
    public static function show($trap_id) {
        $trap = Trap::find($trap_id);
        View::make('trap/trap.html', array('trap'=>$trap));
    }
    
    public static function trapList() {
        $traps = Trap::all();
        View::make('trap/trapList.html', array('traps'=>$traps));
    }
    
    
    public static function newTrap() {
        $trap_types = Trap::types();
        View::make('trap/newTrap.html', array('trap_types'=>$trap_types));
    }
    
    
    public static function store() {
        $params = $_POST;
        $attributes = array(
           'trap_type'=>$params['trap_type'],
           'trap_model'=>$params['trap_model'],
           'trap_size'=>$params['trap_size'],
           'trap_color'=>$params['trap_color'],
           'picture_url'=>$params['picture_url'],
           'in_use'=>$params['in_use'],
        );
        
        $trap = new Trap($attributes);
        $errors = $trap->errors();
        
        if(count($errors) === 0) {
            $trap->save();
            Redirect::to('/trap/' . $trap->trap_id, array('message'=>"Pyydystieto lisättiin onnistuneesti!"));  
        } else {
            $trap_types = Trap::types();
            View::make('trap/newTrap.html', array('errors'=>$errors, 'attributes'=>$attributes, 'trap_types'=>$trap_types));
        }
    }
}

