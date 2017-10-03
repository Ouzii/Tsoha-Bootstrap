<?php
/*
 * Kontrolleri, joka hoitaa työkaluihin liittyvät toiminnallisuudet.
 */
class WorkToolController extends BaseController {

     /*
      * Haetaan kaikki työkalut ja luodaan niistä näkymä.
      */
    public static function index() {
        $workTools = WorkTool::all();
        View::make('tyokalu/tyokalut.html', array('tyokalut' => $workTools));
    }

     /*
      * Haetaan tietty työkalu ja luodaan siitä näkymä.
      */
    public static function show($id) {
        $workTool = WorkTool::find($id);
        View::make('tyokalu/tyokaluKuvaus.html', array('tyokalu' => $workTool));
    }

     /*
      * Haetaan tietty työkalu kuvauksen perusteella ja luodaan siitä näkymä.
      */
//    public static function showKuvaus($kuvaus) {
//        $tyokalu = WorkTool::findKuvaus($kuvaus);
//
//        if ($tyokalu == null) {
//            
//        } else {
//            $id = $tyokalu->id;
//            Redirect::to('/tyokalu/' . $id);
//        }
//    }

     /*
      * Luodaan näkymä uuden työkalun luomiselle.
      */
    public static function create() {
        View::make('tyokalu/uusiTyokalu.html');
    }

     /*
      * Luodaan uusi näkymä työkalun luomiselle vanhoilla arvoilla ja virhetiedotteella.
      */
    public static function createErrors($errors, $attributes) {
        View::make('tyokalu/uusiTyokalu.html', array('errors' => $errors, 'attributes' => $attributes));
    }

     /*
      * Tarkastetaan käyttäjän antamat tiedot sallituiksi 
      * ja kutsutaan worktool-mallia tallentamaan tiedot tietokantaan.
      */
    public static function store() {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
        );
        $workTool = new WorkTool($attributes);
        $errors = $workTool->errors();

        if (count($errors) == 0) {
            $workTool->save();
            Redirect::to('/tyokalu/' . $workTool->id, array('message' => 'Työkalu luotu!'));
        } else {
            WorkToolController::createErrors($errors, $attributes);
        }
    }

     /*
      * Tarkastetaan käyttäjän antamat tiedot sallituiksi 
      * ja kutsutaan worktool-mallia päivittämään tiedot tietokantaan.
      */
    public static function update($id) {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
            'id' => $id
        );

        $workTool = new WorkTool($attributes);
        $errors = $workTool->errors();

        if (count($errors) == 0) {
            $workTool->update();
            Redirect::to('/tyokalu/' . $id, array('message' => 'Työkalua muokattu!'));
        } else {
            WorkToolController::editErrors($errors, $attributes);
        }
    }

     /*
      * Etsitään haluttu työkalu ja luodaan sille muokkausnäkymä.
      */
    public static function edit($id) {

        $workTool = WorkTool::find($id);

        $attributes = array(
            'kuvaus' => $workTool->kuvaus,
            'tarkempi_kuvaus' => $workTool->tarkempi_kuvaus,
            'id' => $id
        );

        View::make('/tyokalu/tyokaluMuokkaus.html', array('attributes' => $attributes));
    }

     /*
      * Luodaan uusi muokkausnäkymä vanhoilla arvoilla ja virhetiedotteella.
      */
    public static function editErrors($errors, $attributes) {
        View::make('tyokalu/tyokaluMuokkaus.html', array('attributes' => $attributes, 'errors' => $errors));
    }

     /*
      * Tarkastetaan käyttäjän oikeus työkalujen poistoon. Jos oikeus löytyy,
      * niin etsitään työkalu ja tarkastetaan sen yhteydet olemassaoleviin töihin.
      * Jos yhteyksiä ei ole, kutsutaan worktool-mallia poistamaan tiedot tietokannasta.
      */
    public static function destroy($id) {
        $isAdmin = User::find($_SESSION['tunnus']);

        if ($isAdmin->admin) {
            $workTool = WorkTool::find($id);
            $errors = $workTool->validate_connections();

            if (count($errors) == 0) {
                $workTool->destroy();
                Redirect::to('/tyokalut', array('message' => 'Työkalu poistettu!'));
            } else {
                Redirect::to('/tyokalu/' . $id, array('errors' => $errors));
            }
        } else {
            $errors[] = 'Sinun täytyy kirjautua admin-tunnuksilla poistaaksesi työkalun!';
            Redirect::to('/tyokalu/' . $id, array('errors' => $errors));
        }
    }

     /*
      * Etsitään haluttu työkalu annetun kuvauksen perusteella.
      */
    public static function findWithKuvaus() {
        $params = $_POST;
        $searchedDescription = $params['kuvaus'];
        $workTool = WorkTool::findWithDescription($searchedDescription);
        if ($workTool == null) {
            Redirect::to('/tyokalut', array('message' => 'Ei hakutuloksia!'));
        } else {
            $id = $workTool->id;
            Redirect::to('/tyokalu/' . $id, array('message' => 'Löytyi!'));
        }
    }

}
