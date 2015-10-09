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
  
  $routes->get('/registration', function() {
      UserController::registration();
  });
  
  $routes->post('/registration', function() {
      UserController::store_user();
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
  
  $routes->get('/catchUpdate/:id', function($id) {
      CatchController::viewUpdate($id);
  });
  
  $routes->post('/catchUpdate/:id', function($id) {
      CatchController::update($id);
  });
  
  $routes->post('/catch/:id/destroy', function($id) {
      CatchController::destroy($id);
  });
  
  $routes->post('/logout', function() {
      UserController::logout();
  });
  
  $routes->get('/saalis', function() { 
      HelloWorldController::saalis();
  });

  $routes->get('/lisaaSaalis', function() {
      HelloWorldController::lisaaSaalis();
  });