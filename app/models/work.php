<?php

class Work extends BaseModel {

    public $id, $kohde, $tyokalu, $kuvaus, $tarkempi_kuvaus, $tehty, $suoritusaika;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
// Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Tyo');
// Suoritetaan kysely
        $query->execute();
// Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $tyot = array();

// Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
// Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $tyot[] = new Work(array(
                'id' => $row['id'],
                'kohde' => $row['kohde'],
                'tyokalu' => $row['tyokalu'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'tehty' => $row['tehty'],
                'suoritusaika' => $row['suoritusaika'],
            ));
        }

        return $tyot;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $tyo[] = new Work(array(
                'id' => $row['id'],
                'kohde' => $row['kohde'],
                'tyokalu' => $row['tyokalu'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'tehty' => $row['tehty'],
                'suoritusaika' => $row['suoritusaika'],
            ));

            return $tyo;
        }

        return null;
    }

}
