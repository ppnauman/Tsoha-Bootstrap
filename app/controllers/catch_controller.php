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
        //$luretypes = LureModel::uniqueTypes();
        //$lures = LureModel::all();
        $luretypes = array('uistin', 'verkko');
        View::make('catch/newCatch.html', array('luretypes'=>$luretypes));
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
      
        $catch = new CatchModel($attributes);
        $errors = $catch->errors();
        
        if (count($errors) === 0) {
            $catch->save();
            Redirect::to('/catchList/' . $catch->saalisID, array('message' => 'Saalistieto lisättiin onnistuneesti!'));    
        } else {
            View::make('catch/newCatch.html', array('errors'=>$errors, 'attributes'=>$attributes));
        }
    }
}
