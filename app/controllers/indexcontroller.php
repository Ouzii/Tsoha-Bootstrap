<?php

class IndexController extends BaseController {

    public static function index() {
        if (isset($_SESSION['tunnus'])) {
        $kayttajanTyot = User::getUsersWorks(self::get_user_logged_in()->tunnus);
        View::make('etusivu/etusivu.html', array('omatTyot' => $kayttajanTyot));
        } else {
            View::make('etusivu/etusivu.html');
        }
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

