<?php

/*
 * Malli käyttäjän ja työn liittävästä tietokohteesta. 
 * Mallia käytetään enemmänkin tauluja yhdistävien metodien sijaintina.
 */

class UsersWorks extends BaseModel {

     /*
      * Haetaan haluttuun käyttäjään liittyvät työt ja palautetaan ne listana.
      */
    public static function getUsersWorks($tunnus) {
        $query = DB::connection()->prepare('SELECT * FROM Tyo WHERE id IN (SELECT tyo FROM KayttajanTyot WHERE tekija = :tunnus) ORDER BY id');
        $query->execute(array('tunnus' => $tunnus));
        $rows = $query->fetchAll();
        $works = array();

        foreach ($rows as $row) {
            $tyo = new Work(array(
                'id' => $row['id'],
                'kuvaus' => $row['kuvaus'],
                'kohde' => $row['kohde'],
                'tyokalu' => $row['tyokalu'],
                'tehty' => $row['tehty']
            ));

            $works[] = $tyo;
        }

        return $works;
    }

     /*
      * Haetaan annettuun työhön liittyvät tekijät ja palautetaan ne listana.
      */
    public static function getUsersForWork($id) {
        $query = DB::connection()->prepare('SELECT tekija FROM KayttajanTyot WHERE tyo = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $users[] = new User(array(
                'tunnus' => $row['tekija'],
            ));
        }
        return $users;
    }

     /*
      * Tallennetaan tieto työn ja jokaisen tekijän yhteydestä tietokantaa.
      */
    public static function saveUsers($id, $users) {
        foreach ($users as $user) {
            $query = DB::connection()->prepare('INSERT INTO KayttajanTyot (tekija, tyo) VALUES (:tekija, :id)');
            $query->execute(array('id' => $id, 'tekija' => $user));
        }
    }

}
