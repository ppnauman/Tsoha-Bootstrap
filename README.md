# Tietokantasovelluksen esittelysivu

Paljon on tehty mutta vielä puuttuukin aika monta juttua, tulossa vielä ainakin
-saaliin muokkaus
-pyydysten lisäys/poisto/listaus
-saaliin lisäyksen validointeihin tarvii hieman tuunausta
-uloskirjautuminen ja kirjautumattoman käyttäjän estot
-dokumentointia + siistimistä

Yleisiä linkkejä

* [Kirjautuminen/rekisteröityminen(toimii ja ohjaa listaukseen/lisäykseen)](https://ppnauman.users.cs.helsinki.fi/fishingLog/login)
* [Saaliiden listaussivu](https://ppnauman.users.cs.helsinki.fi/fishingLog/catchList)
* [Saaliin esittelysivu id#1](https://ppnauman.users.cs.helsinki.fi/fishingLog/catchList/1)
* [Saaliin esittelysivu id#2](https://ppnauman.users.cs.helsinki.fi/fishingLog/catchList/2)
* [Saaliin lisäyssivu](https://ppnauman.users.cs.helsinki.fi/fishingLog/newCatch)


Sivujen alustavat suunnitelmat

* [kirjautumissivu](https://ppnauman.users.cs.helsinki.fi/fishingLog/kirjautuminen)
* [rekisteröitymissivu](https://ppnauman.users.cs.helsinki.fi/fishingLog/rekisteroityminen)
* [saaliiden listausnäkymä](https://ppnauman.users.cs.helsinki.fi/fishingLog/listaaSaaliit)
* [saaliin esittelynäkymä](https://ppnauman.users.cs.helsinki.fi/fishingLog/saalis)
* [saaliin lisäys(/muokkaus)näkymä](https://ppnauman.users.cs.helsinki.fi/fishingLog/lisaaSaalis)

Dokumentaatio

* [Linkki dokumentaatioon](https://github.com/ppnauman/Tsoha-Bootstrap/blob/master/doc/dokumentaatio.pdf)

## Työn aihe

### Kalamiehen saalispäiväkirja 

Saalispäiväkirja on yksinkertainen tietokantasovellus, jonka avulla kalastajat voivat pitää kirjaa niin saaliistaan kuin pyyntivälineistäänkin.Sovellus tarjoaa rekisteröityneille käyttäjille mahdollisuuden paitsi omien saalistietojen säilyttämiseen, myös niiden muokkaamiseen, tarkasteluun, monipuolisiin hakuihin sekä sovellusta käyttävien kalakaverien kirjaamien saaliiden tarkasteluun.  Harjoitustyössä sovellus toteutetaan Helsingin yliopiston tietojenkäsittelytieteen laitoksen users -palvelimella Apache -palvelimen alla. Sovellus on suunniteltu käyttämään PostgreSQL -tietokantaa ja toteutusympäristössä tulee olla tuki PHP -ohjelmointikielelle. Lisäksi asiakkaan selainohjelmassa tulee olla JavaScript -tuki.
