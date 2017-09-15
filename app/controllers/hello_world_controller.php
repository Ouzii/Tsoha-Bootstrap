<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('suunnitelmat/etusivu.html');
    }

    public static function sandbox() {
        View::make('helloworld.html');
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }
    
    public static function tyonkohteet() {
        View::make('suunnitelmat/tyonkohteet.html');
    }
    
    public static function tyonkohde() {
        View::make('suunnitelmat/tyonkohde.html');
    }
    
    public static function tyot() {
        View::make('suunnitelmat/tyot.html');
    }
    
    public static function tyo() {
        View::make('suunnitelmat/tyo.html');
    }
    
    public static function tyokalut() {
        View::make('suunnitelmat/tyokalut.html');
    }
    
    public static function tyokalu() {
        View::make('suunnitelmat/tyokalu.html');
    }
    
    public static function kayttaja() {
        View::make('suunnitelmat/kayttaja.html');
    }
    
    public static function kayttajat() {
        View::make('suunnitelmat/kayttajat.html');
    }
    
    public static function tyoMuokkaus() {
        View::make('suunnitelmat/tyoMuokkaus.html');
    }
    
    public static function tyokaluMuokkaus() {
        View::make('suunnitelmat/tyokaluMuokkaus.html');
    }
    
    public static function tyonkohdeMuokkaus() {
        View::make('suunnitelmat/tyonkohdeMuokkaus.html');
    }
    
    public static function kayttajaMuokkaus() {
        View::make('suunnitelmat/kayttajaMuokkaus.html');
    }

}
