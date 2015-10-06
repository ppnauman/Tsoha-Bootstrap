-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Kalastaja (kayttajatunnus, etunimi, sukunimi, salasana, email) VALUES('kalakalle', 'Kalle', 'Parviainen', 'salasana', 'kalle.parviainen@kalakalle.fi');
INSERT INTO Kalastaja (kayttajatunnus, etunimi, sukunimi, salasana, email) VALUES('kämy', 'Jouni', 'Kämäräinen', 'salasana', 'jouni.kämäräinen@kajaani.fi');
INSERT INTO Kalastaja (kayttajatunnus, etunimi, sukunimi, salasana, email) VALUES('urkki', 'Urho', 'Kekkonen', 'salasana', 'urho.kekkonen@maalaisliitto.fi');
INSERT INTO Pyydys (omistaja,tyyppi,malli,koko,vari, kaytossa) VALUES('kalakalle','Uistin','Nils Master Invincible', '15 cm', 'Papukaija', 'TRUE');
INSERT INTO Pyydys (omistaja,tyyppi,malli,koko,vari, kaytossa) VALUES('kämy','Perho','Super Pupa', 'Nro 22', 'musta-harmaa', 'TRUE');
INSERT INTO Saalistieto (pvm, kellonaika, vesisto, kalalaji, lkm, pituus, paino, vedenLampo, huomiot, pyydys, saaliskuva) VALUES('1979-10-13','12:30:00', 'Puruvesi','Järvitaimen','1','105','6.300','15','Huomioita Kalakallen testisaaliista max. 300 merkkiä.','1','http://www.kalasaalis.com/images/kalat/bimages/Jarvitaimen_3965_1226653436_1.jpg');
INSERT INTO Saalistieto (pvm, kellonaika, vesisto, paikka, kalalaji, lkm, pituus, paino, vedenLampo, huomiot, pyydys, saaliskuva) VALUES('2000-07-12', '23:07:12', 'Nuorttijoki','Hirvashauta','Järvitaimen','1','40','0.800','12','Huomioita Kämyn testisaaliista max. 300 merkkiä.','2','http://www.kolumbus.fi/webweaver/kuvat2/taimen.jpg');
INSERT INTO Pyydystaja (kalastaja, saalisid) VALUES('kalakalle','1');
INSERT INTO Pyydystaja (kalastaja, saalisid) VALUES('kämy','2');
INSERT INTO Kalakaveri (kalastaja, kaveri) VALUES('kämy','urkki');
INSERT INTO Kalakaveri (kalastaja, kaveri) VALUES('urkki','kämy');