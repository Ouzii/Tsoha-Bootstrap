<?php

class WorkObjectController extends BaseController {

    public static function index() {
        $tyonKohteet = WorkObject::all();
        View::make('tyonKohde/tyonKohteet.html', array('tyonKohteet' => $tyonKohteet));
    }

    public static function show($kuvaus) {
        $tyonKohde = WorkObject::find($kuvaus);
        View::make('tyonKohde/tyonKohdeKuvaus.html', array('tyonKohde' => $tyonKohde));
    }

    public static function create() {
        View::make('tyonKohde/uusiTyonKohde.html');
    }
    
    public static function createErrors($errors, $attributes) {
        View::make('tyonKohde/uusiTyonKohde.html', array('errors' => $errors, 'attributes' => $attributes));
    }

    public static function store() {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
        );
        $tyonKohde = new WorkObject($attributes);
//        Kint::dump($params);
        $errors = $tyonKohde->errors();

        if (count($errors) == 0) {
            $tyonKohde->save();
            Redirect::to('/tyonKohde/' . $tyonKohde->kuvaus, array('message' => 'Työn kohde luotu!'));
        } else {
            WorkObjectController::createErrors($errors, $attributes);
        }
    }

    public static function findWithKuvaus() {
        $params = $_POST;
        $etsittyKuvaus = $params['kuvaus'];
        $tyonKohde = WorkObject::find($etsittyKuvaus);
        if ($tyonKohde == null) {
            Redirect::to('/tyonKohteet', array('message' => 'Ei hakutuloksia!'));
        } else {
            $kuvaus = $tyonKohde[0]->kuvaus;
            Redirect::to('/tyonKohde/' . $kuvaus, array('message' => 'Löytyi!'));
        }
    }

}
