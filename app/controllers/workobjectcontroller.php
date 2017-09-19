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

    public static function store() {
        $params = $_POST;
        $tyonKohde = new WorkObject(array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
        ));

//        Kint::dump($params);

        $tyonKohde->save();
        Redirect::to('/tyonKohde/' . $tyonKohde->kuvaus, array('message' => 'Työn kohde luotu!'));
    }

}
