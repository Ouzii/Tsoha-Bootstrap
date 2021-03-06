<?php

/**
 * Kontrolleri, joka huolehtii käyttäjiin liittyvistä toiminnoista.
 */
class UserController extends BaseController {

    /**
     * Hae kaikki käyttäjät ja luo näkymä niistä.
     */
    public static function index() {
        $users = User::allAlphabetical();
        View::make('/user/users.html', array('users' => $users));
    }

    /**
     * Etsi käyttäjä annetulla tunnuksella ja luo käyttäjän descriptionsivu.
     * @param String $username Haetun käyttäjän username.
     */
    public static function show($username) {
        $user = User::find($username);
        View::make('/user/user.html', array('user' => $user));
    }

    /**
     * Metodi, jossa tarkastetaan käyttäjän antamien tietojen oikeellisuus
     * ja kutsutaan user-mallia tallentamaan tietokantaan, jos virheitä ei ole.
     */
    public static function store() {
        $params = $_POST;
        
            $attributes = array(
                'username' => (String) $params['username'],
                'password' => $params['password'],
                'age' => $params['age'],
                'description' => $params['description'],
                'admin' => null
            );

        $user = new User($attributes);
        $errors = $user->errors();
        $unique = $user->validate_unique_username();

        $errors = array_merge($errors, $unique);

        if (count($errors) == 0) {
            $user->save();
            $_SESSION['username'] = $user->username;
            Redirect::to('/', array('message' => 'Tunnus luotu!'));
        } else {
            IndexController::createErrors($errors, $attributes);
        }
    }

    /**
     * Metodi, jolla etsitään haluttu käyttäjä tunnuksen perusteella.
     */
    public static function findWithDescription() {
        $params = $_POST;
        $searchedUsername = $params['username'];
        $user = User::find($searchedUsername);
        if ($user == null) {
            Redirect::to('/users', array('message' => 'Ei hakutuloksia!'));
        } else {
            $username = $user->username;
            Redirect::to('/user/' . $username, array('message' => 'Löytyi!'));
        }
    }

    /**
     * Metodi, jolla tarkastetaan käyttäjän antamat tiedot oikeiksi
     * ja kutsutaan user-mallia päivittämään tietokantaan uudet tiedot.
     * @param String $username Päivitettävän käyttäjän tunnus.
     */
    public static function update($username) {
        $params = $_POST;

        $attributes = array(
            'description' => $params['description'],
            'age' => $params['age'],
            'username' => $username,
            'password' => $params['password']
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
            Redirect::to('/user/' . $username, array('message' => 'Käyttäjää muokattu!'));
        } else {
            UserController::editErrors($errors, $attributes);
        }
    }

    /**
     * Metodi, jossa haetaan haluttu käyttäjä ja luodaan sivu kyseisen käyttäjän tietojen muokkaamiseen.
     * @param String $username Haetun käyttäjän username.
     */
    public static function edit($username) {

        $user = User::find($username);

        $attributes = array(
            'description' => $user->description,
            'age' => $user->age,
            'admin' => $user->admin,
            'password' => $user->password,
            'username' => $username
        );

        View::make('/user/userEdit.html', array('attributes' => $attributes));
    }

    /**
     * Metodi, jota kutsutaan jos käyttäjän muokkaamisessa on havaittu virheitä. 
     * Tällöin palautetaan jo annetut arvot ja virheilmoitukset.
     * @param array $errors Virheilmoitukset listana.
     * @param array $attributes Vanhat arvot listana.
     */
    public static function editErrors($errors, $attributes) {
        View::make('/user/userEdit.html', array('attributes' => $attributes, 'errors' => $errors));
    }

    /**
     * Metodi, jossa etsitään ensin haluttu käyttäjä ja tarkistetaan käyttäjän liitokset olemassaoleviin töihin. 
     * Jos käyttäjä ei liity töihin, niin kutsutaan user-mallia poistamaan käyttäjä tietokannasta. 
     * Muuten palataan käyttäjän tarkasteluun virheilmoituksen kera.
     * @param String $username Poistettavan käyttäjän username.
     */
    public static function destroy($username) {
        $isAdmin = User::find($_SESSION['username']);

        if ($isAdmin->admin || $_SESSION['username'] == $username) {

            $user = User::find($username);


            $errors = $user->validate_connections();

            if (count($errors) == 0) {
                $user->destroy();
                if ($_SESSION['username'] == $user->username) {
                    $_SESSION['username'] = null;
                    Redirect::to('/', array('message' => 'Käyttäjä ' . $user->username . ' poistettu!'));
                } else {
                    Redirect::to('/users', array('message' => 'Käyttäjä ' . $user->username . ' poistettu!'));
                }
            } else {
                Redirect::to('/user/' . $username, array('errors' => $errors));
            }
        } else {
            Redirect::to('/users', array('message' => 'Sinun täytyy kirjautua admin-tunnuksilla poistaaksesi toisen käyttäjän!'));
        }
    }
    
    /**
     * Metodi, jolla poistetaan käyttäjä liitoksista huolimatta, kunhan on todettu oikeudet poistamiseen.
     * @param String $username Poistettavan käyttäjän username.
     */
    public static function destroyWithErrors($username) {
        $isAdmin = User::find($_SESSION['username']);

        if ($isAdmin->admin || $_SESSION['username'] == $username) {

            $user = User::find($username);
            
                $user->destroy();
                if ($_SESSION['username'] == $user->username) {
                    $_SESSION['username'] = null;
                    Redirect::to('/', array('message' => 'Käyttäjä ' . $user->username . ' poistettu!'));
                } else {
                    Redirect::to('/users', array('message' => 'Käyttäjä ' . $user->username . ' poistettu!'));
                }
        } else {
            Redirect::to('/users', array('message' => 'Sinun täytyy kirjautua admin-tunnuksilla poistaaksesi toisen käyttäjän!'));
        }
    }

}
