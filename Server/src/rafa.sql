-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS rafa;

-- Usar la base de datos
USE rafa;

-- Crear la tabla "victimas"
CREATE TABLE victimas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    victima_key VARCHAR(50),
    victima_id VARCHAR(50)
);
