<?php

class UserController extends BaseController{
  public static function index(){
    $kayttajat = User::all();
    View::make('kayttaja/kayttajat.html', array('kayttajat' => $kayttajat));
  }
  
  public static function show($tunnus) {
      $kayttaja = User::find($tunnus);
      View::make('kayttaja/kayttaja.html', array('kayttaja' => $kayttaja));
  }
    
}
