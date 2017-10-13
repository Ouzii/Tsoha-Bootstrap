INSERT INTO Account (username, password, age, description, admin)
VALUES ('Matti', 'salasana', 23, 'Matti Meikäläinen', TRUE);

INSERT INTO Account (username, password, age, description)
VALUES ('Maija', 'Kissa123', 24, 'Maija Meikäläinen');

INSERT INTO WorkObject(description, longer_description)
VALUES ('Jääkaappi', 'Keittiöstä löytyvä kaappi, joka pitää ruoat kylmänä.');

INSERT INTO WorkObject(description, longer_description)
VALUES ('Olohuone', 'Talon ensimmäisestä kerroksesta löytyvä olohuone.');

INSERT INTO WorkObject(description, longer_description)
VALUES ('Makuuhuone', 'Talon toisesta kerroksesta löytyvä huone, jossa on sänky. Käytetään pääasiallisesti nukkumiseen.');

INSERT INTO WorkTool (description, longer_description)
VALUES ('Rätti', 'Keittiön kaapista löytyvä punainen rätti. Käytetään pintojen pyyhkimiseen.');

INSERT INTO WorkTool (description, longer_description)
VALUES ('Imuri', 'Eteisen kaapista löytyvä Bosch-merkkinen imuri.');

INSERT INTO WorkTool (description, longer_description)
VALUES ('Moppi', 'Kodinhoitohuoneesta löytyvä moppi. Käytetään lattioiden pesuun.');

INSERT INTO Work (object, tool, description, longer_description, done, completion_time)
VALUES (1, 1, 'Siivoa jääkaappi rätillä.',
 'Keittiöstä löytyy sekä punainen rätti, että jääkaappi. Pyyhi jääkaapin pinnat rätillä.', TRUE, NOW());

INSERT INTO Work (object, tool, description, longer_description)
VALUES (2, 2, 'Imuroi olohuone.',
 'Imuroi olohuone imurilla. Imuri löytyy eteisen kaapista. Muista myös sohvan alta!');

INSERT INTO Work (object, tool, description, longer_description)
VALUES (3, 3, 'Pese makuuhuoneen lattiat.',
 'Moppi löytyy kodinhoitohuoneen kaapista. Muista pestä myös sängyn alta!');

INSERT INTO UsersWorks (username, work)
VALUES ('Matti', 1);

INSERT INTO UsersWorks (username, work)
VALUES ('Maija', 1);

INSERT INTO UsersWorks (username, work)
VALUES ('Maija', 2);

INSERT INTO UsersWorks (username, work)
VALUES ('Matti', 3);