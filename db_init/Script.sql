SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS persones
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Donem permisos a l'usuari 'usuari' per accedir a la base de dades 'persones'
-- sinó, aquest usuari no podrà veure la base de dades i no podrà accedir a les taules
GRANT ALL PRIVILEGES ON persones.* TO 'usuari'@'%';
FLUSH PRIVILEGES;


-- Després de crear la base de dades, cal seleccionar-la per treballar-hi
USE persones;

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

INSERT INTO TIPO (nom) VALUES ('Redes');
INSERT INTO TIPO (nom) VALUES ('Hardware');

INSERT INTO DEPARTAMENT (nom) VALUES ('Català');
INSERT INTO DEPARTAMENT (nom) VALUES ('Tecnologia');

INSERT INTO TECNIC (nom) VALUES ('Carles');
INSERT INTO TECNIC (nom) VALUES ('Marta');

INSERT INTO INCIDENCIA (descripcio, id_dept,fecha,id_tipo) VALUES ('El ordinador no es conecta a Internet', 2, '2026-04-2', 1);
INSERT INTO INCIDENCIA (descripcio, id_dept,fecha,id_tipo) VALUES ('La impresora no funciona', 1, '2026-04-17', 2);

INSERT INTO ACTUACIO (id_incidencia, descripcio, fecha) VALUES (1, 'He canviat el cable Ethernet', '2026-04-5' );
INSERT INTO ACTUACIO (id_incidencia, descripcio, fecha) VALUES (2, 'Hola que tal ', '2026-04-20' );













