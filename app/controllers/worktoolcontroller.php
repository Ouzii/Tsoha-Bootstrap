<?php

class WorkToolController extends BaseController{
  public static function index(){
    $tyokalut = WorkTool::all();
    View::make('tyokalu/tyokalut.html', array('tyokalut' => $tyokalut));
  }
  
  public static function show($kuvaus) {
      $tyokalu = WorkTool::find($kuvaus);
      View::make('tyokalu/tyokaluKuvaus.html', array('tyokalu' => $tyokalu));
  }
  
  public static function create() {
      View::make('tyokalu/uusiTyokalu.html');
  }
  
  public static function store() {
        $params = $_POST;
        $tyokalu = new WorkTool(array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
        ));

//        Kint::dump($params);

        $tyokalu->save();
        Redirect::to('/tyokalu/' . $tyokalu->kuvaus, array('message' => 'Työkalu luotu!'));
  }
    
}
