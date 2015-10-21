<?php

class CatchModel extends BaseModel {
    public $username, $first_name, $sure_name, $catch_id, $date, $time, $species, $count,
            $length, $weight, $water_sys, $location, $wind_speed, $wind_dir, $air_temp,
            $water_temp, $cloudiness, $notes, $picture_url, $trap_id, $trap_type,
            $trap_model, $trap_size, $trap_color, $friends, $validators;

    
    public function __construct($attributes) {
        
        parent::__construct($attributes);
        $this->validators = array('validate_date', 'validate_time', 'validate_species', 'validate_count',
            'validate_length', 'validate_weight', 'validate_water_sys',
            'validate_location', 'validate_air_temp', 'validate_water_temp',
            'validate_notes', 'validate_picture_url');
    }
    
    public static function catchers($id) {
        $catchers = array();
        $query = DB::connection()->prepare("SELECT kalastaja FROM pyydystaja WHERE saalisid=:id");
        $query->execute(array('id'=>$id));
        $resultRows = $query->fetchAll();
        
        foreach($resultRows as $row) {
            $catchers[] = $row['kalastaja'];
        }
        
        return $catchers;
    }
    
    
    public function save() {
        
        $pdo_connection = DB::connection();
        $success = true;
        //the begin of transaction
        $pdo_connection->beginTransaction();

        try {
            $query = $pdo_connection->prepare("INSERT INTO saalistieto (pvm, kellonaika,"
                    . " kalalaji, lkm, pituus, paino, vesisto, paikka, tuulenvoimakkuus,"
                    . " tuulensuunta, ilmanlampo, vedenlampo, pilvisyys, huomiot,"
                    . " saaliskuva, pyydys) VALUES(:pvm, NULLIF(:aika,'')::time, :laji, :lkm,"
                    . " NULLIF(:pituus,'')::numeric, NULLIF(:paino,'')::numeric, :vesisto,"
                    . " :paikka, :tuulenVoima, :tuulenSuunta,"
                    . " NULLIF(:ilmanLampo,'')::integer, NULLIF(:vedenLampo,'')::integer, :pilvisyys,"
                    . " :huomiot, :kuva, NULLIF(:pyydys,'default')::integer) RETURNING saalisid");

            $query->execute(array('pvm' => $this->date, 'aika' => $this->time,
                'laji' => $this->species, 'lkm' => $this->count,
                'pituus' => $this->length, 'paino' => $this->weight,
                'vesisto' => $this->water_sys, 'paikka' => $this->location,
                'tuulenVoima' => $this->wind_speed, 'tuulenSuunta' => $this->wind_dir,
                'ilmanLampo' => $this->air_temp, 'vedenLampo' => $this->water_temp,
                'pilvisyys' => $this->cloudiness,'huomiot' => $this->notes,
                'kuva' => $this->picture_url, 'pyydys' => $this->trap_id));

            $resultRow = $query->fetch();
            $this->catch_id = $resultRow['saalisid'];
            
            $catchers;
            $username = array($_SESSION['user']);
            if(isset($this->friends)){
                $catchers = array_merge($username, $this->friends);
            } else {
                $catchers = $username;
            }
            
            $query_2 = $pdo_connection->prepare("INSERT INTO pyydystaja VALUES("
                    . ":kalastaja, :saalisid)");
            foreach ($catchers as $catcher) {
                $query_2 -> execute(array('kalastaja' => $catcher, 'saalisid' => $this->catch_id));
            }
            
        } catch (PDOException $e) {
            $success = false;
            Kint::dump($e);
            Kint::trace();
        }
        //end of transaction
        if (!$success) {
            $pdo_connection->rollBack();
        } else {
            $pdo_connection->commit();
        }
    }
    
    
    public function update() {
        $pdo_conn = DB::connection(); 
        
            try {
                $query = $pdo_conn->prepare("UPDATE saalistieto SET pvm=:date, kellonaika=NULLIF(:time,'')::time,"
                        . " kalalaji=:species, lkm=:count, pituus=NULLIF(:length,'')::numeric,"
                        . " paino=NULLIF(:weight,'')::numeric, vesisto=:water_sys,"
                        . " paikka=:location, tuulenvoimakkuus=:wind_speed, tuulensuunta=:wind_dir,"
                        . " ilmanlampo=NULLIF(:air_temp,'')::numeric, vedenlampo=NULLIF(:water_temp,'')::numeric,"
                        . " pilvisyys=:cloudiness, huomiot=:notes, saaliskuva=:picture_url, pyydys=NULLIF(:trap_id,'default')::integer"
                        . " WHERE saalisid=:catch_id");
                $query->execute(array('date'=>$this->date, 'time'=>$this->time, 'species'=>$this->species, 
                    'count'=>$this->count, 'length'=>$this->length, 'weight'=>$this->weight, 'water_sys'=>$this->water_sys, 
                    'location'=>$this->location, 'wind_dir'=>$this->wind_dir, 'wind_speed'=>$this->wind_speed, 
                    'air_temp'=>$this->air_temp, 'water_temp'=>$this->water_temp, 'cloudiness'=>$this->cloudiness, 
                    'notes'=>$this->notes, 'picture_url'=>$this->picture_url, 'trap_id'=>$this->trap_id, 'catch_id'=>$this->catch_id));
                
                
            } catch (PDOException $e) {
                
            }
        
    }
    
 

