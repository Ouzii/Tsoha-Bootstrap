<?php

class BaseController {

    public static function get_user_logged_in() {

        if (isset($_SESSION['tunnus'])) {
            $kayttaja = User::find($_SESSION['tunnus']);

            return $kayttaja;
        }
        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['tunnus'])) {
            Redirect::to('/', array('message' => 'Kirjaudu ensin sisään!'));
        }
    }

}
