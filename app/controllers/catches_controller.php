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
}
