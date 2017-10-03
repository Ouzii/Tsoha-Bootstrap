<?php

class BaseController {

    public static function get_user_logged_in() {

        if (isset($_SESSION['username'])) {
            $kayttaja = User::find($_SESSION['username']);

            return $kayttaja;
        }
        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['username'])) {
            Redirect::to('/', array('message' => 'Kirjaudu ensin sisään!'));
        }
    }

}
