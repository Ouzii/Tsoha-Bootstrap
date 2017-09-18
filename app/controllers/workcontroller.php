<?php

class WorkController extends BaseController{
  public static function index(){
    $tyot = Work::all();
    View::make('tyo/tyot.html', array('tyot' => $tyot));
  }
  
  public static function show($id) {
      $tyo = Work::find($id);
      $tekijat = Work::getUsers($id);
      View::make('tyo/tyoKuvaus.html', array('tyo' => $tyo, 'tekijat' => $tekijat));
  }
  
  public static function create() {
      View::make('tyo/uusiTyo.html');
  }
  
  
    public static function store() {
// POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
        $params = $_POST;
// Alustetaan uusi Game-luokan olion käyttäjän syöttämillä arvoilla
        $tyo = new Work(array(
            'kuvaus' => $params['kuvaus'],
            'kohde' => $params['kohde'],
            'tyokalu' => $params['tyokalu'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus']
        ));

//        Kint::dump($params);
// Kutsutaan alustamamme olion save metodia, joka tallentaa olion tietokantaan
        $tyo->save();

// Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle
        Redirect::to('/tyo/' . $tyo->id, array('message' => 'Työ luotu!'));
    }



    
}
