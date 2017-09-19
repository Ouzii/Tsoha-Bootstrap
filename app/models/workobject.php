<?php

class WorkObject extends BaseModel {

    public $kuvaus, $tarkempi_kuvaus, $luotu;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
// Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde');
// Suoritetaan kysely
        $query->execute();
// Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $kohteet = array();

// Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
// Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $kohteet[] = new WorkObject(array(
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));
        }

        return $kohteet;
    }

    public static function find($kuvaus) {
        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde WHERE kuvaus = :kuvaus LIMIT 1');
        $query->execute(array('kuvaus' => $kuvaus));
        $row = $query->fetch();

        if ($row) {
            $kohde[] = new WorkObject(array(
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));

            return $kohde;
        }

        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Tyon_kohde (kuvaus, tarkempi_kuvaus) VALUES (:kuvaus, :tarkempi_kuvaus)');
        $query->execute(array('kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus));
        
//        Kint::trace();
//        Kint::dump($row);

    }

}
