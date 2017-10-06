<?php

/**
 * Kontrolleri, joka hoitaa työn kohteisiin liittyvät toiminnallisuudet.
 */
class WorkObjectController extends BaseController {

    /**
     * Haetaan tietokannasta kaikki työn kohteet ja luodaan niistä näkymä.
     */
    public static function index() {
        $workObjects = WorkObject::allAlphabetical();
        View::make('tyonKohde/tyonKohteet.html', array('tyonKohteet' => $workObjects));
    }

    /**
     * Etsitään haluttu työn kohde ja luodaan siitä näkymä.
     * @param int $id Haetun työn kohteen id.
     */
    public static function show($id) {
        $workObject = WorkObject::find($id);
        View::make('tyonKohde/tyonKohdeKuvaus.html', array('tyonKohde' => $workObject));
    }

    /**
     * Etsitään haluttu työn kohde kuvauksen perusteella ja luodaan siitä näkymä.
     * @param String $description Haetun työn kohteen kuvaus.
     */
    public static function showKuvaus($description) {
        $workObject = WorkObject::findWithDescription($description);

        if ($workObject == null) {
            Redirect::to('/tyonKohteet', array('message' => 'Ei hakutuloksia!'));
        } else {
            $id = $workObject->id;
            Redirect::to('/tyonKohde/' . $id);
        }
    }

    /**
     * Luodaan näkymä työn kohteen luomiselle.
     */
    public static function create() {
        View::make('tyonKohde/uusiTyonKohde.html');
    }

    /**
     * Luodaan uusi työkohteen luomisen näkymä vanhoilla arvoilla ja virhetiedotteella.
     * @param array $errors Virheilmoitukset listana.
     * @param array $attributes Vanhat arvot listana.
     */
    public static function createErrors($errors, $attributes) {
        View::make('tyonKohde/uusiTyonKohde.html', array('errors' => $errors, 'attributes' => $attributes));
    }

    /**
     * Tarkistetaan käyttäjän antamat tiedot sallituiksi
     * ja kutsutaan workobject-mallia päivittämään tietokantaan uudet arvot.
     * @param int $id Päivitettänä kohteen id.
     */
    public static function update($id) {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
            'id' => $id
        );

        $workObject = new WorkObject($attributes);
        $errors = $workObject->errors();

        if (count($errors) == 0) {
            $workObject->update();
            Redirect::to('/tyonKohde/' . $id, array('message' => 'Työn kohdetta muokattu!'));
        } else {
            WorkObjectController::editErrors($errors, $attributes);
        }
    }

    /**
     * Haetaan haluttu työn kohde ja luodaan sille muokkausnäkymä.
     * @param int $id Päivitettävän kohteen id.
     */
    public static function edit($id) {

        $workObject = WorkObject::find($id);

        $attributes = array(
            'kuvaus' => $workObject->kuvaus,
            'tarkempi_kuvaus' => $workObject->tarkempi_kuvaus,
            'id' => $id
        );

        View::make('/tyonKohde/tyonKohdeMuokkaus.html', array('attributes' => $attributes));
    }

    /**
     * Luodaan uusi työkohteen muokkausnäkymä vanhoilla arvoilla ja virhetiedotteella.
     * @param array $errors Virheilmoitukset listana.
     * @param array $attributes Vanhat arvot listana.
     */
    public static function editErrors($errors, $attributes) {
        View::make('/tyonKohde/tyonKohdeMuokkaus.html', array('attributes' => $attributes, 'errors' => $errors));
    }

    /**
     * Tarkistetaan onko käyttäjällä oikeutta poistaa työn kohteita ja jos on,
     * niin tarkastetaan työn kohteen yhteydet olemassaoleviin töihin. Jos yhteyksiä ei ole,
     * niin pyydetään mallia poistamaan työn kohde tietokannasta.
     * @param int $id Poistettavan kohteen id.
     */
    public static function destroy($id) {

        $isAdmin = User::find($_SESSION['username']);

        if ($isAdmin->admin) {
            $workObject = WorkObject::find($id);
            $errors = $workObject->validate_connections();

            if (count($errors) == 0) {
                $workObject->destroy();
                Redirect::to('/tyonKohteet', array('message' => 'Työn kohde poistettu!'));
            } else {
                Redirect::to('/tyonKohde/' . $id, array('errors' => $errors));
            }
        } else {
            $errors[] = 'Sinun täytyy kirjautua admin-tunnuksilla poistaaksesi työn kohteen!';
            Redirect::to('/tyonKohde/' . $id, array('errors' => $errors));
        }
    }

    /**
     * Tarkastetaan käyttäjän antamat tiedot sallituiksi 
     * ja kutsutaan workobject-mallia tallentamaan tiedot tietokantaan.
     */
    public static function store() {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
        );
        $workObject = new WorkObject($attributes);
        $errors = $workObject->errors();

        if (count($errors) == 0) {
            $workObject->save();
            Redirect::to('/tyonKohde/' . $workObject->id, array('message' => 'Työn kohde luotu!'));
        } else {
            WorkObjectController::createErrors($errors, $attributes);
        }
    }

    /**
     * Etsitään haluttu työn kohde kuvauksen perusteella.
     */
    public static function findWithKuvaus() {
        $params = $_POST;
        $searchedDescription = $params['kuvaus'];
        $workObject = WorkObject::findWithDescription($searchedDescription);
        if ($workObject == null) {
            Redirect::to('/tyonKohteet', array('message' => 'Ei hakutuloksia!'));
        } else {
            $id = $workObject->id;
            Redirect::to('/tyonKohde/' . $id, array('message' => 'Löytyi!'));
        }
    }

}
