<?php

class Work extends BaseModel {

    public $id, $kohde, $tyokalu, $kuvaus, $tarkempi_kuvaus, $tehty, $suoritusaika, $tekijat;

    public function __construct($attributes) {
        parent::__construct($attributes);
        if (is_numeric($this->tyokalu)) {
            $this->getKuvauksetTyokalu();
        }

        if (is_numeric($this->kohde)) {
            $this->getKuvauksetTyonKohde();
        }
        $this->validators = array('validate_kuvaus', 'validate_tarkempi_kuvaus', 'validate_tekijat');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Tyo ORDER BY id ASC');
        $query->execute();
        $rows = $query->fetchAll();
        $tyot = array();

        foreach ($rows as $row) {
            $tyot[] = new Work(array(
                'id' => $row['id'],
                'kohde' => $row['kohde'],
                'tyokalu' => $row['tyokalu'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'tehty' => $row['tehty'],
                'suoritusaika' => (String) substr($row['suoritusaika'], 0, 19)
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
                'suoritusaika' => (String) substr($row['suoritusaika'], 0, 19)
            ));
            return $tyo;
        }

        return null;
    }

    public function getKuvauksetTyokalu() {
        $query = DB::connection()->prepare('SELECT kuvaus FROM Tyokalu WHERE id = :id');
        $query->execute(array('id' => $this->tyokalu));
        $row = $query->fetch();

        $this->tyokalu = $row['kuvaus'];
    }

    public function getKuvauksetTyonKohde() {
        $query = DB::connection()->prepare('SELECT kuvaus FROM Tyon_kohde WHERE id = :id');
        $query->execute(array('id' => $this->kohde));
        $row = $query->fetch();

        $this->kohde = $row['kuvaus'];
    }

    public static function findWithKuvaus($kuvaus) {
        $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE kuvaus = :kuvaus LIMIT 1');
        $query->execute(array('kuvaus' => $kuvaus));
        $row = $query->fetch();

        if ($row) {
            $tyo = new Work(array(
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
        $query = DB::connection()->prepare('INSERT INTO Tyo (kohde, tyokalu, kuvaus, tarkempi_kuvaus) VALUES (:kohde, :tyokalu, :kuvaus, :tarkempi_kuvaus) RETURNING id');
        $kohdeID = WorkObject::findKuvaus($this->kohde)[0]->id;
        $tyokaluID = WorkTool::findKuvaus($this->tyokalu)[0]->id;
        $query->execute(array('kohde' => $kohdeID, 'tyokalu' => $tyokaluID, 'kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus));
        $row = $query->fetch();
        $this->id = $row['id'];
        $this->saveUsers();
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Tyo SET kohde = :kohde, tyokalu = :tyokalu, kuvaus = :kuvaus, tarkempi_kuvaus = :tarkempi_kuvaus WHERE id = :id');
        $kohdeID = WorkObject::findKuvaus($this->kohde)[0]->id;

        $tyokaluID = WorkTool::findKuvaus($this->tyokalu)[0]->id;
        $query->execute(array('kohde' => $kohdeID, 'tyokalu' => $tyokaluID, 'kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus, 'id' => $this->id));

        if ($this->tehty == TRUE) {
            $query = DB::connection()->prepare('UPDATE Tyo SET tehty = TRUE, suoritusaika = now() WHERE id = :id');
            $query->execute(array('id' => $this->id));
        } elseif ($this->tehty == FALSE) {
            $query = DB::connection()->prepare('UPDATE Tyo SET tehty = FALSE, suoritusaika = null WHERE id = :id');
            $query->execute(array('id' => $this->id));
        }

        $query = DB::connection()->prepare('DELETE FROM KayttajanTyot WHERE tyo = :id');
        $query->execute(array('id' => $this->id));
        $this->saveUsers();
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM KayttajanTyot WHERE tyo = :id');
        $query->execute(array('id' => $this->id));
        
        $query = DB::connection()->prepare('DELETE FROM Tyo WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    public function saveUsers() {
        foreach ($this->tekijat as $tekija) {
            $query = DB::connection()->prepare('INSERT INTO KayttajanTyot (tekija, tyo) VALUES (:tekija, :id)');
            $query->execute(array('id' => $this->id, 'tekija' => $tekija));
        }
    }

    public function validate_kuvaus() {
        $errors = array();
        if ($this->kuvaus == '' || $this->kuvaus == null) {
            $errors[] = 'Työ vaatii kuvauksen!';
        }
        if (strlen($this->kuvaus) > 30) {
            $errors[] = 'Työn kuvaus saa olla enintään 30 merkkiä pitkä';
        }

        return $errors;
    }

    public function validate_tarkempi_kuvaus() {
        $errors = array();
        if (strlen($this->tarkempi_kuvaus) > 360) {
            $errors[] = 'Työn tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }

    public function validate_tekijat() {
        $errors = array();
        if (count($this->tekijat) == 0) {
            $errors[] = 'Työllä täytyy olla tekijä!';
        }
        return $errors;
    }

}
