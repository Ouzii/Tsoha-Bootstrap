<?php

/**
 * Malli, joka mallintaa käyttäjää.
 */
class User extends BaseModel {

    public $tunnus, $salasana, $ika, $kuvaus, $admin;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_tunnus', 'validate_salasana', 'validate_kuvaus', 'validate_ika');
    }

//    /**
//     * Haetaan kaikki käyttäjät tietokannasta ja palautetaan ne listana.
//     */
//
//    public static function all() {
//        $query = DB::connection()->prepare('SELECT * FROM Kayttaja');
//        $query->execute();
//        $rows = $query->fetchAll();
//        $users = array();
//
//        foreach ($rows as $row) {
//            $users[] = new User(array(
//                'tunnus' => $row['tunnus'],
//                'salasana' => $row['salasana'],
//                'ika' => $row['ika'],
//                'kuvaus' => $row['kuvaus'],
//                'admin' => $row['admin'],
//            ));
//        }
//
//        return $users;
//    }

    /**
     * Haetaan kaikki käyttäjät tietokannasta aakkosjärjestyksessä ja palautetaan ne listana.
     */
    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja ORDER BY tunnus');
        $query->execute();
        $rows = $query->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $users[] = new User(array(
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana'],
                'ika' => $row['ika'],
                'kuvaus' => $row['kuvaus'],
                'admin' => $row['admin'],
            ));
        }

        return $users;
    }

    /**
     * Etsitään haluttu käyttäjä tunnuksen perusteella ja palautetaan se oliona.
     */
    public static function find($username) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE tunnus = :tunnus LIMIT 1');
        $query->execute(array('tunnus' => $username));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array(
                'tunnus' => $row['tunnus'],
                'salasana' => $row['salasana'],
                'ika' => $row['ika'],
                'kuvaus' => $row['kuvaus'],
                'admin' => $row['admin'],
            ));

            return $user;
        }

        return null;
    }

    /**
     * Tallennetaan olion tiedot tietokantaan.
     */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (tunnus, salasana, ika, kuvaus, admin) VALUES (:tunnus, :salasana, :ika, :kuvaus, :admin)');
        $query->execute(array('tunnus' => $this->tunnus, 'salasana' => $this->salasana, 'ika' => $this->ika, 'kuvaus' => $this->kuvaus, 'admin' => $this->admin));
    }

    /**
     * Päivitetään olion tiedot tietokantaan. Tarkistetaan erikseen admin-ominaisuus, 
     * sillä muuten syntaksi ei toimi ominaisuuden ollessa false.
     */
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

    /**
     * Poistetaan olion tiedot tietokanasta.
     */
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM KayttajanTyot WHERE tekija = :tunnus');
        $query->execute(array('tunnus' => $this->tunnus));
        $query = DB::connection()->prepare('DELETE FROM Kayttaja WHERE tunnus = :tunnus');
        $query->execute(array('tunnus' => $this->tunnus));
    }

    /**
     * Tarkastetaan, että annettu tunnus on sallittu.
     */
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

    /**
     * Tarkastetaan, että annettua tunnusta ei ole ennestään olemassa.
     */
    public function validate_unique_tunnus() {
        $errors = array();
        $existingUsers = User::allAlphabetical();
        $existingUsernames = array();
        foreach ($existingUsers as $existingUser) {
            $existingUsernames[] = $existingUser->tunnus;
        }
        if (in_array($this->tunnus, $existingUsernames)) {
            $errors[] = 'Käyttäjätunnus on jo olemassa!';
        }

        return $errors;
    }

    /**
     * Tarkastetaan, että salasana on sallittu.
     */
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

    /*     * *
     * Tarkastetaan, että kuvaus on sallittu.
     */

    public function validate_kuvaus() {
        $errors = array();

        if (strlen($this->kuvaus) > 360) {
            $errors[] = 'Kuvaus voi olla enintään 360 merkkiä pitkä!';
        }

        return $errors;
    }

    /*     * *
     * Tarkastetaan, että ikä on sallittu.
     */

    public function validate_ika() {
        $errors = array();

        if ($this->ika == null || $this->ika == '') {
            $errors[] = 'Anna ikäsi!';
            return $errors;
        }

        if (strpos($this->ika, '.') || strpos($this->ika, ',')) {
            $errors[] = 'Ikäsi täytyy olla kokonaisluku!';
            return $errors;
        }

        if (is_numeric($this->ika) == FALSE) {
            $errors[] = 'Ikäsi täytyy olla numeroina!';
        }

        if ($this->ika < 0 || $this->ika > 999) {
            $errors[] = 'Valitettavasti sovelluksen ikähaitari on 0-999v.';
        }

        return $errors;
    }

    /*     * *
     * Tarkastetaan onko käyttäjällä liitoksia olemassaoleviin töihin. 
     * Jos on, palautetaan virheilmoituksena liitosten määrä.
     */

    public function validate_connections() {
        $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE id IN (SELECT tyo FROM KayttajanTyot WHERE tekija = :tunnus) AND tehty = FALSE');
        $query->execute(array('tunnus' => $this->tunnus));
        $rows = $query->fetchAll();

        $errors = array();

        if (count($rows) > 0) {
            $errors[] = $this->tunnus . ' liittyy ' . count($rows) . ' tekemättömään työhön!'; 
        }

        return $errors;
    }

    /*     * *
     * Tarkastetaan, että tietokannasta löytyy käyttäjä, jonka tunnus ja salasana täsmäävät annettuihin.
     */

    public static function authenticate($tunnus, $salasana) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE tunnus = :tunnus AND salasana = :salasana LIMIT 1');
        $query->execute(array('tunnus' => $tunnus, 'salasana' => $salasana));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'tunnus' => $row['tunnus'],
                'ika' => $row['ika'],
                'salasana' => $row['salasana'],
                'kuvaus' => $row['kuvaus'],
                'admin' => $row['admin']
            ));
            return $user;
        } else {
            return null;
        }
    }

}