    public static function all() {
        $user = $_SESSION['user'];
       
        $query = DB::connection()->prepare("SELECT etunimi, sukunimi, saalistieto.saalisid,"
                . " pvm, to_char(kellonaika,'HH24:MI') as kellonaika, kalalaji, lkm,"
                . " pituus, paino, vesisto, paikka, tuulenvoimakkuus, ilmanlampo, vedenlampo,"
                . " pilvisyys, huomiot, saaliskuva, pyydys.pyydysid, pyydys.tyyppi,"
                . " pyydys.malli, pyydys.koko, pyydys.vari FROM saalistieto"
                . " LEFT JOIN pyydys ON saalistieto.pyydys=pyydys.pyydysid"
                . " INNER JOIN pyydystaja ON pyydystaja.saalisid = saalistieto.saalisid"
                . " INNER JOIN kalastaja ON kalastaja.kayttajatunnus = pyydystaja.kalastaja"
                . " WHERE (kalastaja.kayttajatunnus=:user OR (kalastaja.kayttajatunnus="
                . " ANY(SELECT DISTINCT kalastaja FROM kalakaveri WHERE kaveri=:usr))) ORDER BY pvm DESC");
        $query->execute(array('user'=>$user, 'usr'=>$user));
        $resultRows = $query->fetchAll();
        $catches = array();
        
        foreach($resultRows as $catch) {
            $catches[] = new CatchModel (array(
                'first_name'=>$catch['etunimi'],
                'sure_name'=>$catch['sukunimi'],
                'catch_id'=>$catch['saalisid'],
                'date'=>$catch['pvm'],
                'time'=>$catch['kellonaika'],
                'species'=>$catch['kalalaji'],
                'count'=>$catch['lkm'],
                'length'=>$catch['pituus'],
                'weight'=>$catch['paino'],
                'water_sys'=>$catch['vesisto'],
                'location'=>$catch['paikka'],
                'wind_speed'=>$catch['tuulenvoimakkuus'],
                'air_temp'=>$catch['ilmanlampo'],
                'water_temp'=>$catch['vedenlampo'],
                'cloudiness'=>$catch['pilvisyys'],
                'notes'=>$catch['huomiot'],
                'picture_url'=>$catch['saaliskuva'],
                'trap_id'=>$catch['pyydysid'],
                'trap_type'=>$catch['tyyppi'],
                'trap_model'=>$catch['malli'],
                'trap_size'=>$catch['koko'],
                'trap_color'=>$catch['vari'],
            ));
        }
        
        return $catches;
    }
    
    
    public static function find($id) {
        
        $query = DB::connection()->prepare("SELECT 1 FROM pyydystaja WHERE kalastaja=:usr AND saalisid=:id LIMIT 1");
        $query->execute(array('usr'=>$_SESSION['user'], 'id'=>$id));
        $row = $query->fetch();
        $resultRow=0;
        
        if($row) {
        
            $query = DB::connection()->prepare("SELECT kayttajatunnus, etunimi, sukunimi, saalistieto.saalisid,"
                    . " pvm, to_char(kellonaika,'HH24:MI') as kellonaika, kalalaji, lkm, pituus, paino, vesisto, paikka,"
                    . " tuulenvoimakkuus, tuulensuunta, ilmanlampo, vedenlampo, pilvisyys, huomiot, saaliskuva,"
                    . " pyydysid, tyyppi, malli, koko, vari FROM saalistieto"
                    . " LEFT JOIN pyydys ON pyydys.pyydysid=saalistieto.pyydys"
                    . " INNER JOIN pyydystaja ON pyydystaja.saalisid = saalistieto.saalisid AND pyydystaja.kalastaja=:user"
                    . " INNER JOIN kalastaja ON kalastaja.kayttajatunnus = pyydystaja.kalastaja"
                    . " WHERE saalistieto.saalisid=:saalisid" 
                    . " LIMIT 1");

            $query->execute(array('user'=>$_SESSION['user'], 'saalisid'=>$id));
            $resultRow = $query->fetch();
        
        
        }else{
             $query = DB::connection()->prepare("SELECT kayttajatunnus, etunimi, sukunimi, saalistieto.saalisid,"
                . " pvm, to_char(kellonaika,'HH24:MI') as kellonaika, kalalaji, lkm, pituus, paino, vesisto, paikka,"
                . " tuulenvoimakkuus, tuulensuunta, ilmanlampo, vedenlampo, pilvisyys, huomiot, saaliskuva,"
                . " pyydysid, tyyppi, malli, koko, vari FROM saalistieto"
                . " LEFT JOIN pyydys ON pyydys.pyydysid=saalistieto.pyydys"
                . " INNER JOIN pyydystaja ON pyydystaja.saalisid = saalistieto.saalisid"
                . " INNER JOIN kalastaja ON kalastaja.kayttajatunnus = pyydystaja.kalastaja"
                . " WHERE saalistieto.saalisid=:saalisid" 
                . " LIMIT 1");

            $query->execute(array('saalisid'=>$id));
            $resultRow = $query->fetch();
        }
        
        
        if($resultRow) {
            $catch = new CatchModel(array(
                'username'=>$resultRow['kayttajatunnus'],
                'first_name'=>$resultRow['etunimi'],
                'sure_name'=>$resultRow['sukunimi'],
                'catch_id'=>$resultRow['saalisid'],
                'date'=>$resultRow['pvm'],
                'time'=>$resultRow['kellonaika'],
                'species'=>$resultRow['kalalaji'],
                'count'=>$resultRow['lkm'],
                'length'=>$resultRow['pituus'],
                'weight'=>$resultRow['paino'],
                'water_sys'=>$resultRow['vesisto'],
                'location'=>$resultRow['paikka'],
                'wind_speed'=>$resultRow['tuulenvoimakkuus'],
                'wind_dir' =>$resultRow['tuulensuunta'],
                'air_temp'=>$resultRow['ilmanlampo'],
                'water_temp'=>$resultRow['vedenlampo'],
                'cloudiness'=>$resultRow['pilvisyys'],
                'notes'=>$resultRow['huomiot'],
                'picture_url'=>$resultRow['saaliskuva'],
                'trap_id'=>$resultRow['pyydysid'],
                'trap_type'=>$resultRow['tyyppi'],
                'trap_model'=>$resultRow['malli'],
                'trap_size'=>$resultRow['koko'],
                'trap_color'=>$resultRow['vari'],   
            ));
            
            return $catch;
        }
        
        return null;
    }
    
