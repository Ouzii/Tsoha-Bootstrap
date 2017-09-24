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

    public static function create() {
        $kohteet = WorkObject::allAlphabetical();
        $tyokalut = WorkTool::allAlphabetical();
        $kayttajat = User::allAlphabetical();
        View::make('tyo/uusiTyo.html', array('kohteet' => $kohteet, 'tyokalut' => $tyokalut, 'kayttajat' => $kayttajat));
    }

    public static function createErrors($errors, $attributes) {
        $kohteet = WorkObject::allAlphabetical();
        $tyokalut = WorkTool::allAlphabetical();
        $kayttajat = User::allAlphabetical();
        View::make('tyo/uusiTyo.html', array('kohteet' => $kohteet, 'tyokalut' => $tyokalut, 'kayttajat' => $kayttajat, 'errors' => $errors, 'attributes' => $attributes));
    }

    public static function store() {
        $params = $_POST;


//        $dummy = array_search("dummy", $tekijat);
//        
//        if ($dummy !== false) {
//            unset($tekijat[$dummy]);
//        }


        If (isset($params['tekijat'])) {
            $attributes = array(
                'kohde' => $params['kohde'],
                'tyokalu' => $params['tyokalu'],
                'kuvaus' => $params['kuvaus'],
                'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
                'tekijat' => $params['tekijat']
            );
        } else {
            $attributes = array(
                'kohde' => $params['kohde'],
                'tyokalu' => $params['tyokalu'],
                'kuvaus' => $params['kuvaus'],
                'tarkempi_kuvaus' => $params['tarkempi_kuvaus']
            );
        }


        $tyo = new Work($attributes);
//        Kint::dump($params);
        $errors = $tyo->errors();

        if (count($errors) == 0) {
            $tyo->save();
            Redirect::to('/tyo/' . $tyo->id, array('message' => 'Työ luotu!'));
        } else {
            WorkController::createErrors($errors, $attributes);
        }
    }

    public static function findWithKuvaus() {
        $params = $_POST;
        $kuvaus = $params['kuvaus'];
        $tyo = Work::findWithKuvaus($kuvaus);
        if ($tyo == null) {
            Redirect::to('/tyot', array('message' => 'Ei hakutuloksia!'));
        } else {
            $id = $tyo->id;
            Redirect::to('/tyo/' . $id, array('message' => 'Löytyi!'));
        }
    }

}
