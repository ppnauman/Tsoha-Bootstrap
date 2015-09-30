<?php

class CatchModel extends BaseModel {
    public $first_name, $sure_name, $catch_id, $date, $time, $species, $count,
            $length, $weight, $water_sys, $location, $wind_speed, $wind_dir, $air_temp,
            $water_temp, $cloudiness, $notes, $picture_url, $trap_id, $trap_type,
            $trap_model, $trap_size, $trap_color, $validators;

    
    public function __construct($attributes) {
        
        parent::__construct($attributes);
        $this->validators = array('validate_species', 'validate_count',
            'validate_length', 'validate_weight', 'validate_water_sys',
            'validate_location', 'validate_air_temp', 'validate_water_temp',
            'validate_notes', 'validate_picture_url');
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
                    . " saaliskuva, pyydys) VALUES(:pvm, :aika, :laji, :lkm, :pituus,"
                    . " :paino, :vesisto, :paikka, :tuulenVoima, :tuulenSuunta,"
                    . " :ilmanLampo, :vedenLampo, :pilvisyys, :huomiot, :kuva, :pyydys)"
                    . " RETURNING saalisid");

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
            
            //NOTE:variable #username for testing only, switch to session USERNAME later
            $username = 'kalakalle';

            $query_2 = $pdo_connection->prepare("INSERT INTO pyydystaja VALUES("
                    . ":kalastaja, :saalisid)");
            $query_2 -> execute(array('kalastaja' => $username, 'saalisid' => $this->catch_id));
                   
        } catch (PDOException $e) {
            $success = false;
        }
        //end of transaction
        if (!$success) {
            $pdo_connection->rollBack();
        } else {
            $pdo_connection->commit();
        }
    }
    

    public static function all() {
        
        $query = DB::connection()->prepare("SELECT etunimi, sukunimi, saalistieto.saalisid,"
                . " pvm, kellonaika, kalalaji, lkm, pituus, paino, vesisto, paikka,"
                . " tuulenvoimakkuus,ilmanlampo, vedenlampo, pilvisyys, huomiot,"
                . " saaliskuva, pyydysid, tyyppi, malli, koko, vari FROM kalastaja,"
                . " saalistieto, pyydys, pyydystaja WHERE saalistieto.saalisid=pyydystaja.saalisid"
                . " AND kalastaja.kayttajatunnus=pyydystaja.kalastaja AND"
                . " saalistieto.pyydys=pyydys.pyydysid ORDER BY pvm DESC");
        $query->execute();
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
                'catch_id'=>$catch['pyydysid'],
                'trap_type'=>$catch['tyyppi'],
                'trap_model'=>$catch['malli'],
                'trap_size'=>$catch['koko'],
                'trap_color'=>$catch['vari'],
            ));
        }
        
        return $catches;
    }
    
    
    public static function find($id) {
        
        $query = DB::connection()->prepare("SELECT etunimi, sukunimi, saalistieto.saalisid,"
                . " pvm, kellonaika, kalalaji, lkm, pituus, paino, vesisto, paikka,"
                . " tuulenvoimakkuus, ilmanlampo, vedenlampo, pilvisyys, huomiot, saaliskuva,"
                . " pyydysid, tyyppi, malli, koko, vari FROM kalastaja, saalistieto,"
                . " pyydys, pyydystaja WHERE saalistieto.saalisid=pyydystaja.saalisid AND"
                . " kalastaja.kayttajatunnus=pyydystaja.kalastaja AND"
                . " saalistieto.pyydys=pyydys.pyydysid AND saalistieto.saalisid=:saalisid"
                . " LIMIT 1");

        $query->execute(array('saalisid' => $id));
        $resultRow = $query->fetch();
        
        if($resultRow) {
            $catch = new CatchModel(array(
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
    
    
    //input validators
    public function validate_species() {
        $errors = array();
        if(is_null($this->species)) {
            $errors[] = "Kalalajin nimi puuttuu!";
        }
        return $errors;
    }
    
    
    public function validate_count() {
        $errors = array();
        if(!is_numeric($this->count)) {
            $errors[] = "Lukumäärä -kentän syötteen on oltava lukuarvo!";
        }
        if(is_null($this->count)) {
            $errors[] = "Lukumäärä -kenttä ei saa olla tyhjä";
        }
        if($this->count == '0') {
            $errors[] = "Lukumäärä -kentän syöte ei saa olla nolla.";
        }
        return $errors;
    }
    
    
    public function validate_length() {
        $errors = array();
        if(!is_numeric($this->length) && !($this->length=="")) {
            $errors[] = "Saaliin pituus - kentän syötteen tulee olla lukuarvo!";
        }
        if($this->length == '0') {
            $errors[] = "Saaliin pituus - kentän syöte ei saa olla nolla";
        }
        return $errors;
    }
    
    
    public function validate_weight() {
        $errors = array();
        if(!is_numeric($this->weight) && !($this->weight=="")) {
            $errors[] = "Saaliin paino -kentän syötteen tulee olla lukuarvo!";
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
        if(strlen($this->notes) > 300) {
            $errors[] = "Lisätiedot -kentän syöte saa olla korkeintaan 300 merkkiä.";
        }
        
        return $errors;
    }
    
    
    public function validate_picture_url() {
        $errors = array();
        if(strlen($this->picture_url) > 300) {
            $errors[] = "Saaliskuvan osoite saa olla korkeintaan 300 merkkiä.";
        }
        
        return $errors;
    }
}    