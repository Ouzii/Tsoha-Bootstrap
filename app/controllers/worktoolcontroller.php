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
    
}
