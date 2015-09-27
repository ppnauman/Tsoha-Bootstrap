<?php

class CatchController extends BaseController {
    
    public static function index() {
        $catches = CatchModel::all();
        View::make('catches/catchList.html', array('catches'=>$catches));
  
    }
    
    public static function show($id) {
        $catch = CatchModel::find($id);
        View::make('catches/catch.html', array('catch'=>$catch));
    }
    
    public static function newCatch() {
        //$luretypes = LureModel::uniqueTypes();
        //$lures = LureModel::all();
        $luretypes = array('uistin', 'verkko');
        View::make('catches/newCatch.html', array('luretypes'=>$luretypes));
    }
    
    public static function store() {
        $params = $_POST;
        $catch = new CatchModel(array(
            'pvm' => $params['date'],
            'kellonaika' => $params['time'],
            'kalalaji' => $params['species'],
            'lkm' => $params['count'],
            'pituus' => $params['length'],
            'paino' => $params['weight'],
            'vesisto' => $params['lake'],
            'paikka' => $params['place'],
            'tuulenVoimakkuus' => $params['windSpd'],
            'tuulenSuunta' => $params['windDir'],
            'ilmanLampo' => $params['airTemp'],
            'vedenLampo' => $params['waterTemp'],
            'pilvisyys' => $params['cloudiness'],
            'huomiot' => $params['notes'],
            'saaliskuva' => $params['picture'],
            'pyydysID' => $params['trapmodel'],
        ));
        
        $catch->save();
        Redirect::to('/catchList/' . $catch->saalisID, array('message' => 'Saalistieto lisättiin onnistuneesti!'));
        
    }

}
