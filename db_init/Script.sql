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

CREATE TABLE USERS(
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    pass VARCHAR(100) NOT NULL,
    rol ENUM('user','tecnic','admin'),
    id_tecnic INT,
    FOREIGN KEY (id_tecnic) REFERENCES TECNIC(id_tecnic)
);



    
-- TIPUS
INSERT INTO TIPO (nom) VALUES 
('Xarxes'),
('Hardware'),
('Software'),
('Seguretat');

-- DEPARTAMENTS
INSERT INTO DEPARTAMENT (nom) VALUES 
('Català'),
('Tecnologia'),
('Administració'),
('Direcció');

-- TÈCNICS
INSERT INTO TECNIC (nom) VALUES 
('Carles'),
('Marta'),
('Joan'),
('Laura');

INSERT INTO INCIDENCIA (descripcio, id_dept, fecha, id_tipo, prioridad, id_tecnic, fecha_fin) VALUES
('No hi ha connexió a Internet', 2, '2026-04-02 08:00:00', 1, 'alta', 1, NULL),
('La impressora no imprimeix', 1, '2026-04-17 09:30:00', 2, 'media', 2, '2026-04-18 12:00:00'),
('Error en iniciar sessió al sistema', 3, '2026-04-10 10:15:00', 3, 'alta', 3, NULL),
('Ordinador molt lent', 2, '2026-04-12 11:00:00', 2, 'baja', 4, '2026-04-13 16:00:00'),
('Possible virus detectat', 4, '2026-04-20 08:45:00', 4, 'alta', 1, NULL);

INSERT INTO ACTUACIO (id_incidencia, descripcio, fecha, finalitzat, visible, duracio) VALUES
(1, 'Revisió del router', '2026-04-02 08:30:00', 1, 0, 30),
(1, 'Canvi de cable Ethernet', '2026-04-02 09:30:00', 1, 1, 45),

(2, 'Reinstal·lació de drivers', '2026-04-17 10:00:00', 1, 0, 60),

(3, 'Reset de contrasenya', '2026-04-10 10:30:00', 1, 1, 20),
(3, 'Revisió servidor autenticació', '2026-04-10 11:00:00', 0, 0, 40),

(4, 'Neteja de fitxers temporals', '2026-04-12 11:30:00', 1, 1, 50),

(5, 'Anàlisi antivirus', '2026-04-20 09:00:00', 0, 1, 70),
(5, 'Eliminació malware', '2026-04-20 10:30:00', 0, 0, 90);


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








