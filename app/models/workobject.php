<?php
/*
 * Malli, joka mallintaa työn kohdetta.
 */
class WorkObject extends BaseModel {

    public $id, $kuvaus, $tarkempi_kuvaus, $luotu;

     /*
      * Konstruktorissa muokataan luontipäivämäärän muotoa.
      */
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->luotu = substr($this->luotu, 0, 19);
        $this->validators = array('validate_kuvaus', 'validate_tarkempi_kuvaus');
    }

     /*
      * Haetaan oliolle oikea id kuvauksen perusteella.
      */
//    public function getId() {
//        $query = DB::connection()->prepare('SELECT id FROM Tyon_kohde WHERE kuvaus = :kuvaus');
//        $query->execute(array('kuvaus' => $this->kuvaus));
//
//        $row = $query->fetch();
//        $this->id = $row['id'];
//    }

     /*
      * Haetaan kaikki työn kohteet ja palautetaan ne listana.
      */
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde');
        $query->execute();
        $rows = $query->fetchAll();
        $workObjects = array();

        foreach ($rows as $row) {
            $workObjects[] = new WorkObject(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));
        }

        return $workObjects;
    }

     /*
      * Haetaan kaikki työn kohteet aakkosjärjestyksessä ja palautetaan ne listana.
      */
    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde ORDER BY kuvaus');
        $query->execute();
        $rows = $query->fetchAll();
        $workObjects = array();

        foreach ($rows as $row) {
            $workObjects[] = new WorkObject(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));
        }

        return $workObjects;
    }

     /*
      * Haetaan haluttu työn kohde ja palautetaan se oliona.
      */
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $workObject = new WorkObject(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));

            return $workObject;
        }

        return null;
    }

     /*
      * Haetaan haluttu työn kohde kuvauksen perusteella ja palautetaan se oliona.
      */
    public static function findWithDescription($kuvaus) {
        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde WHERE kuvaus = :kuvaus LIMIT 1');
        $query->execute(array('kuvaus' => $kuvaus));
        $row = $query->fetch();

        if ($row) {
            $workObject = new WorkObject(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));

            return $workObject;
        }

        return null;
    }

     /*
      * Tallennetaan olion tiedot tietokantaan.
      */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Tyon_kohde (kuvaus, tarkempi_kuvaus) VALUES (:kuvaus, :tarkempi_kuvaus) RETURNING id');
        $query->execute(array('kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus));
        $this->id = $query->fetch()['id'];
    }

     /*
      * Päivitetään olion tiedot tietokantaan.
      */
    public function update() {
        $query = DB::connection()->prepare('UPDATE Tyon_kohde SET kuvaus = :kuvaus, tarkempi_kuvaus = :tarkempi_kuvaus WHERE id = :id');
        $query->execute(array('kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus, 'id' => $this->id));
    }

     /*
      * Poistetaan olion tiedot tietokannasta.
      */
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Tyon_kohde WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

     /*
      * Tarkastetaan, että työn kohteen kuvaus on sallittu.
      */
    public function validate_kuvaus() {
        $errors = array();
        if ($this->kuvaus == '' || $this->kuvaus == null) {
            $errors[] = 'Työn kohde vaatii kuvauksen!';
        }
        if (strlen($this->kuvaus) > 30) {
            $errors[] = 'Työkohteen kuvaus saa olla enintään 30 merkkiä pitkä';
        }

        return $errors;
    }
    
     /*
      * Tarkastetaan, että työn kohteen tarkempi kuvaus on sallittu.
      */
    public function validate_tarkempi_kuvaus() {
        $errors = array();
        if (strlen($this->tarkempi_kuvaus) > 360) {
            $errors[] = 'Työkohteen tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }

     /*
      * Tarkastetaan työn kohteen yhteydet olemassaoleviin töihin.
      */
    public function validate_connections() {
        $errors = array();

        $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE kohde = :id');
        $query->execute(array('id' => $this->id));

        $rows = $query->fetchAll();

        if (count($rows) > 0) {
            $errors[] = $this->kuvaus . ' liittyy ' . count($rows) . ' työhön!';
        }

        return $errors;
    }

}
