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

    public static function edit($id) {
        $tyo = Work::find($id);

        $tekijat = Work::getUsers($id);
        $tyonTekijat = array();
        
        foreach ($tekijat as $tekija) {
            $tyonTekijat[] = $tekija->tunnus;
        }
        
        
        $attributes = array(
            'id' => $tyo[0]->id,
            'kohde' => $tyo[0]->kohde,
            'tyokalu' => $tyo[0]->tyokalu,
            'kuvaus' => $tyo[0]->kuvaus,
            'tarkempi_kuvaus' => $tyo[0]->tarkempi_kuvaus,
            'tehty' => $tyo[0]->tehty,
            'suoritusaika' => $tyo[0]->suoritusaika,
            'tekijat' => $tyonTekijat
        );


        $kohteet = WorkObject::allAlphabetical();
        $tyokalut = WorkTool::allAlphabetical();
        $kayttajat = User::allAlphabetical();
        View::make('tyo/tyoMuokkaus.html', array('attributes' => $attributes, 'kohteet' => $kohteet, 'tyokalut' => $tyokalut, 'kayttajat' => $kayttajat));
    }

    public static function editErrors($errors, $attributes) {
        $kohteet = WorkObject::allAlphabetical();
        $tyokalut = WorkTool::allAlphabetical();
        $kayttajat = User::allAlphabetical();
        View::make('tyo/tyoMuokkaus.html', array('attributes' => $attributes, 'kohteet' => $kohteet, 'tyokalut' => $tyokalut, 'kayttajat' => $kayttajat, 'errors' => $errors));
    }

    // Pelin muokkaaminen (lomakkeen käsittely)
    public static function update($id) {
        $params = $_POST;



        If (isset($params['tekijat'])) {
            $attributes = array(
                'id' => $id,
                'kohde' => $params['kohde'],
                'tyokalu' => $params['tyokalu'],
                'kuvaus' => $params['kuvaus'],
                'tarkempi_kuvaus' => $params['tarkempi_kuvaus'],
                'tekijat' => $params['tekijat']
            );
        } else {
            $attributes = array(
                'id' => $id,
                'kohde' => $params['kohde'],
                'tyokalu' => $params['tyokalu'],
                'kuvaus' => $params['kuvaus'],
                'tarkempi_kuvaus' => $params['tarkempi_kuvaus']
            );
        }

        $tyo = new Work($attributes);
        if (isset($params['tehty'])) {
            $attributes['tehty'] = 1;
            $tyo->tehty = true;
        } else {
            $attributes['tehty'] = 0;
            $tyo->tehty = false;
        }
        $errors = $tyo->errors();

        $tyo->id = $id;
        if (count($errors) == 0) {
            $tyo->update();
            Redirect::to('/tyo/' . $tyo->id, array('message' => 'Työ muokattu!'));
        } else {
            WorkController::editErrors($errors, $attributes);
        }
    }

    public static function destroy($id) {
        $tyo = new Work(array('id' => $id));
        $tyo->destroy();

        Redirect::to('/tyot', array('message' => 'Työ on poistettu!'));
    }

}
