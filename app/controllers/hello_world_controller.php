<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  echo 'Tämä on etusivu!';
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      View::make('helloworld.html');
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
