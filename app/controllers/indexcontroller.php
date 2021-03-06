<?php

/**
 * Kontrolleri, joka huolehtii etu-, kirjautumis- ja rekisteröintisivun luonnista.
 */
class IndexController extends BaseController {

    /**
     *  Luo etusivu sen mukaan, onko sessiolla kirjautunutta käyttäjää.
     */
    public static function index() {
        if (isset($_SESSION['username'])) {
            $usersWorks = UsersWorks::getUsersWorks(self::get_user_logged_in()->username);
            View::make('index/index.html', array('usersWorks' => $usersWorks));
        } else {
            View::make('index/index.html');
        }
    }

    /**
     * Luo kirjautumissivu.
     */
    public static function login() {
        View::make('index/login.html');
    }

    /**
     * Kirjaa käyttäjä ulos, eli muuta session tunnus null.
     */
    public static function logout() {
        $_SESSION['username'] = null;
        Redirect::to('/', array('message' => 'Kirjauduit ulos!'));
    }

    /**
     * Luo rekisteröitymissivu.
     */
    public static function create() {
        View::make('index/register.html');
    }

    /**
     * Luo rekisteröitymissivu uudestaan vanhoilla arvoilla ja uusilla virhetiedotteilla.
     * @param array $errors Virheilmoitukset listana.
     * @param array $attributes Vanhat arvot listana.
     */
    public static function createErrors($errors, $attributes) {
        View::make('index/register.html', array('errors' => $errors, 'attributes' => $attributes));
    }

    /**
     * Metodi, joka kutsuu user-mallia tarkastamaan käyttäjän antaman käyttäjätunnuksen ja salasanan pitävyyden. 
     * Jos tunnus ja salasana ovat oikein, kirjataan käyttäjä sisään. Muuten palautetaan kirjautumissivulle virhe-
     * ilmoituksen kera.
     */
    public static function handle_login() {
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('index/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        } else {
            $_SESSION['username'] = $user->username;

            Redirect::to('/', array('message' => 'Kirjautuminen onnistui!'));
        }
    }

}
