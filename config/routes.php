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
    IndexController::login();
});

$routes->get('/logout', function() {
    IndexController::logout();
});

$routes->post('/logging', function() {
    IndexController::handle_login();
});

$routes->get('/workObjects', 'check_logged_in', function() {
    WorkObjectController::index();
});

$routes->get('/workObject/:id', 'check_logged_in', function($id) {
    WorkObjectController::show($id);
});

$routes->get('/workObjectDesc/:description', 'check_logged_in', function($description) {
    WorkObjectController::showDescription($description);
});

$routes->get('/workToolDesc/:description', 'check_logged_in', function($description) {
    WorkToolController::showDescription($description);
});

$routes->get('/workTools', 'check_logged_in', function() {
    WorkToolController::index();
});

$routes->get('/workTool/:id', 'check_logged_in', function($id) {
    WorkToolController::show($id);
});

$routes->get('/user/:username', 'check_logged_in', function($username) {
    UserController::show($username);
});

$routes->get('/users', 'check_logged_in', function() {
    UserController::index();
});

$routes->get('/workEdit/:id', 'check_logged_in', function($id) {
    WorkController::edit($id);
});

$routes->post('/workEditing/:id', 'check_logged_in', function($id) {
    WorkController::update($id);
});

$routes->get('/workToolEdit/:id', 'check_logged_in', function($id) {
    WorkToolController::edit($id);
});

$routes->post('/workToolEditing/:id', 'check_logged_in', function($id) {
    WorkToolController::update($id);
});

$routes->get('/workObjectEdit/:id', 'check_logged_in', function($id) {
    WorkObjectController::edit($id);
});

$routes->post('/workObjectEditing/:id', 'check_logged_in', function($id) {
    WorkObjectController::update($id);
});

$routes->get('/userEdit/:username', 'check_logged_in', function($username) {
    UserController::edit($username);
});

$routes->post('/userEditing/:usernames', 'check_logged_in', function($username) {
    UserController::update($username);
});

$routes->post('/work', 'check_logged_in', function() {
    WorkController::store();
});

$routes->get('/newWork', 'check_logged_in', function() {
    WorkController::create();
});

$routes->post('/workObject', 'check_logged_in', function() {
    WorkObjectController::store();
});

$routes->get('/newWorkObject', 'check_logged_in', function() {
    WorkObjectController::create();
});

$routes->post('/workTool', 'check_logged_in', function() {
    WorkToolController::store();
});

$routes->get('/newWorkTool', 'check_logged_in', function() {
    WorkToolController::create();
});

$routes->post('/registering', function() {
    UserController::store();
});

$routes->get('/register', function() {
    IndexController::create();
});

$routes->get('/works', 'check_logged_in', function() {
    WorkController::index();
});

$routes->get('/work/:id', 'check_logged_in', function($id) {
    WorkController::show($id);
});

$routes->post('/findWork', 'check_logged_in', function() {
    WorkController::findWithKuvaus();
});

$routes->post('/findTool', 'check_logged_in', function() {
    WorkToolController::findWithDescription();
});

$routes->post('/findObject', 'check_logged_in', function() {
    WorkObjectController::findWithDescription();
});

$routes->post('/findUser', 'check_logged_in', function() {
    UserController::findWithDescription();
});

$routes->get('/workDelete/:id', 'check_logged_in', function($id) {
    WorkController::destroy($id);
});

$routes->get('/workToolDelete/:id', 'check_logged_in', function($id) {
    WorkToolController::destroy($id);
});

$routes->get('/workObjectDelete/:id', 'check_logged_in', function($id) {
    WorkObjectController::destroy($id);
});

$routes->get('/userDelete/:username', 'check_logged_in', function($username) {
    UserController::destroy($username);
});
$routes->get('/userDelete/:username/rekursive', 'check_logged_in', function($username) {
    UserController::destroyWithErrors($username);
});

$routes->get('/workDone/:id', 'check_logged_in', function($id) {
    WorkController::markAsDone($id);
});





