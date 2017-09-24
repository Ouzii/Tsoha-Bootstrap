<?php

Class WorkTool extends BaseModel {

    public $id, $kuvaus, $tarkempi_kuvaus, $luotu;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->luotu = substr($this->luotu, 0, 19);
        $this->validators = array('validate_kuvaus', 'validate_tarkempi_kuvaus');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu');
        $query->execute();
        $rows = $query->fetchAll();
        $tyokalut = array();

        foreach ($rows as $row) {
            $tyokalut[] = new WorkTool(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));
        }

        return $tyokalut;
    }

    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu ORDER BY kuvaus');
        $query->execute();
        $rows = $query->fetchAll();
        $tyokalut = array();

        foreach ($rows as $row) {
            $tyokalut[] = new WorkTool(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));
        }

        return $tyokalut;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $tyokalu[] = new WorkTool(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));

            return $tyokalu;
        }

        return null;
    }

    public static function findKuvaus($kuvaus) {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu WHERE kuvaus = :kuvaus LIMIT 1');
        $query->execute(array('kuvaus' => $kuvaus));
        $row = $query->fetch();

        if ($row) {
            $tyokalu[] = new WorkTool(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));

            return $tyokalu;
        }

        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Tyokalu (kuvaus, tarkempi_kuvaus) VALUES (:kuvaus, :tarkempi_kuvaus)');
        $query->execute(array('kuvaus' => $this->kuvaus, 'tarkempi_kuvaus' => $this->tarkempi_kuvaus));
//        Kint::trace();
//        Kint::dump($row);
    }

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
    
        public function validate_tarkempi_kuvaus() {
        $errors = array();
        if (strlen($this->tarkempi_kuvaus) > 360) {
            $errors[] = 'Työkalun tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }
}