    public function destroy() {
        
        //check how many usernames are connected to this particular catch_id..
        $query = DB::connection()->prepare("SELECT COUNT(DISTINCT kalastaja) AS"
                . " catcher_count FROM pyydystaja WHERE saalisid = $this->catch_id");
        $query->execute();
        $resultRow = $query->fetch();
        $catcher_count = $resultRow['catcher_count'];
        
        $conn = DB::connection();
        $conn ->beginTransaction();
        
            $success = true;
            
            try {
                //delete foreign keys related to logged in user in pyydystaja
                $query = $conn->prepare("DELETE FROM pyydystaja WHERE kalastaja=:user"
                        . " AND saalisid=:catch_id");
                $query->execute(array('user' => $_SESSION['user'], 'catch_id' => $this->catch_id));

                //..when only one username/catch_id > remove catch data from saalistieto
                if ($catcher_count == 1) {
                    $query_2 = $conn->prepare("DELETE FROM saalistieto WHERE saalisid=$this->catch_id");
                    $query_2->execute();
                }

            } catch (PDOException $e) {
                $success = false;
                //Kint::dump($e);
            }
            
            if(!$success) {
                $conn->rollBack();
            } else {
                $conn->commit();
            }
    }
    
    
    //input validators
    public function validate_date() {
        $errors = array();
        $date_reg_exp = '/^[0-9]{4}-((0[1-9])|(1[0-2]))-(([0-2][0-9])|(3[0-1]))$/';
        if(preg_match($date_reg_exp, $this->date) != 1){
            $errors[] = "Päivämäärä -kentän syötteen tulee olla päivämäärä muodossa pp.kk.vvvv";
        }
        return $errors;
    }
    
