<?php

class User extends BaseModel {

    public $tunnus, $salasana, $ika, $kuvaus, $admin;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_tunnus', 'validate_salasana', 'validate_kuvaus', 'validate_ika');
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

    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja ORDER BY tunnus');
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

    public static function getUsersWorks($tunnus) {
        $query = DB::connection()->prepare('SELECT tyo FROM KayttajanTyot WHERE tekija = :tunnus ORDER BY tyo');
        $query->execute(array('tunnus' => $tunnus));

        $rows = $query->fetchAll();
        $tyot = array();

        foreach ($rows as $row) {
            $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE id = :id');
            $query->execute(array('id' => $row['tyo']));

            $tulos = $query->fetch();

            $tyo = new Work(array(
                'id' => $tulos['id'],
                'kuvaus' => $tulos['kuvaus'],
                'kohde' => $tulos['kohde'],
                'tyokalu' => $tulos['tyokalu'],
                'tehty' => $tulos['tehty']
            ));
            
            $tyot[] = $tyo;
        }
        
        return $tyot;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (tunnus, salasana, ika, kuvaus, admin) VALUES (:tunnus, :salasana, :ika, :kuvaus, :admin)');
        if (is_numeric($this->ika)) {
            $query->execute(array('tunnus' => $this->tunnus, 'salasana' => $this->salasana, 'ika' => $this->ika, 'kuvaus' => $this->kuvaus, 'admin' => $this->admin));
        } else {
            $query->execute(array('tunnus' => $this->tunnus, 'salasana' => $this->salasana, 'ika' => null, 'kuvaus' => $this->kuvaus, 'admin' => $this->admin));
        }
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Kayttaja SET kuvaus = :kuvaus, ika = :ika, salasana =:salasana WHERE tunnus = :tunnus');
        $query->execute(array('kuvaus' => $this->kuvaus, 'ika' => $this->ika, 'salasana' => $this->salasana, 'tunnus' => $this->tunnus));

        if ($this->admin == true) {
            $query = DB::connection()->prepare('UPDATE Kayttaja SET admin = TRUE WHERE tunnus = :tunnus');
            $query->execute(array('tunnus' => $this->tunnus));
        } else {
            $query = DB::connection()->prepare('UPDATE Kayttaja SET admin = FALSE WHERE tunnus = :tunnus');
            $query->execute(array('tunnus' => $this->tunnus));
        }
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Kayttaja WHERE tunnus = :tunnus');
        $query->execute(array('tunnus' => $this->tunnus));
    }

    public function validate_tunnus() {
        $errors = array();

        if ($this->tunnus == '' || $this->tunnus == null) {
            $errors[] = 'Käyttäjätunnus on pakollinen!';
        }

        if (strlen($this->tunnus) > 20) {
            $errors[] = 'Käyttäjätunnus voi olla enintään 20 merkkiä pitkä!';
        }

        return $errors;
    }

    public function validate_unique_tunnus() {
        $errors = array();
        $olemassaOlevat = User::all();
        $vanhatTunnukset = array();
        foreach ($olemassaOlevat as $olemassaOleva) {
            $vanhatTunnukset[] = $olemassaOleva->tunnus;
        }
        if (in_array($this->tunnus, $vanhatTunnukset)) {
            $errors[] = 'Käyttäjätunnus on jo olemassa!';
        }

        return $errors;
    }

    public function validate_salasana() {
        $errors = array();

        if ($this->salasana == '' || $this->salasana == null) {
            $errors[] = 'Salasana ei voi olla tyhjä!';
        }

        if (strlen($this->salasana) > 20 || strlen($this->salasana) < 8) {
            $errors[] = 'Salasanan on oltava 8-20 merkkiä pitkä!';
        }

        return $errors;
    }

    public function validate_kuvaus() {
        $errors = array();

        if (strlen($this->kuvaus) > 360) {
            $errors[] = 'Kuvaus voi olla enintään 360 merkkiä pitkä!';
        }

        return $errors;
    }

    public function validate_ika() {
        $errors = array();

        if (is_numeric($this->ika) == FALSE && ($this->ika != '' || $this->ika != null)) {
            $errors[] = 'Ikäsi täytyy olla numeroina!';
        }

        return $errors;
    }

    public function validate_connections() {
        $query = DB::connection()->prepare('SELECT * FROM KayttajanTyot WHERE tekija = :tunnus');
        $query->execute(array('tunnus' => $this->tunnus));
        $rows = $query->fetchAll();

        $errors = array();

        if (count($rows) > 0) {
            $errors[] = $this->tunnus . ' liittyy ' . count($rows) . ' työhön!';
        }

        return $errors;
    }

    public static function authenticate($tunnus, $salasana) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE tunnus = :tunnus AND salasana = :salasana LIMIT 1');
        $query->execute(array('tunnus' => $tunnus, 'salasana' => $salasana));
        $row = $query->fetch();
        if ($row) {
            $kayttaja[] = new User(array(
                'tunnus' => $row['tunnus'],
                'ika' => $row['ika'],
                'salasana' => $row['salasana'],
                'kuvaus' => $row['kuvaus'],
                'admin' => $row['admin']
            ));
            return $kayttaja;
        } else {
            return null;
        }
    }

}
