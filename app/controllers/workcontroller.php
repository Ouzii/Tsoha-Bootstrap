<?php
/*
 * Kontrolleri, joka hoitaa töihin liittyvät toiminnallisuudet.
 */
class WorkController extends BaseController {

     /*
      * Hae kaikki työt tietokannasta ja luo niistä näkymä.
      */
    public static function index() {
        $works = Work::all();
        View::make('tyo/tyot.html', array('tyot' => $works));
    }

     /*
      * Etsi haluttu työ ja siihen liittyvät tekijät tietokannasta ja luo näistä näkymä.
      */
    public static function show($id) {
        $work = Work::find($id);
        $worksUsers = UsersWorks::getUsersForWork($id);
        View::make('tyo/tyoKuvaus.html', array('tyo' => $work, 'tekijat' => $worksUsers));
    }

     /*
      * Hae kaikki vaihtoehdot (aakkosjärjestyksessä) työn luomiselle ja tee näistä näkymä.
      */
    public static function create() {
        $objects = WorkObject::allAlphabetical();
        $tools = WorkTool::allAlphabetical();
        $users = User::allAlphabetical();
        View::make('tyo/uusiTyo.html', array('kohteet' => $objects, 'tyokalut' => $tools, 'kayttajat' => $users));
    }

     /*
      * Metodi, jota kutsutaan kun käyttäjän antamissa tiedoissa on virheitä työn luomisessa.
      * Luodaan uusi työn luomisnäkymä vanhoilla arvoilla ja virhetiedotteella.
      */
    public static function createErrors($errors, $attributes) {
        $objects = WorkObject::allAlphabetical();
        $tools = WorkTool::allAlphabetical();
        $users = User::allAlphabetical();
        View::make('tyo/uusiTyo.html', array('kohteet' => $objects, 'tyokalut' => $tools, 'kayttajat' => $users, 'errors' => $errors, 'attributes' => $attributes));
    }

     /*
      * Tarkistetaan käyttäjän antamat tiedot uudelle työlle oikeiksi 
      * ja kutsutaan work-mallia tallentamaan tiedot tietokantaan.
      */
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

        $work = new Work($attributes);
        $errors = $work->errors();

        if (count($errors) == 0) {
            $work->save();
            Redirect::to('/tyo/' . $work->id, array('message' => 'Työ luotu!'));
        } else {
            WorkController::createErrors($errors, $attributes);
        }
    }

     /*
      * Etsitään työtä annetulla kuvauksella.
      */
    public static function findWithKuvaus() {
        $params = $_POST;
        $description = $params['kuvaus'];
        $work = Work::findWithDescription($description);
        if ($work == null) {
            Redirect::to('/tyot', array('message' => 'Ei hakutuloksia!'));
        } else {
            $id = $work->id;
            Redirect::to('/tyo/' . $id, array('message' => 'Löytyi!'));
        }
    }

     /*
      * Haetaan haluttu työ ja sen tekijät, jonka jälkeen luodaan lista työn tiedoista.
      * Lopulta haetaan kaikki vaihtoehdot työn muokkaamiseksi ja luodaan työn muokkaus -näkymä.
      */
    public static function edit($id) {
        $work = Work::find($id);
        $worksUsers = UsersWorks::getUsersForWork($id);
        $worksUsersUsernames = array();

        foreach ($worksUsers as $user) {
            $worksUsersUsernames[] = $user->tunnus;
        }
        
        $attributes = array(
            'id' => $work->id,
            'kohde' => $work->kohde,
            'tyokalu' => $work->tyokalu,
            'kuvaus' => $work->kuvaus,
            'tarkempi_kuvaus' => $work->tarkempi_kuvaus,
            'tehty' => $work->tehty,
            'suoritusaika' => $work->suoritusaika,
            'tekijat' => $worksUsersUsernames
        );

        $objects = WorkObject::allAlphabetical();
        $tools = WorkTool::allAlphabetical();
        $worksUsers = User::allAlphabetical();
        View::make('tyo/tyoMuokkaus.html', array('attributes' => $attributes, 'kohteet' => $objects, 'tyokalut' => $tools, 'kayttajat' => $worksUsers));
    }

     /*
      * Haetaan kaikki vaihtoehdot työn muokkaamiselle 
      * ja luodaan uusi näkymä vanhoilla tiedoilla ja virhetiedotteella.
      */
    public static function editErrors($errors, $attributes) {
        $objects = WorkObject::allAlphabetical();
        $tools = WorkTool::allAlphabetical();
        $users = User::allAlphabetical();
        View::make('tyo/tyoMuokkaus.html', array('attributes' => $attributes, 'kohteet' => $objects, 'tyokalut' => $tools, 'kayttajat' => $users, 'errors' => $errors));
    }

     /*
      * Ensin tarkistetaan onko käyttäjä asettanut työlle tekijöitä, ja sen mukaan luodaan attribuutin tallentava taulkko.
      * Tämä osa on vain virheiden välttämiseksi, sillä jos kutsuu $params['tekijat'] ilman, että tekijöitä on asetettu,
      * niin syntaksi ei toimi. Tässä vaiheessa ei periaatteessa vielä pidetä virheenä, jos tekijöitä ei ole.
      * Tämän jälkeen tarkastetaan "tehty" kohta parametreista, ja muokataan oliota sen mukaan. Lopuksi ajetaan työn 
      * validointimetodit ja tuloksen perusteella pyydetään work-mallia päivittämään tietokantaa tai antamaan virheilmoituksia.
      */
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

        $work = new Work($attributes);
        if (isset($params['tehty'])) {
            $attributes['tehty'] = 1;
            $work->tehty = true;
        } else {
            $attributes['tehty'] = 0;
            $work->tehty = false;
        }
        $errors = $work->errors();

        $work->id = $id;
        if (count($errors) == 0) {
            $work->update();
            Redirect::to('/tyo/' . $work->id, array('message' => 'Työ muokattu!'));
        } else {
            WorkController::editErrors($errors, $attributes);
        }
    }

     /*
      * Tarkastetaan onko käyttäjällä oikeutta poistaa työtä ja jos on,
      * niin pyydetään work-mallia poistamaan työ tietokannasta. Muuten annetaan virheilmoitus.
      * Näkymissä on kyllä poistettu nappi työn poistamiselle novice-käyttäjiltä, mutta ilman tarkastamista
      * voisi novice-käyttäjä kirjoittaa tarvittavan osoitteen, joka poistaisi työn.
      */
    public static function destroy($id) {
        $isAdmin = User::find($_SESSION['username']);

        if ($isAdmin->admin) {
            $work = new Work(array('id' => $id));
            $work->destroy();

            Redirect::to('/tyot', array('message' => 'Työ on poistettu!'));
        } else {
            Redirect::to('/tyot', array('message' => 'Sinun täytyy kirjautua admin-tunnuksilla poistaaksesi työn!'));
        }
    }
    
    public static function markAsDone($id) {
        $work = Work::find($id);
        
        $work->tehty = true;
        $work->Done();
        
        Redirect::to('/tyo/' . $id, array('message' => 'Työ merkattu tehdyksi!'));
    }

}
