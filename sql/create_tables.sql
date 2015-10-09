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
    vari VARCHAR(32),
    kaytossa BOOLEAN
);

CREATE TABLE Saalistieto(
    saalisID SERIAL PRIMARY KEY,
    pvm DATE NOT NULL,
    kellonaika TIME,
    kalalaji VARCHAR(32) NOT NULL,
    lkm INTEGER NOT NULL,
    pituus VARCHAR(5),
    paino VARCHAR(5),
    vesisto VARCHAR(32),
    paikka VARCHAR(64),
    tuulenVoimakkuus VARCHAR(32),
    tuulenSuunta VARCHAR(32),
    ilmanLampo VARCHAR(4),
    vedenLampo VARCHAR(4),
    pilvisyys VARCHAR(32),
    huomiot VARCHAR(300),
    saaliskuva VARCHAR(300),
    pyydys INTEGER REFERENCES Pyydys(pyydysID)
);

CREATE TABLE Pyydystaja(
    kalastaja VARCHAR(32) REFERENCES Kalastaja(kayttajatunnus),
    saalisID INTEGER REFERENCES Saalistieto(saalisID)
);
