<?php

class Work extends BaseModel {

    public $id, $kohde, $tyokalu, $kuvaus, $tarkempi_kuvaus, $tehty, $suoritusaika, $tekija1, $tekija2, $tekija3, $tekija4, $tekija5;

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

    public static function getUsers($id) {
        $query = DB::connection()->prepare('SELECT tekija FROM KayttajanTyot WHERE tyo = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $tekijat = array();

        foreach ($rows as $row) {
            $tekijat[] = new User(array(
                'tunnus' => $row['tekija'],
            ));
        }
        return $tekijat;
    }

    public function save() {
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
        $query = DB::connection()->prepare('INSERT INTO Tyo (kohde, tyokalu, kuvaus, tarkempi_kuvaus) VALUES (:kohde, :tyokalu, :kuvaus, :tarkempi_kuvaus) RETURNING id');
        // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
        $query->execute(array('kohde' => $this->kohde, 'tyokalu' => $this->tyokalu, 'kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus));
        // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
//        // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
        $this->id = $row['id'];
        $query = DB::connection()->prepare('INSERT INTO KayttajanTyot (tekija, tyo) VALUES (:tekija1, :id)');

        $query->execute(array('id' => $this->id, 'tekija1' => $this->tekija1));
        if ($this->tekija2 != null) {
            $query = DB::connection()->prepare('INSERT INTO KayttajanTyot (tekija, tyo) VALUES (:tekija2, :id)');
            $query->execute(array('id' => $this->id, 'tekija2' => $this->tekija2));

            if ($this->tekija3 != null) {
                $query = DB::connection()->prepare('INSERT INTO KayttajanTyot (tekija3, tyo) VALUES (:tekija3, :id)');
                $query->execute(array('id' => $this->id, 'tekija3' => $this->tekija3));

                if ($this->tekija4 != null) {
                    $query = DB::connection()->prepare('INSERT INTO KayttajanTyot (tekija4, tyo) VALUES (:tekija4, :id)');
                    $query->execute(array('id' => $this->id, 'tekija4' => $this->tekija4));

                    if ($this->tekija5 != null) {
                        $query = DB::connection()->prepare('INSERT INTO KayttajanTyot (tekija5, tyo) VALUES (:tekija5, :id)');
                        $query->execute(array('id' => $this->id, 'tekija4' => $this->tekija5));
                    }
                }
            }
        }
    }

}
