<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/login', function() {
  HelloWorldController::login();
});

  $routes->get('/tyonkohteet', function() {
  HelloWorldController::tyonkohteet();
});

  $routes->get('/tyonkohde', function() {
  HelloWorldController::tyonkohde();
});

  $routes->get('/tyot', function() {
  HelloWorldController::tyot();
});

  $routes->get('/tyo', function() {
  HelloWorldController::tyo();
});

  $routes->get('/tyokalut', function() {
  HelloWorldController::tyokalut();
});

  $routes->get('/tyokalu', function() {
  HelloWorldController::tyokalu();
});

  $routes->get('/kayttaja', function() {
  HelloWorldController::kayttaja();
});

  $routes->get('/kayttajat', function() {
  HelloWorldController::kayttajat();
});

  $routes->get('/tyoMuokkaus', function() {
  HelloWorldController::tyoMuokkaus();
});


    
