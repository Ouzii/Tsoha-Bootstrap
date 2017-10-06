<?php
/**
 * Malli, joka mallintaa työtä.
 */
class Work extends BaseModel {

    public $id, $kohde, $tyokalu, $kuvaus, $tarkempi_kuvaus, $tehty, $suoritusaika, $tekijat;

     /**
      * Heti konstruktorissa tarkistetaan, että onko työkalu ja -kohde tallennettuna id:n arvoina 
      * ja haetaan niiden kuvaukset.
      */
    public function __construct($attributes) {
        parent::__construct($attributes);
        if (is_numeric($this->tyokalu)) {
            $this->getToolDescription();
        }

        if (is_numeric($this->kohde)) {
            $this->getObjectDescription();
        }
        $this->validators = array('validate_kuvaus', 'validate_tarkempi_kuvaus', 'validate_tekijat');
    }

     /**
      * Haetaan kaikki työt id:n mukaan järjestettynä ja palautetaan ne listana.
      */
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Tyo ORDER BY id ASC');
        $query->execute();
        $rows = $query->fetchAll();
        $works = array();

        foreach ($rows as $row) {
            $works[] = new Work(array(
                'id' => $row['id'],
                'kohde' => $row['kohde'],
                'tyokalu' => $row['tyokalu'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'tehty' => $row['tehty'],
                'suoritusaika' => (String) substr($row['suoritusaika'], 0, 19)
            ));
        }
        return $works;
    }

     /**
      * Etsitään haluttu työ tietokannasta ja palautetaan se oliona.
      */
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $work = new Work(array(
                'id' => $row['id'],
                'kohde' => $row['kohde'],
                'tyokalu' => $row['tyokalu'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'tehty' => $row['tehty'],
                'suoritusaika' => (String) substr($row['suoritusaika'], 0, 19)
            ));
            return $work;
        }

        return null;
    }

     /**
      * Etsitään työhön liittyvälle työkalulle kuvaus, joka tallennetaan työkalu-muuttujaan.
      */
    public function getToolDescription() {
        $query = DB::connection()->prepare('SELECT kuvaus FROM Tyokalu WHERE id = :id');
        $query->execute(array('id' => $this->tyokalu));
        $row = $query->fetch();

        $this->tyokalu = $row['kuvaus'];
    }

     /**
      * Etsitään työhön liittyvälle työkohteelle kuvaus, joka tallennetaan työn kohde -muuttujaan.
      */
    public function getObjectDescription() {
        $query = DB::connection()->prepare('SELECT kuvaus FROM Tyon_kohde WHERE id = :id');
        $query->execute(array('id' => $this->kohde));
        $row = $query->fetch();

        $this->kohde = $row['kuvaus'];
    }

     /**
      * Etsitään haluttu työ kuvauksella ja palautetaan se oliona.
      */
    public static function findWithDescription($kuvaus) {
        $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE kuvaus = :kuvaus LIMIT 1');
        $query->execute(array('kuvaus' => $kuvaus));
        $row = $query->fetch();

        if ($row) {
            $work = new Work(array(
                'id' => $row['id'],
                'kohde' => $row['kohde'],
                'tyokalu' => $row['tyokalu'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'tehty' => $row['tehty'],
                'suoritusaika' => $row['suoritusaika'],
            ));

            return $work;
        }

        return null;
    }

     /**
      * Tallennetaan olion tiedot tietokantaan. 
      * Ennen SQL-lauseen suoritusta, haetaan työllä olevien työkalun ja -kohteen id:t.
      */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Tyo (kohde, tyokalu, kuvaus, tarkempi_kuvaus) VALUES (:kohde, :tyokalu, :kuvaus, :tarkempi_kuvaus) RETURNING id');
        $objectId = WorkObject::findWithDescription($this->kohde)->id;
        $toolId = WorkTool::findWithDescription($this->tyokalu)->id;
        $query->execute(array('kohde' => $objectId, 'tyokalu' => $toolId, 'kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus));
        $row = $query->fetch();
        $this->id = $row['id'];
        UsersWorks::saveUsers($this->id, $this->tekijat);
    }

     /**
      * Päivitetään olion tiedot tietokantaan. 
      * Ennen SQL-lauseen suoritusta, haetaan työllä olevien työkalun ja -kohteen id:t. 
      * Työn tehty-attribuuttia muokataan erikseen, jotta tyhjä syöte ei tuottaisi virheitä. 
      * Lopuksi poistetaan vanhat merkinnät KäyttäjänTyöt -tietokohteesta ja lisätään päivitetyt tiedot tietokantaan.
      */
    public function update() {
        $query = DB::connection()->prepare('UPDATE Tyo SET kohde = :kohde, tyokalu = :tyokalu, kuvaus = :kuvaus, tarkempi_kuvaus = :tarkempi_kuvaus WHERE id = :id');
        $objectId = WorkObject::findWithDescription($this->kohde)->id;
        $toolId = WorkTool::findWithDescription($this->tyokalu)->id;
        $query->execute(array('kohde' => $objectId, 'tyokalu' => $toolId, 'kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus, 'id' => $this->id));

        if ($this->tehty == TRUE) {
            $query = DB::connection()->prepare('UPDATE Tyo SET tehty = TRUE, suoritusaika = now() WHERE id = :id');
            $query->execute(array('id' => $this->id));
        } elseif ($this->tehty == FALSE) {
            $query = DB::connection()->prepare('UPDATE Tyo SET tehty = FALSE, suoritusaika = null WHERE id = :id');
            $query->execute(array('id' => $this->id));
        }

        $query = DB::connection()->prepare('DELETE FROM KayttajanTyot WHERE tyo = :id');
        $query->execute(array('id' => $this->id));
        UsersWorks::saveUsers($this->id, $this->tekijat);
    }

     /**
      * Poistetaan ensin työ KäyttäjänTyöt -tietokohteesta liitosvirheiden välttämiseksi. 
      * Sitten poistetaan olion tiedot tietokannasta.
      */
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM KayttajanTyot WHERE tyo = :id');
        $query->execute(array('id' => $this->id));
        
        $query = DB::connection()->prepare('DELETE FROM Tyo WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }
    
    public function Done() {
        $query = DB::connection()->prepare('UPDATE Tyo SET tehty = TRUE, suoritusaika = now() WHERE id = :id');
        $query->execute(array('id' => $this->id));
        
    }

     /**
      * Tarkastetaan, että työn kuvaus on sallittu.
      */
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

     /**
      * Tarkastetaan, että työn tarkempi kuvaus on sallittu.
      */
    public function validate_tarkempi_kuvaus() {
        $errors = array();
        if (strlen($this->tarkempi_kuvaus) > 360) {
            $errors[] = 'Työn tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }

     /**
      * Tarkastetaan, että työllä varmasti on vähintään yksi tekijä.
      */
    public function validate_tekijat() {
        $errors = array();
        if (count($this->tekijat) == 0) {
            $errors[] = 'Työllä täytyy olla tekijä!';
        }
        return $errors;
    }

}
