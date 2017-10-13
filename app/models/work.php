<?php
/**
 * Malli, joka mallintaa työtä.
 */
class Work extends BaseModel {

    public $id, $object, $tool, $description, $longer_description, $done, $completion_time, $users;

     /**
      * Heti konstruktorissa tarkistetaan, että onko työkalu ja -kohde tallennettuna id:n arvoina 
      * ja haetaan niiden kuvaukset.
      */
    public function __construct($attributes) {
        parent::__construct($attributes);
        if (is_numeric($this->tool)) {
            $this->getToolDescription();
        }

        if (is_numeric($this->object)) {
            $this->getObjectDescription();
        }
        $this->validators = array('validate_description', 'validate_longer_description', 'validate_users');
    }

     /**
      * Haetaan kaikki työt id:n mukaan järjestettynä ja palautetaan ne listana.
      */
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Work ORDER BY id ASC');
        $query->execute();
        $rows = $query->fetchAll();
        $works = array();

        foreach ($rows as $row) {
            $works[] = new Work(array(
                'id' => $row['id'],
                'object' => $row['object'],
                'tool' => $row['tool'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'done' => $row['done'],
                'completion_time' => (String) substr($row['completion_time'], 0, 19)
            ));
        }
        return $works;
    }

     /**
      * Etsitään haluttu työ tietokannasta ja palautetaan se oliona.
      */
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Work WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $work = new Work(array(
                'id' => $row['id'],
                'object' => $row['object'],
                'tool' => $row['tool'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'done' => $row['done'],
                'completion_time' => (String) substr($row['completion_time'], 0, 19)
            ));
            return $work;
        }

        return null;
    }

     /**
      * Etsitään työhön liittyvälle työkalulle kuvaus, joka tallennetaan työkalu-muuttujaan.
      */
    public function getToolDescription() {
        $query = DB::connection()->prepare('SELECT description FROM WorkTool WHERE id = :id');
        $query->execute(array('id' => $this->tool));
        $row = $query->fetch();

        $this->tool = $row['description'];
    }

     /**
      * Etsitään työhön liittyvälle työkohteelle kuvaus, joka tallennetaan työn kohde -muuttujaan.
      */
    public function getObjectDescription() {
        $query = DB::connection()->prepare('SELECT description FROM WorkObject WHERE id = :id');
        $query->execute(array('id' => $this->object));
        $row = $query->fetch();

        $this->object = $row['description'];
    }

     /**
      * Etsitään haluttu työ kuvauksella ja palautetaan se oliona.
      */
    public static function findWithDescription($description) {
        $query = DB::connection()->prepare('SELECT * FROM Work WHERE description = :description LIMIT 1');
        $query->execute(array('description' => $description));
        $row = $query->fetch();

        if ($row) {
            $work = new Work(array(
                'id' => $row['id'],
                'object' => $row['object'],
                'tool' => $row['tool'],
                'description' => $row['description'],
                'longer_description' => $row['longer_description'],
                'done' => $row['done'],
                'completion_time' => $row['completion_time'],
            ));

            return $work;
        }

        return null;
    }

     /**
      * Tallennetaan olion tiedot tietokantaan. 
      * Ennen SQL-lauseen suoritusta, haetaan työllä olevien työkalun ja -kohteen id:t.
      */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Work (object, tool, description, longer_description) VALUES (:object, :tool, :description, :longer_description) RETURNING id');
        $objectId = WorkObject::findWithDescription($this->object)->id;
        $toolId = WorkTool::findWithDescription($this->tool)->id;
        $query->execute(array('object' => $objectId, 'tool' => $toolId, 'description' => $this->description, 'longer_description' => $this->longer_description));
        $row = $query->fetch();
        $this->id = $row['id'];
        UsersWorks::saveUsers($this->id, $this->users);
    }

     /**
      * Päivitetään olion tiedot tietokantaan. 
      * Ennen SQL-lauseen suoritusta, haetaan työllä olevien työkalun ja -kohteen id:t. 
      * Työn done-attribuuttia muokataan erikseen, jotta tyhjä syöte ei tuottaisi virheitä. 
      * Lopuksi poistetaan vanhat merkinnät KäyttäjänTyöt -tietokohteesta ja lisätään päivitetyt tiedot tietokantaan.
      */
    public function update() {
        $query = DB::connection()->prepare('UPDATE Work SET object = :object, tool = :tool, description = :description, longer_description = :longer_description WHERE id = :id');
        $objectId = WorkObject::findWithDescription($this->object)->id;
        $toolId = WorkTool::findWithDescription($this->tool)->id;
        $query->execute(array('object' => $objectId, 'tool' => $toolId, 'description' => $this->description, 'longer_description' => $this->longer_description, 'id' => $this->id));

        if ($this->done == TRUE) {
            $query = DB::connection()->prepare('UPDATE Work SET done = TRUE, completion_time = now() WHERE id = :id');
            $query->execute(array('id' => $this->id));
        } elseif ($this->done == FALSE) {
            $query = DB::connection()->prepare('UPDATE Work SET done = FALSE, completion_time = null WHERE id = :id');
            $query->execute(array('id' => $this->id));
        }

        $query = DB::connection()->prepare('DELETE FROM UsersWorks WHERE work = :id');
        $query->execute(array('id' => $this->id));
        UsersWorks::saveUsers($this->id, $this->users);
    }

     /**
      * Poistetaan ensin työ KäyttäjänTyöt -tietokohteesta liitosvirheiden välttämiseksi. 
      * Sitten poistetaan olion tiedot tietokannasta.
      */
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM UsersWorks WHERE work = :id');
        $query->execute(array('id' => $this->id));
        
        $query = DB::connection()->prepare('DELETE FROM Work WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }
    
    public function Done() {
        $query = DB::connection()->prepare('UPDATE Work SET done = TRUE, completion_time = now() WHERE id = :id');
        $query->execute(array('id' => $this->id));
        
    }

     /**
      * Tarkastetaan, että työn kuvaus on sallittu.
      */
    public function validate_description() {
        $errors = array();
        if ($this->description == '' || $this->description == null) {
            $errors[] = 'Työ vaatii kuvauksen!';
        }
        if (strlen($this->description) > 30) {
            $errors[] = 'Työn kuvaus saa olla enintään 30 merkkiä pitkä';
        }

        return $errors;
    }

     /**
      * Tarkastetaan, että työn tarkempi kuvaus on sallittu.
      */
    public function validate_longer_description() {
        $errors = array();
        if (strlen($this->longer_description) > 360) {
            $errors[] = 'Työn tarkempi kuvaus saa olla enintään 360 merkkiä pitkä';
        }
        return $errors;
    }

     /**
      * Tarkastetaan, että työllä varmasti on vähintään yksi tekijä.
      */
    public function validate_users() {
        $errors = array();
        if (count($this->users) == 0) {
            $errors[] = 'Työllä täytyy olla tekijä!';
        }
        return $errors;
    }

}
