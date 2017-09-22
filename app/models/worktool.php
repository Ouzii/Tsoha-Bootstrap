<?php

Class WorkTool extends BaseModel {

    public $kuvaus, $tarkempi_kuvaus, $luotu;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->luotu = substr($this->luotu, 0, 19);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu');
        $query->execute();
        $rows = $query->fetchAll();
        $tyokalut = array();

        foreach ($rows as $row) {
            $tyokalut[] = new WorkTool(array(
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
                'kuvaus' => $row['kuvaus'],
                'tarkempi_kuvaus' => $row['tarkempi_kuvaus'],
                'luotu' => $row['luotu'],
            ));
        }

        return $tyokalut;
    }

    public static function find($kuvaus) {
        $query = DB::connection()->prepare('SELECT * FROM Tyokalu WHERE kuvaus = :kuvaus LIMIT 1');
        $query->execute(array('kuvaus' => $kuvaus));
        $row = $query->fetch();

        if ($row) {
            $tyokalu[] = new WorkTool(array(
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

}
