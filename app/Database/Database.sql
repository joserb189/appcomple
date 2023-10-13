DROP DATABASE IF EXISTS act_complementaria; /*Elimina la base de datos si es que existe*/
CREATE DATABASE act_complementaria; /*si no existe la base de datos la crea*/
USE act_complementaria; /* Usa la base de datos que se encuentra*/
#Tabla departamentos con sus respectivos atributos

CREATE TABLE IF NOT EXISTS deptos(
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(75) NOT NULL,
    PRIMARY KEY (id)
);

#Tabla de tipos de actividades complementarias 
CREATE TABLE IF NOT EXISTS tipos_actividades(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    max_creditos INT NOT NULL
);

#Tabla de Evidencias a presentar 
CREATE TABLE IF NOT EXISTS evds_presentar(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(250) NOT NULL
);

#Tabla de periodos
CREATE TABLE IF NOT EXISTS periodos(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    mes_ini VARCHAR(50) NOT NULL,
    mes_fin VARCHAR(50) NOT NULL,
    anio VARCHAR(50) NOT NULL,
    status BOOLEAN NOT NULL
);

#Tabla de Jefes de departamentos con sus respectivos atributos y su relacion
CREATE TABLE IF NOT EXISTS jdepto(
    rfc VARCHAR(20) NOT NULL UNIQUE PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(200) NOT NULL,
    clave VARCHAR(20) NOT NULL,
    fecha_ingreso TIMESTAMP NOT NULL,
    fecha_termina TIMESTAMP NULL,
    status BOOLEAN NOT NULL,
    departamento INT NOT NULL,
    FOREIGN KEY (departamento) REFERENCES deptos(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

#Tabla de Carreras con sus respectivos atributos y su relacion
CREATE TABLE IF  NOT EXISTS carreras(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    nombre_corto VARCHAR(20) NOT NULL,
    jdepto VARCHAR(20) NOT NULL,
    FOREIGN KEY (jdepto) REFERENCES jdepto(rfc)
    ON UPDATE CASCADE ON DELETE CASCADE
);

#Tabla de alumnos cons sus respectivos atributos y su relacion 
CREATE TABLE IF NOT EXISTS alumnos(
    no_control VARCHAR(50) PRIMARY KEY NOT NULL,
	nombre VARCHAR(120) NOT NULL,
	a_paterno VARCHAR(100) NOT NULL,
	a_materno VARCHAR(100) NOT NULL,
    nip VARCHAR(4) NOT NULL,
    carrera INT NOT NULL,
    FOREIGN KEY (carrera) REFERENCES carreras(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

#Tabla de actividades complementarias con sus respectivos atributos y su relacion
CREATE TABLE IF NOT EXISTS act_complementarias(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    act_gnral VARCHAR(50) NOT NULL,
    act_especifica VARCHAR(50) NOT NULL,
    credito INT NOT NULL,
    lugar VARCHAR(100) NOT NULL,
    num_participantes INT NOT NULL,
    tiempo VARCHAR(50) NOT NULL,
    descripcion VARCHAR(200) NULL,
    tipo INT NOT NULL,
    FOREIGN KEY (tipo) REFERENCES tipos_actividades(id)
	ON UPDATE CASCADE ON DELETE CASCADE
);

#Tabla de M:M Evidencias comprobatorias 
CREATE TABLE IF NOT EXISTS evd_comprobatorias(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    evd_presentar INT NOT NULL,
    act_complementaria INT NOT NULL,
    FOREIGN KEY (evd_presentar) REFERENCES evds_presentar(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (act_complementaria) REFERENCES act_complementarias(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

#Tabla de M:M solicitud de actividad complementaria
CREATE TABLE IF NOT EXISTS solicitud(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    status BOOLEAN NOT NULL,
    observacion VARCHAR(250) NULL,
    created_at TIMESTAMP NOT NULL,
    valor_numerico DECIMAL(2,2) NOT NULL,
    periodo INT NOT NULL,
    jdepto VARCHAR(20) NOT NULL,
    alumno VARCHAR(50) NOT NULL,
    act_complementaria INT NOT NULL,
    FOREIGN KEY (act_complementaria) REFERENCES act_complementarias(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (jdepto) REFERENCES jdepto(rfc)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (periodo) REFERENCES periodos(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (alumno) REFERENCES alumnos(no_control)
    ON UPDATE CASCADE ON DELETE CASCADE
);
