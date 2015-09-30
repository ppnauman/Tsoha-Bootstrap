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
  
  $routes->get('/catchList', function() {
      CatchController::index();
  });
  
  $routes->get('/catchList/:id', function($id) {
      CatchController::show($id);
  });
  
  $routes->get('/newCatch', function() {
      CatchController::newCatch();
  });
  
  $routes->post('/newCatch', function() {
      CatchController::store();
  });
  
  $routes->get('/login', function() {
      UserController::login();
  });
  
  $routes->post('/login', function() {
      UserController::handle_login();
  });
  
  $routes->get('/saalis', function() { 
      HelloWorldController::saalis();
  });

  $routes->get('/lisaaSaalis', function() {
      HelloWorldController::lisaaSaalis();
  });