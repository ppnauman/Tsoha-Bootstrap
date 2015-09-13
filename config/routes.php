<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/kirjautuminen', function() {
      HelloWorldController::kirjautuminen();
  });
  
  $routes->get('/rekisteroityminen', function() {
      HelloWorldController::rekisteroityminen();
  });
  
  $routes->get('/listaaSaaliit', function() {
      HelloWorldController::listaaSaaliit();
  });
  
  $routes->get('/saalis', function() {
      HelloWorldController::saalis();
  });

  $routes->get('/lisaaSaalis', function() {
      HelloWorldController::lisaaSaalis();
  });