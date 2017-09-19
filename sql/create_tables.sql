CREATE TABLE Kayttaja(
	tunnus varchar(20) PRIMARY KEY,
	salasana varchar(20) NOT NULL,
	ika INTEGER,
	kuvaus varchar(360),
	admin boolean
);

CREATE TABLE Tyon_kohde(
	kuvaus varchar(30) PRIMARY KEY,
	tarkempi_kuvaus varchar(360),
	luotu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Tyokalu(
	kuvaus varchar(30) PRIMARY KEY,
	tarkempi_kuvaus varchar(360),
	luotu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Tyo(
	id SERIAL PRIMARY KEY,
	kohde varchar REFERENCES Tyon_kohde(kuvaus),
	tyokalu varchar REFERENCES Tyokalu(kuvaus),
	kuvaus varchar(30) NOT NULL,
	tarkempi_kuvaus varchar(360),
	tehty boolean DEFAULT FALSE,
	suoritusaika TIMESTAMP
);

CREATE TABLE KayttajanTyot(
	tekija varchar REFERENCES Kayttaja(tunnus),
	tyo SERIAL REFERENCES Tyo(id)
);