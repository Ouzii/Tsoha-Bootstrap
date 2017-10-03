<?php

/*
 * Kontrolleri, joka huolehtii käyttäjiin liittyvistä toiminnoista.
 */

class UserController extends BaseController {

     /*
      * Hae kaikki käyttäjät ja luo näkymä niistä.
      */
    public static function index() {
        $users = User::all();
        View::make('kayttaja/kayttajat.html', array('kayttajat' => $users));
    }

     /*
      * Etsi käyttäjä annetulla tunnuksella ja luo käyttäjän kuvaussivu.
      */
    public static function show($username) {
        $user = User::find($username);
        View::make('kayttaja/kayttaja.html', array('kayttaja' => $user));
    }

     /*
      * Metodi, jossa tarkastetaan käyttäjän antamien tietojen oikeellisuus
      * ja kutsutaan user-mallia tallentamaan tietokantaan, jos virheitä ei ole.
      */
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

        $user = new User($attributes);
        $errors = $user->errors();
        $unique = $user->validate_unique_tunnus();

        $errors = array_merge($errors, $unique);

        if (count($errors) == 0) {
            $user->save();
            $_SESSION['username'] = $user->tunnus;
            Redirect::to('/', array('message' => 'Tunnus luotu!'));
        } else {
            IndexController::createErrors($errors, $attributes);
        }
    }

     /*
      * Metodi, jolla etsitään haluttu käyttäjä tunnuksen perusteella.
      */
    public static function findWithTunnus() {
        $params = $_POST;
        $searchedUsername = $params['tunnus'];
        $user = User::find($searchedUsername);
        if ($user == null) {
            Redirect::to('/kayttajat', array('message' => 'Ei hakutuloksia!'));
        } else {
            $username = $user->tunnus;
            Redirect::to('/kayttaja/' . $username, array('message' => 'Löytyi!'));
        }
    }

     /*
      * Metodi, jolla tarkastetaan käyttäjän antamat tiedot oikeiksi
      * ja kutsutaan user-mallia päivittämään tietokantaan uudet tiedot.
      */
    public static function update($tunnus) {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'ika' => $params['ika'],
            'tunnus' => $tunnus,
            'salasana' => $params['salasana']
        );

        $user = new User($attributes);

        if (isset($params['admin'])) {
            $attributes['admin'] = 1;
            $user->admin = true;
        } else {
            $attributes['admin'] = 0;
            $user->admin = false;
        }

        $errors = $user->errors();

        if (count($errors) == 0) {
            $user->update();
            Redirect::to('/kayttaja/' . $tunnus, array('message' => 'Käyttäjää muokattu!'));
        } else {
            UserController::editErrors($errors, $attributes);
        }
    }

     /*
      * Metodi, jossa haetaan haluttu käyttäjä ja luodaan sivu kyseisen käyttäjän tietojen muokkaamiseen.
      */
    public static function edit($username) {

        $user = User::find($username);

        $attributes = array(
            'kuvaus' => $user->kuvaus,
            'ika' => $user->ika,
            'admin' => $user->admin,
            'salasana' => $user->salasana,
            'tunnus' => $username
        );

        View::make('/kayttaja/kayttajaMuokkaus.html', array('attributes' => $attributes));
    }

     /*
      * Metodi, jota kutsutaan jos käyttäjän muokkaamisessa on havaittu virheitä. 
      * Tällöin palautetaan jo annetut arvot ja virheilmoitukset.
      */
    public static function editErrors($errors, $attributes) {
        View::make('/kayttaja/kayttajaMuokkaus.html', array('attributes' => $attributes, 'errors' => $errors));
    }

     /*
      * Metodi, jossa etsitään ensin haluttu käyttäjä ja tarkistetaan käyttäjän liitokset olemassaoleviin töihin. 
      * Jos käyttäjä ei liity töihin, niin kutsutaan user-mallia poistamaan käyttäjä tietokannasta. 
      * Muuten palataan käyttäjän tarkasteluun virheilmoituksen kera.
      */
    public static function destroy($username) {
        $user = User::find($username);


        $errors = $user->validate_connections();

        if (count($errors) == 0) {
            $user->destroy();
            Redirect::to('/kayttajat', array('message' => 'Käyttäjä poistettu!'));
        } else {
            Redirect::to('/kayttaja/' . $username, array('errors' => $errors));
        }
    }

     /*
      * Metodi, joka kutsuu user-mallia tarkastamaan käyttäjän antaman käyttäjätunnuksen ja salasanan pitävyyden. 
      * Jos tunnus ja salasana ovat oikein, kirjataan käyttäjä sisään. Muuten palautetaan kirjautumissivulle virhe-
      * ilmoituksen kera.
      */
    public static function handle_login() {
        $params = $_POST;

        $user = User::authenticate($params['tunnus'], $params['salasana']);

        if (!$user) {
            View::make('etusivu/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'tunnus' => $params['tunnus']));
        } else {
            $_SESSION['username'] = $user->tunnus;

            Redirect::to('/', array('message' => 'Kirjautuminen onnistui!'));
        }
    }
    


}
