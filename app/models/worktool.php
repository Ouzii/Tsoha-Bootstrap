<?php
/*
 * Malli, joka mallintaa työkalua.
 */
Class WorkTool extends BaseModel {

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
      * Haetaan oliolle id kuvauksella.
      */
//    public function getId() {
//        $query = DB::connection()->prepare('SELECT id FROM Tyokalu WHERE kuvaus = :kuvaus');
//        $query->execute(array('kuvaus' => $this->kuvaus));
//
//        $row = $query->fetch();
//        $this->id = $row['id'];
//    }

//     /*
//      * Haetaan kaikki työkalut ja palautetaan ne listana.
//      */
//    public static function all() {
//        $query = DB::connection()->prepare('SELECT * FROM Tyokalu');
//        $query->execute();
//        $rows = $query->fetchAll();
//        $workTools = array();
//
//        foreach ($rows as $row) {
//            $workTools[] = new WorkTool(array(
//                'id' => $row['id'],
//                'kuvaus' => $row['kuvaus'],
//                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
//                'luotu' => $row['luotu'],
//            ));
//        }
//
//        return $workTools;
//    }

     /*
      * Haetaan kaikki työkalut aakkosjärjestyksessä ja palautetaan ne listana.
      */
    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu ORDER BY kuvaus');
        $query->execute();
        $rows = $query->fetchAll();
        $workTools = array();

        foreach ($rows as $row) {
            $workTools[] = new WorkTool(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));
        }

        return $workTools;
    }

     /*
      * Haetaan haluttu työkalu ja palautetaan se oliona.
      */
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $workTool = new WorkTool(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));

            return $workTool;
        }

        return null;
    }

     /*
      * Haetaan haluttu työkalu kuvauksella ja palautetaan se oliona.
      */
    public static function findWithDescription($kuvaus) {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu WHERE kuvaus = :kuvaus LIMIT 1');
        $query->execute(array('kuvaus' => $kuvaus));
        $row = $query->fetch();

        if ($row) {
            $workTool = new WorkTool(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));

            return $workTool;
        }

        return null;
    }

     /*
      * Tallennetaan olion tiedot tietokantaan.
      */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Tyokalu (kuvaus, tarkempi_kuvaus) VALUES (:kuvaus, :tarkempi_kuvaus) RETURNING id');
        $query->execute(array('kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus));
        $this->id = $query->fetch()['id'];
    }
    
     /*
      * Päivitetään olion tiedot tietokantaan.
      */
    public function update() {
        $query = DB::connection()->prepare('UPDATE Tyokalu SET kuvaus = :kuvaus, tarkempi_kuvaus = :tarkempi_kuvaus WHERE id = :id');
        $query->execute(array('kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus, 'id' => $this->id));
    }
    
     /*
      * Poistetaan olion tiedot tietokannasta.
      */
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Tyokalu WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

     /*
      * Tarkastetaan, että työkalun kuvaus on sallittu.
      */
    public function validate_kuvaus() {
        $errors = array();
        if ($this->kuvaus == '' || $this->kuvaus == null) {
            $errors[] = 'Työkalu vaatii kuvauksen!';
        }
        if (strlen($this->kuvaus) > 30) {
            $errors[] = 'Työkalun kuvaus saa olla enintään 30 merkkiä pitkä';
        }

        return $errors;
    }

     /*
      * Tarkastetaan, että työkalun tarkempi kuvaus on sallittu.
      */
    public function validate_tarkempi_kuvaus() {
        $errors = array();
        if (strlen($this->tarkempi_kuvaus) > 360) {
            $errors[] = 'Työkalun tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }
    
     /*
      * Tarkastetaan työkalun yhteyden olemassaoleviin töihin.
      */
    public function validate_connections() {
        $errors = array();
        
        $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE tyokalu = :id');
        $query->execute(array('id' => $this->id));
        
        $rows = $query->fetchAll();
        
        if (count($rows) > 0) {
            $errors[] = $this->kuvaus . ' liittyy ' . count($rows) . ' työhön!';
        }
        
        return $errors;
    }

}
