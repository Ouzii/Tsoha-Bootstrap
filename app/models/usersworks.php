<?php

class UsersWorks extends BaseModel {

    public $tekija, $tyo;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
// Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM KayttajanTyot');
// Suoritetaan kysely
        $query->execute();
// Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $kayttajienTyot = array();

// Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
// Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $kayttajienTyot[] = new UsersWorks(array(
                'tekija' => $row['tekija'],
                'tyo' => $row['tyo'],
            ));
        }

        return $kayttajienTyot;
    }

    public static function find($tekija) {
        $query = DB::connection()->prepare('SELECT * FROM KayttajanTyot WHERE tekija = :tekija');
        $query->execute(array('tekija' => $tekija));
        $rows = $query->fetchAll();
        $kayttajanTyot = array();
        
        foreach ($rows as $row) {
            $kayttajanTyot[] = new UsersWorks(array(
                'tekija' => $row['tekija'],
                'tyo' => $row['tyo'],
            ));
        }
        return $kayttajanTyot;
    }

}
