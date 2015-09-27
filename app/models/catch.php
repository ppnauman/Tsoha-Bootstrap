<?php

class CatchModel extends BaseModel {
    public $etunimi, $sukunimi, $saalisID, $pvm, $kellonaika, $kalalaji, $lkm, $pituus, $paino, $vesisto,
            $paikka, $tuulenVoimakkuus, $tuulenSuunta, $ilmanLampo, $vedenLampo,
            $pilvisyys, $huomiot, $saaliskuva, $pyydysID, $pyydystyyppi, $pyydysmalli, $pyydyskoko, $pyydysvari;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public function save() {
        $pdo_connection = DB::connection();
        $success = true;
        //transaction begins
        $pdo_connection->beginTransaction();

        try {

            $query = $pdo_connection->prepare("INSERT INTO saalistieto (pvm, kellonaika, kalalaji, lkm, pituus, paino,"
                    . "vesisto, paikka, tuulenvoimakkuus, tuulensuunta, ilmanlampo, vedenlampo, pilvisyys, huomiot, saaliskuva, pyydys)"
                    . " VALUES(:pvm, :aika, :laji, :lkm,"
                    . ":pituus, :paino, :vesisto, :paikka, :tuulenVoima, :tuulenSuunta, :ilmanLampo,"
                    . " :vedenLampo, :pilvisyys, :huomiot, :kuva, :pyydys) RETURNING saalisid");

            $query->execute(array('pvm' => $this->pvm, 'aika' => $this->kellonaika, 'laji' => $this->kalalaji,
                'lkm' => $this->lkm, 'pituus' => $this->pituus, 'paino' => $this->paino, 'vesisto' => $this->vesisto,
                'paikka' => $this->paikka, 'tuulenVoima' => $this->tuulenVoimakkuus, 'tuulenSuunta' => $this->tuulenSuunta,
                'ilmanLampo' => $this->ilmanLampo, 'vedenLampo' => $this->vedenLampo, 'pilvisyys' => $this->pilvisyys,
                'huomiot' => $this->huomiot, 'kuva' => $this->saaliskuva, 'pyydys' => $this->pyydysID));

            $resultRow = $query->fetch();
            $this->saalisID = $resultRow['saalisid'];
            

            //NOTE:variable for testing only change to session USERNAME later
            $username = 'kalakalle';

            $query_2 = $pdo_connection->prepare("INSERT INTO pyydystaja VALUES(:kalastaja, :saalisid)");
            $query_2 -> execute(array('kalastaja' => $username, 'saalisid' => $this->saalisID));
        
            
        } catch (PDOException $e) {
            $success = false;
        }

        if (!$success) {
            $pdo_connection->rollBack();
        } else {
            $pdo_connection->commit();
        }
    }
    

    public static function all() {
        $query = DB::connection()->prepare("SELECT etunimi, sukunimi, saalistieto.saalisid, pvm, kellonaika, kalalaji, lkm, pituus, paino, vesisto, paikka, tuulenvoimakkuus,ilmanlampo, vedenlampo, pilvisyys, huomiot, saaliskuva, pyydysid, tyyppi, malli, koko, vari FROM kalastaja, saalistieto, pyydys, pyydystaja WHERE saalistieto.saalisid=pyydystaja.saalisid AND kalastaja.kayttajatunnus=pyydystaja.kalastaja AND saalistieto.pyydys=pyydys.pyydysid ORDER BY pvm DESC");
        $query->execute();
        $resultRows = $query->fetchAll();
        $catches = array();
        
        foreach($resultRows as $catch) {
            $catches[] = new CatchModel (array(
                'etunimi'=>$catch['etunimi'],
                'sukunimi'=>$catch['sukunimi'],
                'saalisID'=>$catch['saalisid'],
                'pvm'=>$catch['pvm'],
                'kellonaika'=>$catch['kellonaika'],
                'kalalaji'=>$catch['kalalaji'],
                'lkm'=>$catch['lkm'],
                'pituus'=>$catch['pituus'],
                'paino'=>$catch['paino'],
                'vesisto'=>$catch['vesisto'],
                'paikka'=>$catch['paikka'],
                'tuulenVoimakkuus'=>$catch['tuulenvoimakkuus'],
                'ilmanLampo'=>$catch['ilmanlampo'],
                'vedenLampo'=>$catch['vedenlampo'],
                'pilvisyys'=>$catch['pilvisyys'],
                'huomiot'=>$catch['huomiot'],
                'saaliskuva'=>$catch['saaliskuva'],
                'pyydysID'=>$catch['pyydysid'],
                'pyydystyyppi'=>$catch['tyyppi'],
                'pyydysmalli'=>$catch['malli'],
                'pyydyskoko'=>$catch['koko'],
                'pyydysvari'=>$catch['vari'],
            ));
        }
        
        return $catches;
    }
    
    
    
    public static function find($id) {
        $query = DB::connection()->prepare("SELECT etunimi, sukunimi, saalistieto.saalisid, pvm, kellonaika, kalalaji, lkm, pituus, paino, vesisto, paikka, tuulenvoimakkuus,ilmanlampo, vedenlampo, pilvisyys, huomiot, saaliskuva, pyydysid, tyyppi, malli, koko, vari FROM kalastaja, saalistieto, pyydys, pyydystaja WHERE saalistieto.saalisid=pyydystaja.saalisid AND kalastaja.kayttajatunnus=pyydystaja.kalastaja AND saalistieto.pyydys=pyydys.pyydysid AND saalistieto.saalisid=:saalisid LIMIT 1");
        $query->execute(array('saalisid'=>$id));
        $resultRow = $query->fetch();
        
        if($resultRow) {
            $catch = new CatchModel(array(
                'etunimi'=>$resultRow['etunimi'],
                'sukunimi'=>$resultRow['sukunimi'],
                'saalisID'=>$resultRow['saalisid'],
                'pvm'=>$resultRow['pvm'],
                'kellonaika'=>$resultRow['kellonaika'],
                'kalalaji'=>$resultRow['kalalaji'],
                'lkm'=>$resultRow['lkm'],
                'pituus'=>$resultRow['pituus'],
                'paino'=>$resultRow['paino'],
                'vesisto'=>$resultRow['vesisto'],
                'paikka'=>$resultRow['paikka'],
                'tuulenVoimakkuus'=>$resultRow['tuulenvoimakkuus'],
                'ilmanLampo'=>$resultRow['ilmanlampo'],
                'vedenLampo'=>$resultRow['vedenlampo'],
                'pilvisyys'=>$resultRow['pilvisyys'],
                'huomiot'=>$resultRow['huomiot'],
                'saaliskuva'=>$resultRow['saaliskuva'],
                'pyydysID'=>$resultRow['pyydysid'],
                'pyydystyyppi'=>$resultRow['tyyppi'],
                'pyydysmalli'=>$resultRow['malli'],
                'pyydyskoko'=>$resultRow['koko'],
                'pyydysvari'=>$resultRow['vari'],   
            ));
            
            return $catch;
        }
        
        return null;
    }
}

