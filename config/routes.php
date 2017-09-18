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
      WorkObjectController::index();
});

  $routes->get('/tyonkohde/:kuvaus', function($kuvaus) {
      WorkObjectController::show($kuvaus);
});

  $routes->get('/tyokalut', function() {
      WorkToolController::index();
});

  $routes->get('/tyokalu/:kuvaus', function($kuvaus) {
      WorkToolController::show($kuvaus);
});

  $routes->get('/kayttaja/:tunnus', function($tunnus) {
      UserController::show($tunnus);
});

  $routes->get('/kayttajat', function() {
      UserController::index();
});

  $routes->get('/tyoMuokkaus', function() {
  HelloWorldController::tyoMuokkaus();
});

  $routes->get('/tyokaluMuokkaus', function() {
  HelloWorldController::tyokaluMuokkaus();
});

  $routes->get('/tyonkohdeMuokkaus', function() {
  HelloWorldController::tyonkohdeMuokkaus();
});

  $routes->get('/kayttajaMuokkaus', function() {
  HelloWorldController::kayttajaMuokkaus();
});

  $routes->get('/uusiTyo', function() {
  HelloWorldController::uusiTyo();
});

  $routes->get('/uusiTyonkohde', function() {
  HelloWorldController::uusiTyonkohde();
});

  $routes->get('/uusiTyokalu', function() {
  HelloWorldController::uusiTyokalu();
});

  $routes->get('/rekisteroityminen', function() {
  HelloWorldController::rekisteroityminen();
});

  $routes->get('/tyot', function() {
      WorkController::index();
});

  $routes->get('/tyo/:id', function($id) {
      WorkController::show($id);
});


    
