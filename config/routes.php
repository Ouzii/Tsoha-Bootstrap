<?php

function check_logged_in() {
    BaseController::check_logged_in();
}
$routes->get('/', function() {
    IndexController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/login', function() {
    UserController::login();
});

$routes->get('/logout', function() {
    UserController::logout();
});

$routes->post('/logging', function() {
    UserController::handle_login();
});

$routes->get('/tyonKohteet', 'check_logged_in', function() {
    WorkObjectController::index();
});

$routes->get('/tyonKohde/:id', 'check_logged_in', function($id) {
    WorkObjectController::show($id);
});

$routes->get('/tyonKohdeKuvaus/:kuvaus', 'check_logged_in', function($kuvaus) {
    WorkObjectController::showKuvaus($kuvaus);
});

$routes->get('/tyokalut', 'check_logged_in', function() {
    WorkToolController::index();
});

$routes->get('/tyokalu/:id', 'check_logged_in', function($id) {
    WorkToolController::show($id);
});

$routes->get('/tyokaluKuvaus/:kuvaus', 'check_logged_in', function($kuvaus) {
    WorkToolController::showKuvaus($kuvaus);
});

$routes->get('/kayttaja/:tunnus', 'check_logged_in', function($tunnus) {
    UserController::show($tunnus);
});

$routes->get('/kayttajat', 'check_logged_in', function() {
    UserController::index();
});

$routes->get('/tyoMuokkaus/:id', 'check_logged_in', function($id) {
    WorkController::edit($id);
});

$routes->post('/tyonMuokkaaminen/:id', 'check_logged_in', function($id) {
    WorkController::update($id);
});

$routes->get('/tyokaluMuokkaus/:id', 'check_logged_in', function($id) {
    WorkToolController::edit($id);
});

$routes->post('/tyokaluMuokkaaminen/:id', 'check_logged_in', function($id) {
    WorkToolController::update($id);
});

$routes->get('/tyonKohdeMuokkaus/:id', 'check_logged_in', function($id) {
    WorkObjectController::edit($id);
});

$routes->post('/tyonKohdeMuokkaaminen/:id', 'check_logged_in', function($id) {
    WorkObjectController::update($id);
});

$routes->get('/kayttajaMuokkaus/:tunnus', 'check_logged_in', function($tunnus) {
    UserController::edit($tunnus);
});

$routes->post('/kayttajaMuokkaaminen/:tunnus', 'check_logged_in', function($tunnus) {
    UserController::update($tunnus);
});

$routes->post('/tyo', 'check_logged_in', function() {
    WorkController::store();
});

$routes->get('/uusiTyo', 'check_logged_in', function() {
    WorkController::create();
});

$routes->post('/tyonKohde', 'check_logged_in', function() {
    WorkObjectController::store();
});

$routes->get('/uusiTyonKohde', 'check_logged_in', function() {
    WorkObjectController::create();
});

$routes->post('/tyokalu', 'check_logged_in', function() {
    WorkToolController::store();
});

$routes->get('/uusiTyokalu', 'check_logged_in', function() {
    WorkToolController::create();
});

$routes->post('/rekisteroidu', function() {
    UserController::store();
});

$routes->get('/rekisteroityminen', function() {
    UserController::create();
});

$routes->get('/tyot', 'check_logged_in', function() {
    WorkController::index();
});

$routes->get('/tyo/:id', 'check_logged_in', function($id) {
    WorkController::show($id);
});

$routes->post('/etsiTyo', 'check_logged_in', function() {
    WorkController::findWithKuvaus();
});

$routes->post('/etsiTyokalu', 'check_logged_in', function() {
    WorkToolController::findWithKuvaus();
});

$routes->post('/etsiTyonKohde', 'check_logged_in', function() {
    WorkObjectController::findWithKuvaus();
});

$routes->post('/etsiKayttaja', 'check_logged_in', function() {
    UserController::findWithKuvaus();
});

$routes->get('/tyoPoisto/:id', 'check_logged_in', function($id) {
    WorkController::destroy($id);
});

$routes->get('/tyokaluPoisto/:id', 'check_logged_in', function($id) {
    WorkToolController::destroy($id);
});

$routes->get('/tyonKohdePoisto/:id', 'check_logged_in', function($id) {
    WorkObjectController::destroy($id);
});

$routes->get('/kayttajaPoisto/:tunnus', 'check_logged_in', function($tunnus) {
    UserController::destroy($tunnus);
});





