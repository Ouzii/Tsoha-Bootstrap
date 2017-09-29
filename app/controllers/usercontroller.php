<?php

class UserController extends BaseController {

    public static function index() {
        $kayttajat = User::all();
        View::make('kayttaja/kayttajat.html', array('kayttajat' => $kayttajat));
    }

    public static function show($tunnus) {
        $kayttaja = User::find($tunnus);
        View::make('kayttaja/kayttaja.html', array('kayttaja' => $kayttaja[0]));
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
                'tunnus' => (String) $params['tunnus'],
                'salasana' => $params['salasana'],
                'ika' => $params['ika'],
                'kuvaus' => $params['kuvaus'],
                'admin' => $params['admin']
            );
        } else {

            $attributes = array(
                'tunnus' => (String) $params['tunnus'],
                'salasana' => $params['salasana'],
                'ika' => $params['ika'],
                'kuvaus' => $params['kuvaus'],
                'admin' => null
            );
        }

        $kayttaja = new User($attributes);
        $errors = $kayttaja->errors();
        $uniikki = $kayttaja->validate_unique_tunnus();

        $errors = array_merge($errors, $uniikki);

        if (count($errors) == 0) {
            $kayttaja->save();
            $_SESSION['tunnus'] = $kayttaja->tunnus;
            Redirect::to('/', array('message' => 'Tunnus luotu!'));
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

    public static function update($tunnus) {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'ika' => $params['ika'],
            'tunnus' => $tunnus,
            'salasana' => $params['salasana']
        );

        $kayttaja = new User($attributes);

        if (isset($params['admin'])) {
            $attributes['admin'] = 1;
            $kayttaja->admin = true;
        } else {
            $attributes['admin'] = 0;
            $kayttaja->admin = false;
        }

        $errors = $kayttaja->errors();

        if (count($errors) == 0) {
            $kayttaja->update();
            Redirect::to('/kayttaja/' . $tunnus, array('message' => 'Käyttäjää muokattu!'));
        } else {
            UserController::editErrors($errors, $attributes);
        }
    }

    public static function edit($tunnus) {

        $kayttaja = User::find($tunnus);

        $attributes = array(
            'kuvaus' => $kayttaja[0]->kuvaus,
            'ika' => $kayttaja[0]->ika,
            'admin' => $kayttaja[0]->admin,
            'salasana' => $kayttaja[0]->salasana,
            'tunnus' => $tunnus
        );

        View::make('/kayttaja/kayttajaMuokkaus.html', array('attributes' => $attributes));
    }

    public static function editErrors($errors, $attributes) {
        View::make('/kayttaja/kayttajaMuokkaus.html', array('attributes' => $attributes, 'errors' => $errors));
    }

    public static function destroy($tunnus) {
        $kayttaja = User::find($tunnus);


        $errors = $kayttaja[0]->validate_connections();

        if (count($errors) == 0) {
            $kayttaja[0]->destroy();
            Redirect::to('/kayttajat', array('message' => 'Käyttäjä poistettu!'));
        } else {
            Redirect::to('/kayttaja/' . $tunnus, array('errors' => $errors));
        }
    }

    public static function login() {
        View::make('etusivu/login.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $kayttaja = User::authenticate($params['tunnus'], $params['salasana']);

        if (!$kayttaja) {
            View::make('etusivu/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'tunnus' => $params['tunnus']));
        } else {
            $_SESSION['tunnus'] = $kayttaja[0]->tunnus;

            Redirect::to('/', array('message' => 'Kirjautuminen onnistui!'));
        }
    }
    
    public static function logout() {
        
        $_SESSION['tunnus'] = null;
        Redirect::to('/', array('message' => 'Kirjauduit ulos!'));
    }

}
