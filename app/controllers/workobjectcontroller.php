<?php

class WorkObjectController extends BaseController{
  public static function index(){
    $tyonKohteet = WorkObject::all();
    View::make('tyonKohde/tyonKohteet.html', array('tyonKohteet' => $tyonKohteet));
  }
  
  public static function show($kuvaus) {
      $tyonKohde = WorkTool::find($kuvaus);
      View::make('tyonKohde/tyonKohdeKuvaus.html', array('tyonKohde' => $tyonKohde));
  }
    
}
