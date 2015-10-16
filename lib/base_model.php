<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
      $errors = array();

      foreach($this->validators as $validator){
        // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
        $validator_errors = $this->{$validator}();
        $errors = array_merge($errors, $validator_errors); 
      }
      
      return $errors;
    }
    
    
    public function str_length_between ($string, $min, $max) {
        if(strlen($string) < $min || strlen($string) > $max) {
            return false;
        }
        return true;
    }
    
    public function valid_mail($mail_string) {
        $mail_regexp ="/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/";
        if(preg_match($mail_regexp, $mail_string) != 1) {
            return false;
        }
        return true;
    }
    
    public function valid_url($url_str) {
        
        if(filter_var( $url_str, FILTER_VALIDATE_URL )){
            return true;
        }
        
        return false;  
    }

  }
