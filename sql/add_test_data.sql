INSERT INTO Kayttaja (tunnus, salasana, ika, kuvaus, admin)
VALUES ('Matti', 'salasana', 23, 'Matti Meikäläinen', TRUE);

INSERT INTO Kayttaja (tunnus, salasana, ika, kuvaus)
VALUES ('Maija', 'Kissa123', 24, 'Maija Meikäläinen');

INSERT INTO Tyon_kohde (kuvaus, tarkempi_kuvaus)
VALUES ('Jääkaappi', 'Keittiöstä löytyvä kaappi, joka pitää ruoat kylmänä.');

INSERT INTO Tyon_kohde (kuvaus, tarkempi_kuvaus)
VALUES ('Olohuone', 'Talon ensimmäisestä kerroksesta löytyvä olohuone.');

INSERT INTO Tyon_kohde (kuvaus, tarkempi_kuvaus)
VALUES ('Makuuhuone', 'Talon toisesta kerroksesta löytyvä huone, jossa on sänky. Käytetään pääasiallisesti nukkumiseen.');

INSERT INTO Tyokalu (kuvaus, tarkempi_kuvaus)
VALUES ('Rätti', 'Keittiön kaapista löytyvä punainen rätti. Käytetään pintojen pyyhkimiseen.');

INSERT INTO Tyokalu (kuvaus, tarkempi_kuvaus)
VALUES ('Imuri', 'Eteisen kaapista löytyvä Bosch-merkkinen imuri.');

INSERT INTO Tyokalu (kuvaus, tarkempi_kuvaus)
VALUES ('Moppi', 'Kodinhoitohuoneesta löytyvä moppi. Käytetään lattioiden pesuun.');

INSERT INTO Tyo (kohde, tyokalu, kuvaus, tarkempi_kuvaus, tehty, suoritusaika)
VALUES (1, 1, 'Siivoa jääkaappi rätillä.',
 'Keittiöstä löytyy sekä punainen rätti, että jääkaappi. Pyyhi jääkaapin pinnat rätillä.', TRUE, NOW());

INSERT INTO Tyo (kohde, tyokalu, kuvaus, tarkempi_kuvaus)
VALUES (2, 2, 'Imuroi olohuone.',
 'Imuroi olohuone imurilla. Imuri löytyy eteisen kaapista. Muista myös sohvan alta!');

INSERT INTO Tyo (kohde, tyokalu, kuvaus, tarkempi_kuvaus)
VALUES (3, 3, 'Pese makuuhuoneen lattiat.',
 'Moppi löytyy kodinhoitohuoneen kaapista. Muista pestä myös sängyn alta!');

INSERT INTO KayttajanTyot (tekija, tyo)
VALUES ('Matti', 1);

INSERT INTO KayttajanTyot (tekija, tyo) 
VALUES ('Maija', 1);

INSERT INTO KayttajanTyot (tekija, tyo)
VALUES ('Maija', 2);

INSERT INTO KayttajanTyot (tekija, tyo)
VALUES ('Matti', 3);