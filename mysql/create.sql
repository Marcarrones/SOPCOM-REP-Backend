# Repository
CREATE TABLE repository_status (
    id INT AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE,
    PRIMARY KEY(id)
);

CREATE TABLE repository (
    id VARCHAR (50),
    name VARCHAR(100),
    description VARCHAR(200),
    status INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (status)
        REFERENCES repository_status(id)
        ON DELETE NO ACTION,
);

#Method Element

CREATE TABLE method_element_type (
    id INT AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE method_element (
    id VARCHAR(50),
    name VARCHAR(100) NOT NULL,
    abstract BIT(1) NOT NULL,
    description VARCHAR(200),
    figure VARCHAR(100),
    type INT,
    repository VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (type)
        REFERENCES method_element_type(id)
        ON DELETE NO ACTION,
    FOREIGN KEY (repository)
        REFERENCES repository(id)
        ON DELETE NO ACTION
);

CREATE TABLE tool (
    id VARCHAR(50),
    PRIMARY KEY (id),
    FOREIGN KEY (id) 
		REFERENCES method_element(id)
        ON DELETE CASCADE
);

CREATE TABLE artefact (
    id VARCHAR(50),
    PRIMARY KEY (id),
    FOREIGN KEY (id) 
		REFERENCES method_element(id)
        ON DELETE CASCADE
);

CREATE TABLE activity (
    id VARCHAR(50),
    PRIMARY KEY (id),
    FOREIGN KEY (id) 
		REFERENCES method_element(id)
        ON DELETE CASCADE
);

CREATE TABLE role (
    id VARCHAR(50),
    PRIMARY KEY (id),
    FOREIGN KEY (id) 
		REFERENCES method_element(id)
        ON DELETE CASCADE
);

<<<<<<< Updated upstream
=======
CREATE TABLE map (
    id VARCHAR(50),
    name VARCHAR(50) NOT NULL,
    repository VARCHAR(50) NOT NULL,
    pruebas json,
    PRIMARY KEY (id),
    FOREIGN KEY (repository)
        REFERENCES repository(id)
        ON DELETE NO ACTION
);

>>>>>>> Stashed changes
CREATE TABLE goal (
    id INT AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE method_chunk (
    id VARCHAR(50),
    name VARCHAR(100) NOT NULL,
    description VARCHAR(200) NOT NULL,
    activity VARCHAR(50) UNIQUE,
<<<<<<< Updated upstream
    intention INT NOT NULL,
=======
    intention INT,
    strategy VARCHAR(50),
    repository VARCHAR(50) NOT NULL,
>>>>>>> Stashed changes
    PRIMARY KEY (id),
    FOREIGN KEY (activity) 
		REFERENCES activity(id)
        ON DELETE NO ACTION,
<<<<<<< Updated upstream
	FOREIGN KEY (intention)
		REFERENCES goal(id)
=======
    FOREIGN KEY (intention)
        REFERENCES goal(id)
        ON DELETE SET NULL,
    FOREIGN KEY (strategy)
        REFERENCES strategy(id)
        ON DELETE SET NULL,
    FOREIGN KEY (repository)
        REFERENCES repository(id)
>>>>>>> Stashed changes
        ON DELETE NO ACTION
);

CREATE TABLE method_chunk_uses_tool(
    idMC VARCHAR(50),
    idME VARCHAR(50),
    PRIMARY KEY (idMC, idME),
    FOREIGN KEY (idMC) 
		REFERENCES method_chunk(id)
        ON DELETE CASCADE,
    FOREIGN KEY (idME) 
		REFERENCES tool(id)
        ON DELETE CASCADE
);

CREATE TABLE method_chunk_produces_artefact(
    idMC VARCHAR(50),
    idME VARCHAR(50),
    PRIMARY KEY (idMC, idME),
    FOREIGN KEY (idMC) 
		REFERENCES method_chunk(id)
        ON DELETE CASCADE,
    FOREIGN KEY (idME) 
		REFERENCES artefact(id)
        ON DELETE CASCADE
);

CREATE TABLE method_chunk_consumes_artefact(
    idMC VARCHAR(50),
    idME VARCHAR(50),
    PRIMARY KEY (idMC, idME),
    FOREIGN KEY (idMC) 
		REFERENCES method_chunk(id)
        ON DELETE CASCADE,
    FOREIGN KEY (idME) 
		REFERENCES artefact(id)
        ON DELETE CASCADE
);

CREATE TABLE method_chunk_includes_role(
    idMC VARCHAR(50),
    idME VARCHAR(50),
    isSet BIT(1),
    PRIMARY KEY (idMC, idME),
    FOREIGN KEY (idMC) 
		REFERENCES method_chunk(id)
        ON DELETE CASCADE,
    FOREIGN KEY (idME) 
		REFERENCES role(id)
        ON DELETE CASCADE
);

CREATE TABLE  criterion (
    id INT AUTO_INCREMENT,
    name VARCHAR(100),
    repository VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE(name, repository),
    FOREIGN KEY (repository)
        REFERENCES repository(id)
        ON DELETE NO ACTION
);

CREATE TABLE value (
    id INT AUTO_INCREMENT,
    name VARCHAR(50),
    criterion INT,
    UNIQUE (name, criterion),
    PRIMARY KEY (id),
    FOREIGN KEY (criterion)
		REFERENCES criterion(id)
        ON DELETE CASCADE
);

CREATE TABLE assign_method_chunk (
    idMC VARCHAR(50),
    criterion INT,
    PRIMARY KEY (idMC, criterion),
    FOREIGN KEY (idMC)
		REFERENCES method_chunk(id)
        ON DELETE CASCADE,
	FOREIGN KEY (criterion)
		REFERENCES criterion(id)
        ON DELETE CASCADE
);

CREATE TABLE assign_method_chunk_value (
    idMC VARCHAR(50),
    criterion INT REFERENCES Criterion, 
    value INT REFERENCES Value,
    PRIMARY KEY(idMC, criterion, value),
    FOREIGN KEY (idMC, criterion)
		REFERENCES assign_method_chunk(idMC, criterion)
        ON DELETE CASCADE,
	FOREIGN KEY (value)
		REFERENCES value(id)
        ON DELETE CASCADE
);

CREATE TABLE struct_rel_type (
    id INT AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE,
    PRIMARY KEY(id)
);

CREATE TABLE activity_rel_type (
    id INT AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE,
    PRIMARY KEY(id)
);

CREATE TABLE artefact_rel_type (
    id INT AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE,
     PRIMARY KEY(id)
);

CREATE TABLE me_rel (
    fromME VARCHAR(50),
    toME VARCHAR(50),
    PRIMARY KEY (fromME, toME),
    FOREIGN KEY (fromME)
		REFERENCES method_element(id)
        ON DELETE CASCADE,
	FOREIGN KEY (toME)
		REFERENCES method_element(id)
        ON DELETE CASCADE
);

CREATE TABLE me_struct_rel (
    fromME VARCHAR(50),
    toME VARCHAR(50),
    rel INT,
    PRIMARY KEY (fromME, toME),
    FOREIGN KEY (fromME, toME)
		REFERENCES me_rel(fromME, toME)
        ON DELETE CASCADE,
	FOREIGN KEY (rel)
		REFERENCES struct_rel_type(id)
);

CREATE TABLE activity_rel (
    fromME VARCHAR(50),
    toME VARCHAR(50),
    rel INT,
    PRIMARY KEY (fromME, toME),
    FOREIGN KEY (fromME, toME)
		REFERENCES me_rel(fromME, toME)
        ON DELETE CASCADE,
	FOREIGN KEY (rel)
		REFERENCES activity_rel_type(id),
    FOREIGN KEY (fromME)
		REFERENCES activity(id)
        ON DELETE CASCADE,
	FOREIGN KEY (toME)
		REFERENCES activity(id)
        ON DELETE CASCADE
);

CREATE TABLE artefact_rel (
    fromME VARCHAR(50),
    toME VARCHAR(50),
    rel INT,
    PRIMARY KEY (fromME, toME),
    FOREIGN KEY (fromME, toME)
		REFERENCES me_rel(fromME, toME)
        ON DELETE CASCADE,
	FOREIGN KEY (rel)
		REFERENCES artefact_rel_type(id),
    FOREIGN KEY (fromME)
		REFERENCES artefact(id)
        ON DELETE CASCADE,
	FOREIGN KEY (toME)
		REFERENCES artefact(id)
        ON DELETE CASCADE
);

CREATE TABLE chunk_rel (
    fromMC VARCHAR(50),
    toMC VARCHAR(50),
    fromME VARCHAR(50),
	toME VARCHAR(50),
    PRIMARY KEY (fromMC, toMC),
    FOREIGN KEY (fromMC)
		REFERENCES method_chunk(id)
        ON DELETE CASCADE,
	FOREIGN KEY (toMC)
		REFERENCES method_chunk(id)
        ON DELETE CASCADE,
	FOREIGN KEY (fromME, toME)
		REFERENCES me_rel(fromME, toME)
        ON DELETE CASCADE
);