<?php
/**
 * Malli, joka mallintaa työn kohdetta.
 */
class WorkObject extends BaseModel {

    public $id, $description, $longer_description, $created;

     /**
      * Konstruktorissa muokataan luontipäivämäärän muotoa.
      */
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->created = substr($this->created, 0, 19);
        $this->validators = array('validate_description', 'validate_longer_description');
    }

     /**
      * Haetaan oliolle oikea id kuvauksen perusteella.
      */
//    public function getId() {
//        $query = DB::connection()->prepare('SELECT id FROM Tyon_kohde WHERE kuvaus = :kuvaus');
//        $query->execute(array('kuvaus' => $this->kuvaus));
//
//        $row = $query->fetch();
//        $this->id = $row['id'];
//    }

//     /**
//      * Haetaan kaikki työn kohteet ja palautetaan ne listana.
//      */
//    public static function all() {
//        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde');
//        $query->execute();
//        $rows = $query->fetchAll();
//        $workObjects = array();
//
//        foreach ($rows as $row) {
//            $workObjects[] = new WorkObject(array(
//                'id' => $row['id'],
//                'kuvaus' => $row['kuvaus'],
//                'longer_description' => $row['longer_description'],
//                'luotu' => $row['luotu'],
//            ));
//        }
//
//        return $workObjects;
//    }

     /**
      * Haetaan kaikki työn kohteet aakkosjärjestyksessä ja palautetaan ne listana.
      */
    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM WorkObject ORDER BY description');
        $query->execute();
        $rows = $query->fetchAll();
        $workObjects = array();

        foreach ($rows as $row) {
            $workObjects[] = new WorkObject(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'created' => $row['created'],
            ));
        }

        return $workObjects;
    }

     /**
      * Haetaan haluttu työn kohde ja palautetaan se oliona.
      */
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM WorkObject WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $workObject = new WorkObject(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'created' => $row['created'],
            ));

            return $workObject;
        }

        return null;
    }

     /**
      * Haetaan haluttu työn kohde kuvauksen perusteella ja palautetaan se oliona.
      */
    public static function findWithDescription($description) {
        $query = DB::connection()->prepare('SELECT * FROM WorkObject WHERE description = :description LIMIT 1');
        $query->execute(array('description' => $description));
        $row = $query->fetch();

        if ($row) {
            $workObject = new WorkObject(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'created' => $row['created'],
            ));

            return $workObject;
        }

        return null;
    }

     /**
      * Tallennetaan olion tiedot tietokantaan.
      */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO WorkObject (description, longer_description) VALUES (:description, :longer_description) RETURNING id');
        $query->execute(array('description' => $this->description, 'longer_description' => $this->longer_description));
        $this->id = $query->fetch()['id'];
    }

     /**
      * Päivitetään olion tiedot tietokantaan.
      */
    public function update() {
        $query = DB::connection()->prepare('UPDATE WorkObject SET description = :description, longer_description = :longer_description WHERE id = :id');
        $query->execute(array('description' => $this->description, 'longer_description' => $this->longer_description, 'id' => $this->id));
    }

     /**
      * Poistetaan olion tiedot tietokannasta.
      */
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM WorkObject WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

     /**
      * Tarkastetaan, että työn kohteen kuvaus on sallittu.
      */
    public function validate_description() {
        $errors = array();
        if ($this->description == '' || $this->description == null) {
            $errors[] = 'Työn kohde vaatii kuvauksen!';
        }
        if (strlen($this->description) > 30) {
            $errors[] = 'Työkohteen kuvaus saa olla enintään 30 merkkiä pitkä';
        }

        return $errors;
    }
    
     /**
      * Tarkastetaan, että työn kohteen tarkempi kuvaus on sallittu.
      */
    public function validate_longer_description() {
        $errors = array();
        if (strlen($this->longer_description) > 360) {
            $errors[] = 'Työkohteen tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }

     /**
      * Tarkastetaan työn kohteen yhteydet olemassaoleviin töihin.
      */
    public function validate_connections() {
        $errors = array();

        $query = DB::connection()->prepare('SELECT * FROM Work WHERE object = :id');
        $query->execute(array('id' => $this->id));

        $rows = $query->fetchAll();

        if (count($rows) > 0) {
            $errors[] = $this->description . ' liittyy ' . count($rows) . ' työhön!';
        }

        return $errors;
    }

}
