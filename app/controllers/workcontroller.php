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
        $kohteet = WorkObject::all();
        $tyokalut = WorkTool::all();
        $kayttajat = User::all();
        View::make('tyo/uusiTyo.html', array('kohteet' => $kohteet, 'tyokalut' => $tyokalut, 'kayttajat' => $kayttajat));
    }

    public static function store() {
// POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
        $params = $_POST;
// Alustetaan uusi Game-luokan olion käyttäjän syöttämillä arvoilla
        $tyo = new Work(array(
            'kohde' => $params['kohde'],
            'tyokalu' => $params['tyokalu'],
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
            'tekijat' => $params['tekijat']
//            'tekija2' => $params['tekija2'],
//            'tekija3' => $params['tekija3'],
//            'tekija4' => $params['tekija4'],
//            'tekija5' => $params['tekija5']
        ));

//        Kint::dump($params);
// Kutsutaan alustamamme olion save metodia, joka tallentaa olion tietokantaan
        $tyo->save();

// Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle
        Redirect::to('/tyo/' . $tyo->id, array('message' => 'Työ luotu!'));
    }

}
