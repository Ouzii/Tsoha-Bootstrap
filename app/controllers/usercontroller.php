<?php

class UserController extends BaseController {

    public static function index() {
        $kayttajat = User::all();
        View::make('kayttaja/kayttajat.html', array('kayttajat' => $kayttajat));
    }

    public static function show($tunnus) {
        $kayttaja = User::find($tunnus);
        View::make('kayttaja/kayttaja.html', array('kayttaja' => $kayttaja));
    }

    public static function create() {
        View::make('etusivu/rekisteroityminen.html');
    }

    public static function createErrors($errors, $attributes) {
        View::make('etusivu/rekisteroityminen.html', array('errors' => $errors, 'attributes' => $attributes));
    }

    public static function store() {
        $params = $_POST;


        if ($params['admin'] == 1) {

            $attributes = array(
                'tunnus' => (String)$params['tunnus'],
                'salasana' => $params['salasana'],
                'ika' => $params['ika'],
                'kuvaus' => $params['kuvaus'],
                'admin' => $params['admin']
            );
        } else {

            $attributes = array(
                'tunnus' => (String)$params['tunnus'],
                'salasana' => $params['salasana'],
                'ika' => $params['ika'],
                'kuvaus' => $params['kuvaus'],
                'admin' => null
            );
        }
        
        $kayttaja = new User($attributes);
        $errors = $kayttaja->errors();

//        Kint::dump($params);

        if (count($errors) == 0) {
            $kayttaja->save();
            Redirect::to('/kayttaja/' . $kayttaja->tunnus, array('message' => 'Tunnus luotu!'));
        } else {
            UserController::createErrors($errors, $attributes);
        }
    }

    public static function findWithKuvaus() {
        $params = $_POST;
        $etsittyTunnus = $params['tunnus'];
        $kayttaja = User::find($etsittyTunnus);
        if ($kayttaja == null) {
            Redirect::to('/kayttajat', array('message' => 'Ei hakutuloksia!'));
        } else {
            $tunnus = $kayttaja[0]->tunnus;
            Redirect::to('/kayttaja/' . $tunnus, array('message' => 'Löytyi!'));
        }
    }

}
