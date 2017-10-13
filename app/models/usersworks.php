<?php

/**
 * Malli käyttäjän ja työn liittävästä tietokohteesta. 
 * Mallia käytetään enemmänkin tauluja yhdistävien metodien sijaintina.
 */

class UsersWorks extends BaseModel {

     /**
      * Haetaan haluttuun käyttäjään liittyvät työt ja palautetaan ne listana.
      */
    public static function getUsersWorks($username) {
        $query = DB::connection()->prepare('SELECT * FROM Work WHERE id IN (SELECT work FROM UsersWorks WHERE username = :username) ORDER BY id');
        $query->execute(array('username' => $username));
        $rows = $query->fetchAll();
        $works = array();

        foreach ($rows as $row) {
            $work = new Work(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'object' => $row['object'],
                'tool' => $row['tool'],
                'done' => $row['done']
            ));

            $works[] = $work;
        }

        return $works;
    }

     /**
      * Haetaan annettuun työhön liittyvät tekijät ja palautetaan ne listana.
      */
    public static function getUsersForWork($id) {
        $query = DB::connection()->prepare('SELECT username FROM UsersWorks WHERE work = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $users[] = new User(array(
                'username' => $row['username'],
            ));
        }
        return $users;
    }

     /**
      * Tallennetaan tieto työn ja jokaisen tekijän yhteydestä tietokantaa.
      */
    public static function saveUsers($id, $users) {
        foreach ($users as $user) {
            $query = DB::connection()->prepare('INSERT INTO UsersWorks (username, work) VALUES (:username, :id)');
            $query->execute(array('id' => $id, 'username' => $user));
        }
    }

}
