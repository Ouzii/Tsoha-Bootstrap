<?php

class WorkObject extends BaseModel {

    public $kuvaus, $tarkempi_kuvaus, $luotu;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->luotu = substr($this->luotu, 0, 19);
        $this->validators = array('validate_kuvaus', 'validate_tarkempi_kuvaus');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde');
        $query->execute();
        $rows = $query->fetchAll();
        $kohteet = array();

        foreach ($rows as $row) {
            $kohteet[] = new WorkObject(array(
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));
        }

        return $kohteet;
    }

    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM Tyon_kohde ORDER BY kuvaus');
        $query->execute();
        $rows = $query->fetchAll();
        $kohteet = array();

        foreach ($rows as $row) {
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

    public function validate_tarkempi_kuvaus() {
        $errors = array();
        if (strlen($this->tarkempi_kuvaus) > 360) {
            $errors[] = 'Työkohteen tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }

}
