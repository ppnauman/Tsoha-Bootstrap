<?php

class TrapController extends BaseController {
    
    
    public static function show($trap_id) {
        self::check_logged_in();
        $trap = Trap::find($trap_id);
        View::make('trap/trap.html', array('trap'=>$trap));
    }
    
    
    public static function trapList() {
        self::check_logged_in();
        $traps = Trap::all();
        View::make('trap/trapList.html', array('traps'=>$traps));
    }
    
    
    public static function newTrap() {
        self::check_logged_in();
        $trap_types = Trap::types();
        View::make('trap/newTrap.html', array('trap_types'=>$trap_types));
    }
    
    
    public static function store() {
        self::check_logged_in();
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
    
    
    public static function viewUpdate($trap_id) {
        self::check_logged_in();
        $trap = Trap::find($trap_id);
        $attributes = array(
            'trap_id'=>$trap->trap_id,
            'trap_type'=>$trap->trap_type,
            'trap_model'=>$trap->trap_model,
            'trap_size'=>$trap->trap_size,
            'trap_color'=>$trap->trap_color,
            'picture_url'=>$trap->picture_url,
            'in_use'=>$trap->in_use
        );
        
        $trap_types = Trap::types();
        
        View::make('trap/updateTrap.html', array('attributes'=>$attributes, 'trap_types'=>$trap_types));
    }
    
    
    public static function update($trap_id) {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'trap_id'=>$trap_id,
            'trap_type'=>$params['trap_type'],
            'trap_model'=>$params['trap_model'],
            'trap_size'=>$params['trap_size'],
            'trap_color'=>$params['trap_color'],
            'picture_url'=>$params['picture_url'],
            'in_use'=>$params['in_use']
        );
        
        $trap = new Trap($attributes);
        $errors = $trap->errors();
        
        if(count($errors) === 0) {
            $trap -> update();
            Redirect::to('/trap/' . $trap->trap_id, array('trap'=>$trap, 'message'=>"Pyydystieto päivitettiin onnistuneesti!"));
        } else {
            $trap_types = Trap::types();
            View::make('trap/updateTrap.html', array('errors'=>$errors, 'attributes'=>$attributes, 'trap_types'=>$trap_types));
        }
    }
    
    
    public static function destroy($trap_id) {
        self::check_logged_in();
        $trap = new Trap(array('trap_id'=>$trap_id));
        $success = $trap->destroy();
        
        if($success) {
            Redirect::to('/trapList', array('message'=>"Pyydystieto poistettiin onnistuneesti"));
        } else {
            $trap= Trap::find($trap_id);
            View::make('trap/trap.html', array('trap'=>$trap, 'message'=>"Pyydys on liitetty saalistietoon, joten sitä ei voida poistaa. Jos pyydys ei ole enää käytössä, merkitse se käytöstä poistetuksi Muokkaa -pyydystä -näkymässä."));
        }
    }
}

