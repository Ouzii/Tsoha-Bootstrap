<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('suunnitelmat/etusivu.html');
    }

    public static function sandbox() {
            $virhetyo = new Work(array(
                'kohde' => 'Olohuone',                
                'tyokalu' => 'Moppi',
                'kuvaus' => 'Tämä merkkijono on yli 30 merkkiä pitkä ja sen takia ei sovellu työn kuvaukseksi.',                
                'tarkempi_kuvaus' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam feugiat,'
                . ' turpis at pulvinar vulputate, erat libero tristique tellus,'
                . ' nec bibendum odio risus sit amet ante. Aliquam erat volutpat.'
                . ' Nunc auctor. Mauris pretium quam et urna. Fusce nibh. Duis risus.'
                . ' Curabitur sagittis hendrerit ante. Aliquam erat volutpat.'
                . ' Vestibulum erat nulla, ullamcorper nec, rutrum non, nonummy',
            ));
            $errors = $virhetyo->errors();

            Kint::dump($errors);
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
