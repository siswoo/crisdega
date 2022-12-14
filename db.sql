DROP DATABASE IF EXISTS crisdega;
CREATE DATABASE crisdega;
USE crisdega;

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

