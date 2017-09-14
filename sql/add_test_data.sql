-- Lisää INSERT INTO lauseet tähän tiedostoon

INSERT INTO Kayttaja (tunnus, salasana, ika, kuvaus, admin)
VALUES ('Matti', 'salasana', 23, 'Matti Meikäläinen', TRUE);

INSERT INTO Kayttaja (tunnus, salasana, ika, kuvaus)
VALUES ('Maija', 'Kissa123', 24, 'Maija Meikäläinen');

INSERT INTO Tyon_kohde (kuvaus, tarkempi_kuvaus)
VALUES ('Jääkaappi', 'Keittiöstä löytyvä kaappi, joka pitää ruoat kylmänä.');

INSERT INTO Tyon_kohde (kuvaus, tarkempi_kuvaus)
VALUES ('Olohuone', 'Talon ensimmäisestä kerroksesta löytyvä olohuone.');

INSERT INTO Tyokalu (kuvaus, tarkempi_kuvaus)
VALUES ('Rätti', 'Keittiön kaapista löytyvä punainen rätti. Käytetään pintojen pyyhkimiseen.');

INSERT INTO Tyokalu (kuvaus, tarkempi_kuvaus)
VALUES ('Imuri', 'Eteisen kaapista löytyvä Bosch-merkkinen imuri.');

INSERT INTO Tyo (kohde, tyokalu, kuvaus, tarkempi_kuvaus, tehty, suoritusaika)
VALUES ('Jääkaappi', 'Rätti', 'Siivoa jääkaappi rätillä.',
 'Keittiöstä löytyy sekä punainen rätti, että jääkaappi. Pyyhi jääkaapin pinnat rätillä.', TRUE, NOW());

INSERT INTO Tyo (kohde, tyokalu, kuvaus, tarkempi_kuvaus)
VALUES ('Olohuone', 'Imuri', 'Imuroi olohuone.',
 'Imuroi olohuone imurilla. Imuri löytyy eteisen kaapista. Muista myös sohvan alta!');

INSERT INTO KayttajanTyot (tekija, tyo)
VALUES ('Matti', 1);

INSERT INTO KayttajanTyot (tekija, tyo)
VALUES ('Maija', 2);