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
    
}
