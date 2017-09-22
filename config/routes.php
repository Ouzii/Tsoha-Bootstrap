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

$routes->get('/tyonKohteet', function() {
    WorkObjectController::index();
});

$routes->get('/tyonKohde/:kuvaus', function($kuvaus) {
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

$routes->get('/tyonKohdeMuokkaus', function() {
    HelloWorldController::tyonkohdeMuokkaus();
});

$routes->get('/kayttajaMuokkaus', function() {
    HelloWorldController::kayttajaMuokkaus();
});

$routes->post('/tyo', function() {
    WorkController::store();
});

$routes->get('/uusiTyo', function() {
    WorkController::create();
});

$routes->post('/tyonKohde', function() {
    WorkObjectController::store();
});

$routes->get('/uusiTyonKohde', function() {
    WorkObjectController::create();
});

$routes->post('/tyokalu', function() {
    WorkToolController::store();
});

$routes->get('/uusiTyokalu', function() {
    WorkToolController::create();
});

$routes->post('/rekisteroidu', function() {
    UserController::store();
});

$routes->get('/rekisteroityminen', function() {
    UserController::create();
});

$routes->get('/tyot', function() {
    WorkController::index();
});

$routes->get('/tyo/:id', function($id) {
    WorkController::show($id);
});

$routes->post('/etsiTyo', function() {
    WorkController::showKuvaus();
});

$routes->post('/etsiTyokalu', function() {
    WorkToolController::showKuvaus();
});

$routes->post('/etsiTyonKohde', function() {
    WorkObjectController::showKuvaus();
});

$routes->post('/etsiKayttaja', function() {
    UserController::showKuvaus();
});





