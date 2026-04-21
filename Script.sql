-- ------------------------
-- TAULES
-- ------------------------
CREATE TABLE TIPO (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL);

CREATE TABLE DEPARTAMENT (
    id_dept INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL);

CREATE TABLE TECNIC (
    id_tecnic INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL);


CREATE TABLE INCIDENCIA(
    id_incidencia INT AUTO_INCREMENT PRIMARY KEY,
    descripcio VARCHAR(1000) NOT NULL,
    id_dept INT NOT NULL,
    fecha DATE  NOT NULL ,
    id_tipo INT NOT NULL,
    prioridad ENUM('baja','media','alta'),
    id_tecnic INT,
    fecha_fin DATE,
    FOREIGN KEY (id_dept) REFERENCES DEPARTAMENT(id_dept),
    FOREIGN KEY (id_tipo) REFERENCES TIPO(id_tipo),
    FOREIGN KEY (id_tecnic) REFERENCES TECNIC(id_tecnic)
);

CREATE TABLE ACTUACIO(
    id_actuacio INT AUTO_INCREMENT PRIMARY KEY,
    id_incidencia INT NOT NULL,
    descripcio VARCHAR(1000) NOT NULL,
    fecha DATE  NOT NULL,
    finalitzat INT,
    visible INT,
    duracio INT,
    FOREIGN KEY (id_incidencia) REFERENCES INCIDENCIA(id_incidencia));






