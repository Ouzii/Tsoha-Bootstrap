<?php

class WorkObjectController extends BaseController {

    public static function index() {
        $tyonKohteet = WorkObject::all();
        View::make('tyonKohde/tyonKohteet.html', array('tyonKohteet' => $tyonKohteet));
    }

    public static function show($id) {
        $tyonKohde = WorkObject::find($id);
        View::make('tyonKohde/tyonKohdeKuvaus.html', array('tyonKohde' => $tyonKohde[0]));
    }

    public static function showKuvaus($kuvaus) {
        $tyonKohde = WorkObject::findKuvaus($kuvaus);
//        View::make('tyonKohde/tyonKohdeKuvaus.html', array('tyonKohde' => $tyonKohde));

        if ($tyonKohde == null) {
            Redirect::to('/tyonKohteet', array('message' => 'Ei hakutuloksia!'));
        } else {
            $id = $tyonKohde[0]->id;
            Redirect::to('/tyonKohde/' . $id);
        }
    }

    public static function create() {
        View::make('tyonKohde/uusiTyonKohde.html');
    }

    public static function createErrors($errors, $attributes) {
        View::make('tyonKohde/uusiTyonKohde.html', array('errors' => $errors, 'attributes' => $attributes));
    }

    public static function update($id) {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
            'id' => $id
        );

        $tyonKohde = new WorkObject($attributes);

        $errors = $tyonKohde->errors();

        if (count($errors) == 0) {
            $tyonKohde->update();
            Redirect::to('/tyonKohde/' . $id, array('message' => 'Työn kohdetta muokattu!'));
        } else {
            WorkObjectController::editErrors($errors, $attributes);
        }
    }

    public static function edit($id) {

        $tyonKohde = WorkObject::find($id);

        $attributes = array(
            'kuvaus' => $tyonKohde[0]->kuvaus,
            'tarkempi_kuvaus' => $tyonKohde[0]->tarkempi_kuvaus,
            'id' => $id
        );

        View::make('/tyonKohde/tyonKohdeMuokkaus.html', array('attributes' => $attributes));
    }

    public static function editErrors($errors, $attributes) {
        View::make('/tyonKohde/tyonKohdeMuokkaus.html', array('attributes' => $attributes, 'errors' => $errors));
    }

    public static function destroy($id) {
        $tyonKohde = WorkObject::find($id);


        $errors = $tyonKohde[0]->validate_connections();

        if (count($errors) == 0) {
            $tyonKohde[0]->destroy();
            Redirect::to('/tyonKohteet', array('message' => 'Työn kohde poistettu!'));
        } else {
            Redirect::to('/tyonKohde/' . $id, array('errors' => $errors));
        }
    }

    public static function store() {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
        );
        $tyonKohde = new WorkObject($attributes);
        $errors = $tyonKohde->errors();

        if (count($errors) == 0) {
            $tyonKohde->save();
            Redirect::to('/tyonKohde/' . $tyonKohde->id, array('message' => 'Työn kohde luotu!'));
        } else {
            WorkObjectController::createErrors($errors, $attributes);
        }
    }

    public static function findWithKuvaus() {
        $params = $_POST;
        $etsittyKuvaus = $params['kuvaus'];
        $tyonKohde = WorkObject::findKuvaus($etsittyKuvaus);
        if ($tyonKohde == null) {
            Redirect::to('/tyonKohteet', array('message' => 'Ei hakutuloksia!'));
        } else {
            $id = $tyonKohde[0]->id;
            Redirect::to('/tyonKohde/' . $id, array('message' => 'Löytyi!'));
        }
    }

}
