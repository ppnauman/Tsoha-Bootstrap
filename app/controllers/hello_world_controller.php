<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  echo 'Tämä on etusivu!';
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      //$catch_1 = CatchModel::find(1);
      $catch = new CatchModel(array(
          'kalalaji'=>"lahna",
          'lkm' => '0',
          'vedenLampo' => '-3',
      ));
      $errors = $catch->errors();
      Kint::dump($errors);
    }
    
    public static function kirjautuminen(){
        View::make('/suunnitelmat/kirjautuminen.html');
    }
    
    public static function rekisteroityminen(){
        View::make('/suunnitelmat/rekisteroityminen.html');
    }
    
    public static function listaaSaaliit(){
        View::make('/suunnitelmat/listaaSaaliit.html');
    }
    
    public static function saalis() {
        View::make('/suunnitelmat/saalis.html');
    }
    
    public static function lisaaSaalis() {
        View::make('/suunnitelmat/lisaaSaalis.html');
    }
    
    
  }
