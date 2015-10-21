<?php

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/', function() {
      UserController::login();
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
  
  $routes->post('/catchUpd/:id', function($id) {
      CatchController::update($id);
  });
  
  $routes->post('/catch/:id/destroy', function($id) {
      CatchController::destroy($id);
  });
  
  $routes->post('/logout', function() {
      UserController::logout();
  });
  
  $routes->post('/trap', function() {
      TrapController::store();
  });
  
  $routes->get('/trap', function() {
      TrapController::newTrap();
  });
  
  $routes->get('/trap/:id', function($id) {
      TrapController::show($id);
  });
  
  $routes->post('/trap/:id/destroy', function($id) {
      TrapController::destroy($id);
  });
  
  $routes->get('/trapList', function() {
    TrapController::trapList();
  });
  
  $routes->get('/trapUpdate/:id', function($id) {
      TrapController::viewUpdate($id);
  });
  
  $routes->post('/trapUpdate/:id', function($id) {
      TrapController::update($id);
  });
  
  $routes->get('/userUpdate', function() {
      UserController::viewUpdate();
  });
  
  $routes->post('/userUpdate', function() {
      UserController::update();
  });
  



