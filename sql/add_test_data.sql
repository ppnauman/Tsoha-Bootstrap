-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kalastaja(kayttajatunnus, etunimi, sukunimi, salasana, email) VALUES ('kalakalle', 'Kalle', 'Parviainen', 'salasana', 'kalle.parviainen@kalakalle.fi');
INSERT INTO Pyydys(omistaja,tyyppi,malli,koko,vari) VALUES ('kalakalle','uistin','nils master invincible', '15 cm', 'papukaija');
INSERT INTO Saalistieto(pvm,kalalaji,pituus,huomiot,pyydys) VALUES('1979-10-13','järvitaimen','105','testisaalis','1');
INSERT INTO Pyydystaja(kalastaja, saalisID) VALUES('kalakalle','1');
