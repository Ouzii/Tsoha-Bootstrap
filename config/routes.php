<?php

$routes->get('/', function() {
    IndexController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/login', function() {
    UserController::login();
});

$routes->post('/logging', function() {
    UserController::handle_login();
});

$routes->get('/tyonKohteet', function() {
    WorkObjectController::index();
});

$routes->get('/tyonKohde/:id', function($id) {
    WorkObjectController::show($id);
});

$routes->get('/tyonKohdeKuvaus/:kuvaus', function($kuvaus) {
    WorkObjectController::showKuvaus($kuvaus);
});

$routes->get('/tyokalut', function() {
    WorkToolController::index();
});

$routes->get('/tyokalu/:id', function($id) {
    WorkToolController::show($id);
});

$routes->get('/tyokaluKuvaus/:kuvaus', function($kuvaus) {
    WorkToolController::showKuvaus($kuvaus);
});

$routes->get('/kayttaja/:tunnus', function($tunnus) {
    UserController::show($tunnus);
});

$routes->get('/kayttajat', function() {
    UserController::index();
});

$routes->get('/tyoMuokkaus/:id', function($id) {
    WorkController::edit($id);
});

$routes->post('/tyonMuokkaaminen/:id', function($id) {
    WorkController::update($id);
});

$routes->get('/tyokaluMuokkaus/:id', function($id) {
    WorkToolController::edit($id);
});

$routes->post('/tyokaluMuokkaaminen/:id', function($id) {
    WorkToolController::update($id);
});

$routes->get('/tyonKohdeMuokkaus/:id', function($id) {
    WorkObjectController::edit($id);
});

$routes->post('/tyonKohdeMuokkaaminen/:id', function($id) {
    WorkObjectController::update($id);
});

$routes->get('/kayttajaMuokkaus/:tunnus', function($tunnus) {
    UserController::edit($tunnus);
});

$routes->post('/kayttajaMuokkaaminen/:tunnus', function($tunnus) {
    UserController::update($tunnus);
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
    WorkController::findWithKuvaus();
});

$routes->post('/etsiTyokalu', function() {
    WorkToolController::findWithKuvaus();
});

$routes->post('/etsiTyonKohde', function() {
    WorkObjectController::findWithKuvaus();
});

$routes->post('/etsiKayttaja', function() {
    UserController::findWithKuvaus();
});

$routes->get('/tyoPoisto/:id', function($id) {
    WorkController::destroy($id);
});

$routes->get('/tyokaluPoisto/:id', function($id) {
    WorkToolController::destroy($id);
});

$routes->get('/tyonKohdePoisto/:id', function($id) {
    WorkObjectController::destroy($id);
});

$routes->get('/kayttajaPoisto/:tunnus', function($tunnus) {
    UserController::destroy($tunnus);
});





