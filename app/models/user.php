<?php

class User extends BaseModel {
    
    public $tunnus, $salasana, $ika, $kuvaus, $admin;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja');
        $query->execute();
        $rows = $query->fetchAll();
        $kayttajat = array();
        
        foreach ($rows as $row) {
            $kayttajat[] = new User(array(
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana'],
                'ika' => $row['ika'],
                'kuvaus' => $row['kuvaus'],
                'admin' => $row['admin'],
            ));
        }

        return $kayttajat;
    }

    public static function find($tunnus) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE tunnus = :tunnus LIMIT 1');
        $query->execute(array('tunnus' => $tunnus));
        $row = $query->fetch();

        if ($row) {
            $kayttaja[] = new User(array(
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana'],
                'ika' => $row['ika'],
                'kuvaus' => $row['kuvaus'],
                'admin' => $row['admin'],
            ));
        

            return $kayttaja;
        }

        return null;
    }
    
        public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (tunnus, salasana, ika, kuvaus, admin) VALUES (:tunnus, :salasana, :ika, :kuvaus, :admin)');
        $query->execute(array('tunnus' => $this->tunnus, 'salasana' => $this->salasana, 'ika' => $this->ika, 'kuvaus' => $this->kuvaus, 'admin' => $this->admin));
        
//        Kint::trace();
//        Kint::dump($row);

    }

}