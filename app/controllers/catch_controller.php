<?php

class CatchController extends BaseController {
    
    public static function index() {
        $catches = CatchModel::all();
        //Kint::trace();
        //Kint::dump($catches);
        View::make('catch/catchList.html', array('catches'=>$catches));
    }
    
    public static function show($id) {
        $catch = CatchModel::find($id);
        View::make('catch/catch.html', array('catch'=>$catch));
    }
    
    public static function newCatch() {
        $traps = Trap::all();
        $trap_types = Trap::types();
       
        View::make('catch/newCatch.html', array('traps'=>$traps, 'trap_types'=>$trap_types, 'friend_of'=>$_SESSION['friend_of']));
    }
    
    public static function viewUpdate($id) {
        $catchers = CatchModel::catchers($id);
        $catch = CatchModel::find($id);
        $attributes = array(
            'catch_id'=> $catch->catch_id,
            'date' => $catch->date,
            'time' => $catch->time,
            'species' => $catch->species,
            'count' => $catch->count,
            'length' => $catch->length,
            'weight' => $catch->weight,
            'water_sys' => $catch->water_sys,
            'location' => $catch->location,
            'wind_speed' => $catch->wind_speed,
            'wind_dir' => $catch->wind_dir,
            'air_temp' => $catch->air_temp,
            'water_temp' => $catch->water_temp,
            'cloudiness' => $catch->cloudiness,
            'notes' => $catch->notes,
            'picture_url' => $catch->picture_url,
            'trap_id' => $catch->trap_model,
            'friends' => $catchers
        );
        
        View::make('catch/updateCatch.html', array('attributes'=>$attributes, 'friend_of'=>$_SESSION['friend_of']));
    }
    
    
    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'catch_id' => $id,
            'date' => $params['date'],
            'time' => $params['time'],
            'species' => $params['species'],
            'count' => $params['count'],
            'length' => $params['length'],
            'weight' => $params['weight'],
            'water_sys' => $params['water_sys'],
            'location' => $params['location'],
            'wind_speed' => $params['wind_speed'],
            'wind_dir' => $params['wind_dir'],
            'air_temp' => $params['air_temp'],
            'water_temp' => $params['water_temp'],
            'cloudiness' => $params['cloudiness'],
            'notes' => $params['notes'],
            'picture_url' => $params['picture_url'],
            'trap_id' => $params['trap_model'],
        );
        if(isset($params['friends'])) {
            $attributes['friends'] = $params['friends'];
        }
        
        $catch = new CatchModel($attributes);       
        $errors = $catch->errors();
        
        if(count($errors) === 0){
            $catch->update();
            //Redirect::to('/catchList/' . $catch->catch_id ."", array('message'=>"Saalistieto päivitettiin onnistuneesti!"));
        } else {    
            $catchers = CatchModel::catchers($catch->catch_id);
            $catch->friends = $catchers;
            View::make('catch/updateCatch.html', array('attributes'=>$attributes, 'errors'=>$errors));
        }
    }
    
    
    public static function store() {
        $params = $_POST;
        $attributes = array(
            'date' => $params['date'],
            'time' => $params['time'],
            'species' => $params['species'],
            'count' => $params['count'],
            'length' => $params['length'],
            'weight' => $params['weight'],
            'water_sys' => $params['water_sys'],
            'location' => $params['location'],
            'wind_speed' => $params['wind_speed'],
            'wind_dir' => $params['wind_dir'],
            'air_temp' => $params['air_temp'],
            'water_temp' => $params['water_temp'],
            'cloudiness' => $params['cloudiness'],
            'notes' => $params['notes'],
            'picture_url' => $params['picture_url'],
            'trap_id' => $params['trap_model'],       
        );
        
        if(isset($params['friends'])) {
            $attributes['friends'] = $params['friends'];
        }
        
        //Kint::dump($attributes);
        $catch = new CatchModel($attributes);
        $errors = $catch->errors();
        
        if (count($errors) === 0) {
            $catch->save();
            //Kint::dump($catch->catch_id);
            Redirect::to("/catchList/" . $catch->catch_id ."", array('message' => 'Saalistieto lisättiin onnistuneesti!'));    
        } else {
            $traps = Trap::all();
            $trap_types = Trap::types();
            View::make('catch/newCatch.html', array('errors'=>$errors, 'attributes'=>$attributes, 'traps'=> $traps, 'trap_types'=>$trap_types, 'friend_of'=>$_SESSION['friend_of']));
        }
    }
    
    
    public static function destroy($id) {
        $catch = new CatchModel(array('catch_id'=>$id));
        $catch->destroy();
        
        Redirect::to('/catchList', array('message'=>"Saalistieto poistettiin onnistuneesti."));
    }
}
