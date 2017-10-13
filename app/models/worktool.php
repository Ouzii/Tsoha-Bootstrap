<?php
/**
 * Malli, joka mallintaa työkalua.
 */
Class WorkTool extends BaseModel {

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
      * Haetaan oliolle id kuvauksella.
      */
//    public function getId() {
//        $query = DB::connection()->prepare('SELECT id FROM WorkTool WHERE description = :description');
//        $query->execute(array('description' => $this->description));
//
//        $row = $query->fetch();
//        $this->id = $row['id'];
//    }

//     /**
//      * Haetaan kaikki työkalut ja palautetaan ne listana.
//      */
//    public static function all() {
//        $query = DB::connection()->prepare('SELECT * FROM WorkTool');
//        $query->execute();
//        $rows = $query->fetchAll();
//        $workTools = array();
//
//        foreach ($rows as $row) {
//            $workTools[] = new WorkTool(array(
//                'id' => $row['id'],
//                'description' => $row['description'],
//                'longer_description' => $row['longer_description'],
//                'created' => $row['created'],
//            ));
//        }
//
//        return $workTools;
//    }

     /**
      * Haetaan kaikki työkalut aakkosjärjestyksessä ja palautetaan ne listana.
      */
    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM WorkTool ORDER BY description');
        $query->execute();
        $rows = $query->fetchAll();
        $workTools = array();

        foreach ($rows as $row) {
            $workTools[] = new WorkTool(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'created' => $row['created'],
            ));
        }

        return $workTools;
    }

     /**
      * Haetaan haluttu työkalu ja palautetaan se oliona.
      */
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM WorkTool WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $workTool = new WorkTool(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'created' => $row['created'],
            ));

            return $workTool;
        }

        return null;
    }

     /**
      * Haetaan haluttu työkalu kuvauksella ja palautetaan se oliona.
      */
    public static function findWithDescription($description) {
        $query = DB::connection()->prepare('SELECT * FROM WorkTool WHERE description = :description LIMIT 1');
        $query->execute(array('description' => $description));
        $row = $query->fetch();

        if ($row) {
            $workTool = new WorkTool(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'created' => $row['created'],
            ));

            return $workTool;
        }

        return null;
    }

     /**
      * Tallennetaan olion tiedot tietokantaan.
      */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO WorkTool (description, longer_description) VALUES (:description, :longer_description) RETURNING id');
        $query->execute(array('description' => $this->description, 'longer_description' => $this->longer_description));
        $this->id = $query->fetch()['id'];
    }
    
     /**
      * Päivitetään olion tiedot tietokantaan.
      */
    public function update() {
        $query = DB::connection()->prepare('UPDATE WorkTool SET description = :description, longer_description = :longer_description WHERE id = :id');
        $query->execute(array('description' => $this->description, 'longer_description' => $this->longer_description, 'id' => $this->id));
    }
    
     /**
      * Poistetaan olion tiedot tietokannasta.
      */
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM WorkTool WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

     /**
      * Tarkastetaan, että työkalun kuvaus on sallittu.
      */
    public function validate_description() {
        $errors = array();
        if ($this->description == '' || $this->description == null) {
            $errors[] = 'Työkalu vaatii kuvauksen!';
        }
        if (strlen($this->description) > 30) {
            $errors[] = 'Työkalun kuvaus saa olla enintään 30 merkkiä pitkä';
        }

        return $errors;
    }

     /**
      * Tarkastetaan, että työkalun tarkempi kuvaus on sallittu.
      */
    public function validate_longer_description() {
        $errors = array();
        if (strlen($this->longer_description) > 360) {
            $errors[] = 'Työkalun tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }
    
     /**
      * Tarkastetaan työkalun yhteyden olemassaoleviin töihin.
      */
    public function validate_connections() {
        $errors = array();
        
        $query = DB::connection()->prepare('SELECT * FROM Work WHERE tool = :id');
        $query->execute(array('id' => $this->id));
        
        $rows = $query->fetchAll();
        
        if (count($rows) > 0) {
            $errors[] = $this->description . ' liittyy ' . count($rows) . ' työhön!';
        }
        
        return $errors;
    }

}
