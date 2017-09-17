<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('suunnitelmat/etusivu.html');
    }

    public static function sandbox() {
        $tyo = Work::find(1);
        $tyot = Work::all();
        $kohteet = WorkObject::all();
        $kohde = WorkObject::find('Jääkaappi');
        $tyokalut = WorkTool::all();
        $tyokalu = WorkTool::find('Rätti');
        $kayttajat = User::all();
        $kayttaja = User::find('Matti');
        $kayttajienTyot = UsersWorks::all();
        $kayttajanTyot = UsersWorks::find('Maija');
        $tyonTekijat = Work::getUsers(1);
        // Kint-luokan dump-metodi tulostaa muuttujan arvon
        Kint::dump($tyo);
        Kint::dump($tyot);
        Kint::dump($kohteet);
        Kint::dump($kohde);
        Kint::dump($tyokalut);
        Kint::dump($tyokalu);
        Kint::dump($kayttajat);
        Kint::dump($kayttaja);
        Kint::dump($kayttajienTyot);
        Kint::dump($kayttajanTyot);
        Kint::dump($tyonTekijat);
    }

    public static function login() {
        View::make('etusivu/login.html');
    }

    public static function tyonkohteet() {
        View::make('tyonKohde/tyonkohteet.html');
    }

    public static function tyonkohde() {
        View::make('tyonKohde/tyonkohde.html');
    }

    public static function tyot() {
        View::make('tyo/tyot.html');
    }

    public static function tyo() {
        View::make('tyo/tyoKuvaus.html');
    }

    public static function tyokalut() {
        View::make('tyokalu/tyokalut.html');
    }

    public static function tyokalu() {
        View::make('tyokalu/tyokalu.html');
    }

    public static function kayttaja() {
        View::make('kayttaja/kayttaja.html');
    }

    public static function kayttajat() {
        View::make('kayttaja/kayttajat.html');
    }

    public static function tyoMuokkaus() {
        View::make('tyo/tyoMuokkaus.html');
    }

    public static function tyokaluMuokkaus() {
        View::make('tyokalu/tyokaluMuokkaus.html');
    }

    public static function tyonkohdeMuokkaus() {
        View::make('tyonKohde/tyonkohdeMuokkaus.html');
    }

    public static function kayttajaMuokkaus() {
        View::make('kayttaja/kayttajaMuokkaus.html');
    }

    public static function uusiTyo() {
        View::make('tyo/uusiTyo.html');
    }

    public static function uusiTyonkohde() {
        View::make('tyonKohde/uusiTyonkohde.html');
    }

    public static function uusiTyokalu() {
        View::make('tyokalu/uusiTyokalu.html');
    }

    public static function rekisteroityminen() {
        View::make('etusivu/rekisteroityminen.html');
    }

}
