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
    fecha DATETIME ,
    id_tipo INT ,
    prioridad ENUM('baja','media','alta'),
    id_tecnic INT,
    fecha_fin DATETIME,
    FOREIGN KEY (id_dept) REFERENCES DEPARTAMENT(id_dept),
    FOREIGN KEY (id_tipo) REFERENCES TIPO(id_tipo),
    FOREIGN KEY (id_tecnic) REFERENCES TECNIC(id_tecnic)
);

CREATE TABLE ACTUACIO(
    id_actuacio INT AUTO_INCREMENT PRIMARY KEY,
    id_incidencia INT NOT NULL,
    descripcio VARCHAR(1000) NOT NULL,
    fecha DATETIME ,
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

INSERT INTO INCIDENCIA (descripcio, id_dept,fecha,id_tipo) VALUES ('El ordinador no es conecta a Internet', 2, '2026-04-2 00:00:00', 1);
INSERT INTO INCIDENCIA (descripcio, id_dept,fecha,id_tipo) VALUES ('La impresora no funciona', 1, '2026-04-17 00:00:00', 2);

INSERT INTO ACTUACIO (id_incidencia, descripcio, fecha) VALUES (1, 'He canviat el cable Ethernet', '2026-04-5 00:00:00' );
INSERT INTO ACTUACIO (id_incidencia, descripcio, fecha) VALUES (2, 'Hola que tal ', '2026-04-20 00:00:00' );


CREATE OR REPLACE VIEW vista_informe_tecnics AS
SELECT
    t.id_tecnic,
    t.nom AS nomTecnic,
    i.prioridad,
    i.id_incidencia,
    i.descripcio AS descripcioIncidencia,
    i.fecha AS dataInici,
    IFNULL(SUM(a.duracio), 0) AS tempsTotalDedicat
FROM TECNIC t
INNER JOIN INCIDENCIA i
    ON t.id_tecnic = i.id_tecnic
LEFT JOIN ACTUACIO a
    ON i.id_incidencia = a.id_incidencia
WHERE i.fecha_fin IS NULL
GROUP BY
    t.id_tecnic,
    t.nom,
    i.prioridad,
    i.id_incidencia,
    i.descripcio,
    i.fecha;

CREATE OR REPLACE VIEW vista_consum_departaments AS
SELECT
    d.id_dept,
    d.nom AS nomDepartament,
    COUNT(i.id_incidencia) AS nombreIncidencies,
    IFNULL(SUM(temps_per_incidencia.tempsTotal), 0) AS tempsTotalDedicat
FROM DEPARTAMENT d
LEFT JOIN INCIDENCIA i
    ON d.id_dept = i.id_dept
LEFT JOIN (
    SELECT
        id_incidencia,
        SUM(duracio) AS tempsTotal
    FROM ACTUACIO
    GROUP BY id_incidencia
) AS temps_per_incidencia
    ON i.id_incidencia = temps_per_incidencia.id_incidencia
GROUP BY
    d.id_dept,
    d.nom;








