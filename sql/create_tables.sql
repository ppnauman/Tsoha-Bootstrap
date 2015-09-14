-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Kalastaja(
    kayttajatunnus VARCHAR(32) PRIMARY KEY,
    etunimi VARCHAR(32) NOT NULL,
    sukunimi VARCHAR(32) NOT NULL,
    salasana VARCHAR(32) NOT NULL,
    email VARCHAR(32) NOT NULL
);

CREATE TABLE Kalakaveri(
    kalastaja VARCHAR(32) REFERENCES Kalastaja(kayttajatunnus),
    kaveri VARCHAR(32) REFERENCES Kalastaja(kayttajatunnus)
);

CREATE TABLE Pyydys(
    pyydysID SERIAL PRIMARY KEY,
    omistaja VARCHAR(32) REFERENCES Kalastaja(kayttajatunnus),
    tyyppi VARCHAR(32) NOT NULL,
    malli VARCHAR(32),
    koko VARCHAR(16),
    vari VARCHAR(32)
);

CREATE TABLE Saalistieto(
    saalisID SERIAL PRIMARY KEY,
    pvm DATE NOT NULL,
    kellonaika TIME,
    kalalaji VARCHAR(32) NOT NULL,
    pituus DECIMAL(4,1),
    paino DECIMAL(4,3),
    vesisto VARCHAR(32),
    paikka VARCHAR(64),
    tuulenVoimakkuus VARCHAR(32),
    tuulenSuunta VARCHAR(32),
    ilmanLampo DECIMAL(3,1),
    vedenLampo DECIMAL(3,1),
    pilvisyys VARCHAR(32),
    huomiot VARCHAR(300),
    saaliskuva VARCHAR(300),
    pyydys INTEGER REFERENCES Pyydys(pyydysID)
);

CREATE TABLE Pyydystaja(
    kalastaja VARCHAR(32) REFERENCES Kalastaja(kayttajatunnus),
    saalisID INTEGER REFERENCES Saalistieto(saalisID)
);


    
    
