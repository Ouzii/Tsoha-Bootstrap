CREATE TABLE Account(
	username varchar(20) PRIMARY KEY,
	password varchar(20) NOT NULL,
	age INTEGER DEFAULT NULL,
	description varchar(360),
	admin boolean
);

CREATE TABLE WorkObject(
	id SERIAL PRIMARY KEY,
	description varchar(30),
	longer_description varchar(360),
	created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE WorkTool(
	id SERIAL PRIMARY KEY,
	description varchar(30),
	longer_description varchar(360),
	created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Work(
	id SERIAL PRIMARY KEY,
	object SERIAL REFERENCES WorkObject(id),
	tool SERIAL REFERENCES WorkTool(id),
	description varchar(30) NOT NULL,
	longer_description varchar(360),
	done boolean DEFAULT FALSE,
	completion_time TIMESTAMP
);

CREATE TABLE UsersWorks(
	username varchar REFERENCES Account(username),
	work SERIAL REFERENCES Work(id)
);