<?php

class WorkToolController extends BaseController {

    public static function index() {
        $tyokalut = WorkTool::all();
        View::make('tyokalu/tyokalut.html', array('tyokalut' => $tyokalut));
    }

    public static function show($id) {
        $tyokalu = WorkTool::find($id);
        View::make('tyokalu/tyokaluKuvaus.html', array('tyokalu' => $tyokalu));
    }

    public static function showKuvaus($kuvaus) {
        $tyokalu = WorkTool::findKuvaus($kuvaus);

        if ($tyokalu == null) {
            
        } else {
            $id = $tyokalu[0]->id;
            Redirect::to('/tyokalu/' . $id);
        }
    }

    public static function create() {
        View::make('tyokalu/uusiTyokalu.html');
    }

    public static function createErrors($errors, $attributes) {
        View::make('tyokalu/uusiTyokalu.html', array('errors' => $errors, 'attributes' => $attributes));
    }

    public static function store() {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
        );
        $tyokalu = new WorkTool($attributes);
//        Kint::dump($params);
        $errors = $tyokalu->errors();

        if (count($errors) == 0) {
            $tyokalu->save();
            Redirect::to('/tyokalu/' . $tyokalu->id, array('message' => 'Työkalu luotu!'));
        } else {
            WorkToolController::createErrors($errors, $attributes);
        }
    }

    public static function update($id) {
        $params = $_POST;

        $attributes = array(
            'kuvaus' => $params['kuvaus'],
            'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
            'id' => $id
        );

        $tyokalu = new WorkTool($attributes);

        $errors = $tyokalu->errors();

        if (count($errors) == 0) {
            $tyokalu->update();
            Redirect::to('/tyokalu/' . $id, array('message' => 'Työkalua muokattu!'));
        } else {
            WorkToolController::editErrors($errors, $attributes);
        }
    }

    public static function edit($id) {

        $tyokalu = WorkTool::find($id);

        $attributes = array(
            'kuvaus' => $tyokalu[0]->kuvaus,
            'tarkempi_kuvaus' => $tyokalu[0]->tarkempi_kuvaus,
            'id' => $id
        );

        View::make('/tyokalu/tyokaluMuokkaus.html', array('attributes' => $attributes));
    }

    public static function editErrors($errors, $attributes) {
        View::make('tyokalu/tyokaluMuokkaus.html', array('attributes' => $attributes, 'errors' => $errors));
    }

    public static function destroy($id) {
        $tyokalu = WorkTool::find($id);


        $errors = $tyokalu[0]->validate_connections();

        if (count($errors) == 0) {
            $tyokalu[0]->destroy();
            Redirect::to('/tyokalut', array('message' => 'Työkalu poistettu!'));
        } else {
            Redirect::to('/tyokalu/' . $id, array('errors' => $errors));
        }
    }

    public static function findWithKuvaus() {
        $params = $_POST;
        $etsittyKuvaus = $params['kuvaus'];
        $tyokalu = WorkTool::findKuvaus($etsittyKuvaus);
        if ($tyokalu == null) {
            Redirect::to('/tyokalut', array('message' => 'Ei hakutuloksia!'));
        } else {
            $id = $tyokalu[0]->id;
            Redirect::to('/tyokalu/' . $id, array('message' => 'Löytyi!'));
        }
    }

}
