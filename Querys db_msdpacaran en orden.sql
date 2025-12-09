Create database	db_msdpacaran;
Use db_msdpacaran;

CREATE TABLE rol (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    rol VARCHAR(60),
    descripcion VARCHAR(150)
);

CREATE TABLE area (
    id_area INT PRIMARY KEY AUTO_INCREMENT,
    area VARCHAR(35)
);

CREATE TABLE cargo (
    id_cargo INT PRIMARY KEY AUTO_INCREMENT,
    cargo VARCHAR(35)
);

CREATE TABLE programa_estudio (
    id_programa_estudio INT PRIMARY KEY AUTO_INCREMENT,
    programa_estudio VARCHAR(40)
);

CREATE TABLE distrito (
  `id_distrito` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
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


CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombres_usuario VARCHAR(75),
    apellidos_usuario VARCHAR(75),
    email_per VARCHAR(75),
    email_ins VARCHAR(75),
    id_rol INT,
    id_area INT,
    celular_usuario INT,
    tipo_documento VARCHAR(25),
    numero_documento INT,
    id_estado INT,
    direccion_usuario VARCHAR(50),
    url_foto_usuario VARCHAR(50),
    url_dni_usuario VARCHAR(50),
    url_firma VARCHAR(50),
    id_dep VARCHAR(2),
    id_prov VARCHAR(4),
    id_dis VARCHAR(6),
    created_at DATETIME,
    last_session DATETIME,
    FOREIGN KEY (id_rol) REFERENCES rol(id_rol),
    FOREIGN KEY (id_area) REFERENCES area(id_area),
    FOREIGN KEY (id_dep) REFERENCES ubigeo_peru_departments(id),
    FOREIGN KEY (id_prov) REFERENCES ubigeo_peru_provinces(id),
    FOREIGN KEY (id_dis) REFERENCES ubigeo_peru_districts(id)
);


CREATE TABLE usuario_cargo (
    id_usuario_cargo INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_cargo INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo)
);
-- Version mejorada:
CREATE TABLE usuario_cargo (
    id_usuario INT NOT NULL,
    id_cargo INT NOT NULL,
    PRIMARY KEY (id_usuario, id_cargo),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo) ON DELETE CASCADE
);

CREATE TABLE usuario_programa_estudio (
    id_usuario_programa INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_programa_estudio INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_programa_estudio) REFERENCES programa_estudio(id_programa_estudio)
);

CREATE TABLE fut (
    id_fut INT PRIMARY KEY AUTO_INCREMENT,
    sumilla VARCHAR(75),
    id_area INT,
    codigo_modular VARCHAR(25),
    id_usuario INT,
    fundamento_pedido VARCHAR(256),
    lugar VARCHAR(40),
    fecha DATETIME,
    firma_usuario VARCHAR(100),
    correlativo INT,
    FOREIGN KEY (id_area) REFERENCES area(id_area),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE detalle_fut (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_fut INT,
    nombre_documento VARCHAR(100),
    tipo_documento VARCHAR(50),
    archivo VARCHAR(255),
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_fut) REFERENCES fut(id_fut)
);

CREATE TABLE historial_derivacion (
    id_historial INT PRIMARY KEY AUTO_INCREMENT,
    id_fut INT,
    id_usuario_origen INT,
    id_usuario_destino INT,
    fecha_derivacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    observacion TEXT,
    FOREIGN KEY (id_fut) REFERENCES fut(id_fut),
    FOREIGN KEY (id_usuario_origen) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_usuario_destino) REFERENCES usuario(id_usuario)
);

CREATE TABLE requerimiento_almacen (
    id_requerimiento INT PRIMARY KEY AUTO_INCREMENT,
    fecha_requerimiento DATETIME,
    motivo VARCHAR(256),
    id_area INT,
    id_usuario INT,
    FOREIGN KEY (id_area) REFERENCES area(id_area),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE documento (
    id_documento INT PRIMARY KEY AUTO_INCREMENT,
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
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
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
    FOREIGN KEY (id_programa_destino) REFERENCES programa_estudio(id_programa_estudio),
    FOREIGN KEY (id_rol_destino) REFERENCES rol(id_rol),
    FOREIGN KEY (id_cargo_destino) REFERENCES cargo(id_cargo)
);

CREATE TABLE seguimiento_documento (
    id_seguimiento INT PRIMARY KEY AUTO_INCREMENT,
    id_detalle INT NOT NULL,
    id_usuario_accion INT NOT NULL,
    tipo_accion ENUM('enviado', 'leído', 'descargado', 'respondido', 'derivado', 'observado') NOT NULL,
    fecha_accion DATETIME DEFAULT CURRENT_TIMESTAMP,
    observacion TEXT,
    FOREIGN KEY (id_detalle) REFERENCES detalle_documento(id_detalle),
    FOREIGN KEY (id_usuario_accion) REFERENCES usuario(id_usuario)
);

CREATE TABLE archivo (
    id_archivo INT PRIMARY KEY AUTO_INCREMENT,
    id_documento INT NOT NULL,
    fecha_archivado DATETIME,
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)
);
