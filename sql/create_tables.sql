CREATE TABLE Kayttaja(
	tunnus varchar(20) PRIMARY KEY,
	salasana varchar(20) NOT NULL,
	ika INTEGER DEFAULT NULL,
	kuvaus varchar(360),
	admin boolean
);

CREATE TABLE Tyon_kohde(
	id SERIAL PRIMARY KEY,
	kuvaus varchar(30),
	tarkempi_kuvaus varchar(360),
	luotu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Tyokalu(
	id SERIAL PRIMARY KEY,
	kuvaus varchar(30),
	tarkempi_kuvaus varchar(360),
	luotu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Tyo(
	id SERIAL PRIMARY KEY,
	kohde SERIAL REFERENCES Tyon_kohde(id),
	tyokalu SERIAL REFERENCES Tyokalu(id),
	kuvaus varchar(30) NOT NULL,
	tarkempi_kuvaus varchar(360),
	tehty boolean DEFAULT FALSE,
	suoritusaika TIMESTAMP
);

CREATE TABLE KayttajanTyot(
	tekija varchar REFERENCES Kayttaja(tunnus),
	tyo SERIAL REFERENCES Tyo(id)
);