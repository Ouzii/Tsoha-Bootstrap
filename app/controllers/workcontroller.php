<?php

class WorkController extends BaseController {

    public static function index() {
        $tyot = Work::all();
        View::make('tyo/tyot.html', array('tyot' => $tyot));
    }

    public static function show($id) {
        $tyo = Work::find($id);
        $tekijat = Work::getUsers($id);
        View::make('tyo/tyoKuvaus.html', array('tyo' => $tyo, 'tekijat' => $tekijat));
    }

    public static function showKuvaus() {
        $params = $_POST;
        $kuvaus = $params['kuvaus'];
        $tyo = Work::findKuvaus($kuvaus);
        if ($tyo == null) {
            WorkController::index();
        } else {
            $id = $tyo->id;
            Redirect::to('/tyo/' . $id, array('message' => 'Löytyi!'));
        }
    }

    public static function create() {
        $kohteet = WorkObject::allAlphabetical();
        $tyokalut = WorkTool::allAlphabetical();
        $kayttajat = User::allAlphabetical();
        View::make('tyo/uusiTyo.html', array('kohteet' => $kohteet, 'tyokalut' => $tyokalut, 'kayttajat' => $kayttajat));
    }

    public static function store() {
        $params = $_POST;
        $tyo = new Work(array(
            'kohde' => $params['kohde'],
            'tyokalu' => $params['tyokalu'],
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
            'tekijat' => $params['tekijat']
        ));

//        Kint::dump($params);
        $tyo->save();

        Redirect::to('/tyo/' . $tyo->id, array('message' => 'Työ luotu!'));
    }

}
