<?php

/*
 * Kontrolleri, joka huolehtii etu-, kirjautumis- ja rekisteröintisivun luonnista.
 */

class IndexController extends BaseController {

    /*
     *  Luo etusivu sen mukaan, onko sessiolla kirjautunutta käyttäjää.
     */
    public static function index() {
        if (isset($_SESSION['username'])) {
            $usersWorks = UsersWorks::getUsersWorks(self::get_user_logged_in()->tunnus);
            View::make('etusivu/etusivu.html', array('omatTyot' => $usersWorks));
        } else {
            View::make('etusivu/etusivu.html');
        }
    }

     /*
      * Luo kirjautumissivu.
      */
    public static function login() {
        View::make('etusivu/login.html');
    }

     /*
      * Kirjaa käyttäjä ulos, eli muuta session tunnus null.
      */
    public static function logout() {
        $_SESSION['userName'] = null;
        Redirect::to('/', array('message' => 'Kirjauduit ulos!'));
    }

     /*
      * Luo rekisteröitymissivu.
      */
    public static function create() {
        View::make('etusivu/rekisteroityminen.html');
    }

     /*
      * Luo rekisteröitymissivu uudestaan vanhoilla arvoilla ja uusilla virhetiedotteilla.
      */
    public static function createErrors($errors, $attributes) {
        View::make('etusivu/rekisteroityminen.html', array('errors' => $errors, 'attributes' => $attributes));
    }

}