    public function validate_time() {
        $errors = array();
        $time_reg_exp = "/^(([0-1][0-9])|([2][0-3])):[0-5][0-9]$/";
        if((preg_match($time_reg_exp, $this->time) != 1) && ($this->time != "")) {
            $errors[] = "Aika -kentän syötteen on oltava kellonaika muodossa hh:mm!";
        }
        return $errors;       
    }
    
    public function validate_species() {
        $errors = array();
        if(!$this->str_length_between($this->species, 1, 32)) {
            $errors[] = "Kalalaji -kentän syötteen pituus tulee olla 1-32 merkkiä.";
        }
        return $errors;
    }
    
    
    public function validate_count() {
        $errors = array();
        if(!is_numeric($this->count)) {
            $errors[] = "Lukumäärä -kentän syötteenä on oltava lukuarvo!";
        }
       
        if($this->count == '0') {
            $errors[] = "Lukumäärä -kentän syöte ei saa olla nolla.";
        }
        return $errors;
    }
    
    
    public function validate_length() {
        $errors = array();
        if(!is_numeric($this->length) && !($this->length=="")) {
            $errors[] = "Saaliin pituus - kentän syötteen tulee olla lukuarvo (desimaalierottimena '.')!";
        }
        if($this->length == '0') {
            $errors[] = "Saaliin pituus - kentän syöte ei saa olla nolla";
        }
        return $errors;
    }
    
    
    public function validate_weight() {
        $errors = array();
        if(!is_numeric($this->weight) && !($this->weight=="")) {
            $errors[] = "Saaliin paino -kentän syötteen tulee olla lukuarvo (desimaalierottimena '.')!";
        }
        if($this->weight == '0') {
           $errors[] = "Saaliin paino -kentän syöte ei saa olla nolla.";
        }
        return $errors;
    }
    
    
    public function validate_water_sys() {
        $errors = array();
        if(strlen($this->water_sys) > 32) {
            $errors[] ="Vesistö -kentän syöte saa olla korkeintaan 32 merkkiä.";
        }
        return $errors;   
    }
    
    
    public function validate_location() {
        $errors = array();
        if(strlen($this->location) > 64) {
            $errors[] ="Paikka -kentän syöte saa olla korkeintaan 64 merkkiä.";
        }
        return $errors;
    }
    
    
    public function validate_air_temp() {
        $errors = array();
        if(!is_numeric($this->air_temp) && !($this->air_temp=="")) {
            $errors[] = "Ilman lämpötila -kentän syötteenä tulee olla lukuarvo.";
        }
        return $errors;
    }
    
    
    public function validate_water_temp() {
        $errors = array();
        if(!is_numeric($this->water_temp) && !($this->water_temp=="")) {
            $errors[] = "Veden lämpötila -kentän syötteenä tulee olla lukuarvo.";
        }
        if($this->water_temp < 0) {
            $errors[] = "Veden lämpötila ei voi olla negatiivinen.";
        }
        
        return $errors;
    }
    
    
    public function validate_notes() {
        $errors = array();
        if(strlen($this->notes) > 600) {
            $errors[] = "Lisätiedot -kentän syöte saa olla korkeintaan 600 merkkiä.";
        }
        
        return $errors;
    }
    
    
    public function validate_picture_url() {
        $errors = array();
        if(strlen($this->picture_url) > 600) {
            $errors[] = "Saaliskuvan osoite saa olla korkeintaan 600 merkkiä.";
        }
        
        return $errors;
    }
}    