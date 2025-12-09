Create database	db_msdpacaran;
Use db_msdpacaran;

create table rol (
id_rol INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
rol varchar(60),
descripcion varchar(150)
);
	
create table area(
id_area INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
area varchar(35)
);

CREATE TABLE usuario_cargo (
id_usuario_cargo INT PRIMARY KEY AUTO_INCREMENT,
id_usuario INT NOT NULL,
id_cargo INT NOT NULL,
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo)
);

CREATE TABLE usuario_programa_estudio (
    id_usuario_programa INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_programa_estudio INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_programa_estudio) REFERENCES programa_estudio(id_programa_estudio)
);

create table fut(
id_fut INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
sumilla varchar(75),
id_area INT(11),
codigo_modular varchar(25),
id_usuario INT(11),
fundamento_pedido varchar (256),
lugar varchar(40),
fecha datetime,
firma_usuario varchar(100),
correlativo INT(11)
);

CREATE TABLE detalle_fut (
  id_detalle INT AUTO_INCREMENT PRIMARY KEY,
  id_fut INT(11),
  nombre_documento VARCHAR(100),
  tipo_documento VARCHAR(50),
  archivo VARCHAR(255),
  fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_fut) REFERENCES fut(id_fut)
);

CREATE TABLE historial_derivacion (
  id_historial INT AUTO_INCREMENT PRIMARY KEY,
  id_fut INT(11),
  id_usuario_origen INT(11),
  id_usuario_destino INT(11),
  fecha_derivacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  observacion TEXT,
  FOREIGN KEY (id_fut) REFERENCES fut(id_fut),
  FOREIGN KEY (id_usuario_origen) REFERENCES usuario(id_usuario),
  FOREIGN KEY (id_usuario_destino) REFERENCES usuario(id_usuario)
);

create table usuario (
id_usuario INT(11) AUTO_INCREMENT PRIMARY KEY,
nombres_usuario varchar(75),
apellidos_usuario varchar(75),
email_per varchar(75),
email_ins varchar(75),
id_rol INT(11),
id_area INT(11),
celular_usuario INT(11),
tipo_documento varchar(25),
numero_documento INT(11),
id_estado INT(11),
direccion_usuario varchar(50), 
url_foto_usuario varchar(50), 
url_dni_usuario varchar(50), 
url_firma varchar(50),
id_dep INT(11),
id_prov INT(11),
id_dis INT(11),
created_at datetime,
last_session datetime,
FOREIGN KEY (id_rol) REFERENCES rol(id_rol),
FOREIGN KEY (id_area) REFERENCES area(id_area),
FOREIGN KEY (id_dep) REFERENCES departamento(id_departamento),
FOREIGN KEY (id_prov) REFERENCES provincia(id_provincia),
FOREIGN KEY (id_dis) REFERENCES distrito(id_distrito)
);

create table requerimiento_almacen(
id_requerimiento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
fecha_requerimiento datetime,
motivo varchar(256),
id_area INT(11),
id_usuario INT(11),
FOREIGN KEY (id_area) REFERENCES area(id_area),
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE documento (
  id_documento INT AUTO_INCREMENT PRIMARY KEY,
  codigo_documento VARCHAR(50) UNIQUE NOT NULL,
  asunto VARCHAR(255) NOT NULL,
  descripcion TEXT,
  fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
  id_usuario_emisor INT NOT NULL,
  tipo_envio ENUM('rol', 'area', 'programa', 'cargo', 'usuario') NOT NULL,
  url_doc VARCHAR(255),
  FOREIGN KEY (id_usuario_emisor) REFERENCES usuario(id_usuario)
);

CREATE TABLE detalle_documento (
  id_detalle INT AUTO_INCREMENT PRIMARY KEY,
  id_documento INT NOT NULL,
  id_usuario_destino INT NULL,
  id_area_destino INT NULL,
  id_programa_destino INT NULL,
  id_rol_destino INT NULL,
  id_cargo_destino INT NULL,
  estado ENUM('pendiente', 'leido', 'respondido') DEFAULT 'pendiente',
  fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_documento) REFERENCES documento(id_documento),
  FOREIGN KEY (id_usuario_destino) REFERENCES usuario(id_usuario),
  FOREIGN KEY (id_area_destino) REFERENCES area(id_area),
  FOREIGN KEY (id_programa_destino) REFERENCES programa_estudio(id_programa),
  FOREIGN KEY (id_rol_destino) REFERENCES rol(id_rol),
  FOREIGN KEY (id_cargo_destino) REFERENCES cargo(id_cargo)
);

CREATE TABLE seguimiento_documento (
  id_seguimiento INT AUTO_INCREMENT PRIMARY KEY,
  id_detalle INT NOT NULL,
  id_usuario_accion INT NOT NULL,
  tipo_accion ENUM('enviado', 'leído', 'descargado', 'respondido', 'derivado', 'observado') NOT NULL,
  fecha_accion DATETIME DEFAULT CURRENT_TIMESTAMP,
  observacion TEXT NULL,
  FOREIGN KEY (id_detalle) REFERENCES detalle_documento(id_detalle),
  FOREIGN KEY (id_usuario_accion) REFERENCES usuario(id_usuario)
);

CREATE TABLE archivo (
id_archivo INT AUTO_INCREMENT PRIMARY KEY,
id_documento INT NOT NULL,
fecha_archivado datetime
);

CREATE TABLE distrito (
  `id_dis` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `province_id` varchar(4) DEFAULT NULL,
  `department_id` varchar(2) DEFAULT NULL
);

CREATE TABLE provincia (
  `id_provincia` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(45) NOT NULL,
  `department_id` varchar(2) NOT NULL
);

CREATE TABLE departamento (
  `id_departamento` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(45) NOT NULL
);