-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Kayttaja(
	tunnus varchar(10) PRIMARY KEY,
	salasana varchar(20) NOT NULL,
	ika INTEGER,
	kuvaus varchar(240),
	admin boolean
);

CREATE TABLE Tyon_kohde(
	kuvaus varchar(30) PRIMARY KEY,
	tarkempi_kuvaus varchar(240),
	luotu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Tyokalu(
	kuvaus varchar(30) PRIMARY KEY,
	tarkempi_kuvaus varchar(240),
	luotu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Tyo(
	id SERIAL PRIMARY KEY,
	kohde varchar REFERENCES Tyon_kohde(kuvaus),
	tyokalu varchar REFERENCES Tyokalu(kuvaus),
	kuvaus varchar(30) NOT NULL,
	tarkempi_kuvaus varchar(240),
	tehty boolean DEFAULT FALSE,
	suoritusaika TIMESTAMP
);

CREATE TABLE KayttajanTyot(
	tekija varchar REFERENCES Kayttaja(tunnus),
	tyo SERIAL REFERENCES Tyo(id)
);