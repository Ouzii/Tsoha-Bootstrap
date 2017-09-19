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

    public static function store() {
        $params = $_POST;
        if ($params['admin'] == 1) {
            $kayttaja = new User(array(
                'tunnus' => $params['tunnus'],
                'salasana' => $params['salasana'],
                'ika' => $params['ika'],
                'kuvaus' => $params['kuvaus'],
                'admin' => $params['admin']
            ));
        } else {
            $kayttaja = new User(array(
                'tunnus' => $params['tunnus'],
                'salasana' => $params['salasana'],
                'ika' => $params['ika'],
                'kuvaus' => $params['kuvaus'],
                'admin' => null
            ));
        }


//        Kint::dump($params);

        $kayttaja->save();
        Redirect::to('/kayttaja/' . $kayttaja->tunnus, array('message' => 'Tunnus luotu!'));
    }

}
