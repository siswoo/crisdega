DROP DATABASE IF EXISTS crisdega;
CREATE DATABASE crisdega;
USE crisdega;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
	id INT AUTO_INCREMENT,
	usuario VARCHAR(250) NOT NULL,
	password VARCHAR(250) NOT NULL,
	rol INT NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE usuarios CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO usuarios (usuario,password,rol) VALUES 
('admin','d964173dc44da83eeafa3aebbee9a1a0',1);

DROP TABLE IF EXISTS bodega;
CREATE TABLE bodega (
	id INT AUTO_INCREMENT,
	descripcion VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE bodega CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO bodega (descripcion) VALUES 
('171'),
('186'),
('187'),
('188'),
('189');

DROP TABLE IF EXISTS ubicacion;
CREATE TABLE ubicacion (
	id INT AUTO_INCREMENT,
	bodega_id INT NOT NULL,
	descripcion VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE ubicacion CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO ubicacion (bodega_id,descripcion) VALUES 
(1,'1-1-A-1-A'),
(1,'1-1-A-1-B'),
(1,'1-1-A-1-C');

DROP TABLE IF EXISTS conteo;
CREATE TABLE conteo (
	id INT AUTO_INCREMENT,
	descripcion VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE conteo CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO conteo (descripcion) VALUES 
('C1'),
('C2'),
('C3');

DROP TABLE IF EXISTS referencia;
CREATE TABLE referencia (
	id INT AUTO_INCREMENT,
	descripcion VARCHAR(250) NOT NULL,
	articulo VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE referencia CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO referencia (descripcion,articulo) VALUES 
('1008','Hola'),
('1009','Bien'),
('1010','platos');

DROP TABLE IF EXISTS inventario;
CREATE TABLE inventario (
	id INT AUTO_INCREMENT,
	ubicacion_id INT NOT NULL,
	conteo_id INT NOT NULL,
	referencia_id INT NOT NULL,
	cantidad INT NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE inventario CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO inventario (ubicacion_id,conteo_id,referencia_id,cantidad) VALUES 
(1,1,1,100),
(2,2,2,200),
(3,3,3,300);
