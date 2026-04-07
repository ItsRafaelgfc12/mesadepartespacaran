Create database	db_msdpacaran;
Use db_msdpacaran;

CREATE TABLE rol (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    rol VARCHAR(60),
    descripcion VARCHAR(150),
    estado INT
);

CREATE TABLE area (
    id_area INT PRIMARY KEY AUTO_INCREMENT,
    nombre_area VARCHAR(35),
    descripcion VARCHAR(255),
    estado INT
);

CREATE TABLE cargo (
    id_cargo INT PRIMARY KEY AUTO_INCREMENT,
    cargo VARCHAR(35),
    estado INT,
    descripcion VARCHAR(150),
    id_area int,
    FOREIGN KEY (id_area) REFERENCES area(id_area)
);

CREATE TABLE programa_estudio (
    id_programa_estudio INT PRIMARY KEY AUTO_INCREMENT,
    programa_estudio VARCHAR(40),
    descripcion VARCHAR(255),
    estado INT
);

CREATE TABLE ubigeo_peru_departments (
  id VARCHAR(2) PRIMARY KEY,
  name VARCHAR(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ubigeo_peru_provinces (
  id VARCHAR(4) PRIMARY KEY,
  name VARCHAR(45) NOT NULL,
  department_id VARCHAR(2),
  FOREIGN KEY (department_id)
    REFERENCES ubigeo_peru_departments(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ubigeo_peru_districts (
  id VARCHAR(6) PRIMARY KEY,
  name VARCHAR(45),
  province_id VARCHAR(4),
  department_id VARCHAR(2),
  FOREIGN KEY (province_id)
    REFERENCES ubigeo_peru_provinces(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (department_id)
    REFERENCES ubigeo_peru_departments(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE estado_usuario (
    id_estado INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50)
);

CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombres_usuario VARCHAR(75),
    apellidos_usuario VARCHAR(75),
    email_per VARCHAR(75),
    email_ins VARCHAR(75),
    id_rol INT NULL,
    celular_usuario VARCHAR(15),
    tipo_documento VARCHAR(25),
    numero_documento VARCHAR(15),
    id_estado INT,
    direccion_usuario VARCHAR(255),
    url_foto_usuario VARCHAR(255),
    url_dni_usuario VARCHAR(255),
    url_firma VARCHAR(255),
    id_dep VARCHAR(2),
    id_prov VARCHAR(4),
    id_dis VARCHAR(6),
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,    
    last_session DATETIME,
    contrasena VARCHAR(255),
    UNIQUE (email_per),
	UNIQUE (email_ins),
    FOREIGN KEY (id_estado) REFERENCES estado_usuario(id_estado),
	FOREIGN KEY (id_rol) REFERENCES rol(id_rol),
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

CREATE TABLE usuario_programa_estudio (
    id_usuario_programa INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_programa_estudio INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_programa_estudio) REFERENCES programa_estudio(id_programa_estudio)
);

CREATE TABLE tipo_documento (
    id_tipo INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50)
);

CREATE TABLE documento (
    id_documento INT PRIMARY KEY AUTO_INCREMENT,
	id_tipo INT NOT NULL,
    codigo_documento VARCHAR(50) UNIQUE NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    descripcion TEXT,
    sumilla VARCHAR(75),
    fundamento_pedido VARCHAR(256),
    codigo_modular VARCHAR(25),
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    lugar VARCHAR(40),
    id_usuario_emisor INT,
    id_area_origen INT,
    url_principal VARCHAR(255),
	estado ENUM('borrador','enviado','en_proceso','finalizado','archivado') 
	DEFAULT 'borrador',
    FOREIGN KEY (id_tipo) REFERENCES tipo_documento(id_tipo),
    FOREIGN KEY (id_usuario_emisor) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_area_origen) REFERENCES area(id_area)
);

CREATE TABLE documento_adjuntos (
    id_adjunto INT PRIMARY KEY AUTO_INCREMENT,
    id_documento INT NOT NULL,
    nombre VARCHAR(100),
    tipo VARCHAR(50),
    ruta_archivo VARCHAR(255) NOT NULL,
	nombre_original VARCHAR(150),
	extension VARCHAR(10),
	peso BIGINT,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)
);

CREATE TABLE documento_derivacion (
    id_derivacion INT PRIMARY KEY AUTO_INCREMENT,
    id_documento INT NOT NULL,
    tipo_destino ENUM('usuario','area','programa','rol','cargo') NOT NULL,
    id_destino INT NOT NULL,
    estado ENUM('pendiente','recibido','derivado','finalizado') DEFAULT 'pendiente',
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (id_documento, tipo_destino, id_destino),
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)
);

CREATE TABLE documento_historial (
    id_historial INT PRIMARY KEY AUTO_INCREMENT,
    id_documento INT NOT NULL,
    id_usuario INT NOT NULL,
    tipo_evento ENUM(
        'creado',
        'enviado',
        'recibido',
        'leido',
        'descargado',
        'derivado',
        'observado',
        'respondido',
        'archivado'
    ) NOT NULL,
    tipo_destino ENUM('usuario','area','programa','rol','cargo') NULL,
    id_destino INT NULL,
    observacion TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

-- DERIVACION
CREATE INDEX idx_doc_derivacion_doc ON documento_derivacion(id_documento);
CREATE INDEX idx_doc_derivacion_destino ON documento_derivacion(id_destino);

-- HISTORIAL
CREATE INDEX idx_doc_historial_doc ON documento_historial(id_documento);
CREATE INDEX idx_doc_historial_usuario ON documento_historial(id_usuario);


CREATE TABLE documento_archivo (
    id_archivo INT PRIMARY KEY AUTO_INCREMENT,
    id_documento INT NOT NULL,

    fecha_archivado DATETIME,
    mensaje VARCHAR(100),

    id_usuario INT,

    FOREIGN KEY (id_documento) REFERENCES documento(id_documento),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE producto(
	id_producto INT PRIMARY KEY AUTO_INCREMENT,
	nombre_producto VARCHAR(50),
    descripcion_producto VARCHAR(255)    
);

CREATE TABLE requerimiento_almacen (
    id_requerimiento_almacen INT PRIMARY KEY AUTO_INCREMENT,
    fecha_requerimiento DATETIME,
    motivo VARCHAR(256),
    id_area INT,
    id_usuario INT,
    FOREIGN KEY (id_area) REFERENCES area(id_area),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE detalle_requerimiento_almacen (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_requerimiento_almacen INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    descripcion VARCHAR(255),
    FOREIGN KEY (id_requerimiento_almacen) REFERENCES requerimiento_almacen(id_requerimiento_almacen),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

CREATE TABLE requerimiento_economico (
    id_requerimiento_economico INT PRIMARY KEY AUTO_INCREMENT,
    fecha_requerimiento_eco DATETIME,
    motivo VARCHAR(256),
    id_area INT,
    id_usuario INT,
    FOREIGN KEY (id_area) REFERENCES area(id_area),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE detalle_requerimiento_economico (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_requerimiento_economico INT NOT NULL,
    concepto VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255),
    cantidad INT DEFAULT 1,
    monto_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2),
    FOREIGN KEY (id_requerimiento_economico)
        REFERENCES requerimiento_economico(id_requerimiento_economico)
);

CREATE TABLE evento (
	id_evento INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100),
    descripcion VARCHAR(255),
    nombre_ubicacion VARCHAR(150),
    url_ubicacion VARCHAR(512),
    fecha DATETIME,
    url_imagen VARCHAR(255)
    );
    
CREATE TABLE evento_participante (
    id_evento_participante INT PRIMARY KEY AUTO_INCREMENT,
    id_evento INT NOT NULL,
    id_usuario INT NOT NULL,
    observacion VARCHAR(255),
    tipo_participacion ENUM('asistente','organizador','ponente','invitado') DEFAULT 'asistente',
    estado ENUM('pendiente','confirmado','asistio','no_asistio') DEFAULT 'pendiente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_evento) REFERENCES evento(id_evento),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    UNIQUE (id_evento, id_usuario)
);
	
CREATE INDEX idx_evento_participante_evento ON evento_participante(id_evento);

CREATE TABLE expediente (
    id_expediente INT PRIMARY KEY AUTO_INCREMENT,
    codigo_expediente VARCHAR(50) UNIQUE NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    tipo ENUM('privado','publico','compartido') DEFAULT 'privado',
    id_usuario_responsable INT NOT NULL,
    estado ENUM('activo','en_proceso','finalizado','archivado') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_responsable) REFERENCES usuario(id_usuario)
);

CREATE TABLE expediente_acceso (
    id_acceso INT PRIMARY KEY AUTO_INCREMENT,
    id_expediente INT NOT NULL,
    tipo_acceso ENUM('usuario','area','rol','cargo') NOT NULL,
    id_referencia INT NOT NULL,
    permiso ENUM('lectura','edicion','administrador') DEFAULT 'lectura',
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_expediente) REFERENCES expediente(id_expediente),
    UNIQUE (id_expediente, tipo_acceso, id_referencia)
);

CREATE INDEX idx_expediente_acceso_exp ON expediente_acceso(id_expediente);

CREATE TABLE expediente_historial (
    id_historial INT PRIMARY KEY AUTO_INCREMENT,
    id_expediente INT NOT NULL,
    id_usuario INT,
    tipo_evento ENUM(
        'creado',
        'modificado',
        'acceso_asignado',
        'acceso_revocado',
        'solicitud_acceso',
        'aprobado',
        'rechazado',
        'archivado'
    ) NOT NULL,
    observacion TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_expediente) REFERENCES expediente(id_expediente),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE expediente_solicitud (
    id_solicitud INT PRIMARY KEY AUTO_INCREMENT,
    id_expediente INT NOT NULL,
    id_usuario_solicitante INT NOT NULL,
    estado ENUM('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
    mensaje TEXT,
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_expediente) REFERENCES expediente(id_expediente),
    FOREIGN KEY (id_usuario_solicitante) REFERENCES usuario(id_usuario)
);

CREATE INDEX idx_expediente_solicitud_exp ON expediente_solicitud(id_expediente);

CREATE TABLE expediente_documento (
    id_documento INT PRIMARY KEY AUTO_INCREMENT,
    id_expediente INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    version_actual INT DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_expediente) REFERENCES expediente(id_expediente)
);

CREATE TABLE expediente_version (
    id_version INT PRIMARY KEY AUTO_INCREMENT,
    id_documento INT NOT NULL,
    version INT NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    nombre_original VARCHAR(150),
    extension VARCHAR(10),
    peso BIGINT,
    id_usuario INT NOT NULL,
    comentario TEXT,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_documento) REFERENCES expediente_documento(id_documento),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    UNIQUE (id_documento, version)
);

CREATE INDEX idx_expediente_doc ON expediente_documento(id_expediente);
CREATE INDEX idx_version_doc ON expediente_version(id_documento);
CREATE INDEX idx_historial_exp ON expediente_historial(id_expediente);

CREATE TABLE plantilla (
    id_plantilla INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    id_usuario INT NOT NULL,
    url_imagen VARCHAR(255),
    ruta_archivo VARCHAR(255) NOT NULL,
    estado ENUM('activo','inactivo') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE plantilla_acceso (
    id_acceso INT PRIMARY KEY AUTO_INCREMENT,
    id_plantilla INT NOT NULL,
    tipo_acceso ENUM('usuario','area','rol','cargo','publico') NOT NULL,
    id_referencia INT NULL,
    permiso ENUM('ver','usar','editar') DEFAULT 'ver',
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_plantilla) REFERENCES plantilla(id_plantilla),
    UNIQUE (id_plantilla, tipo_acceso, id_referencia)
);

CREATE INDEX idx_plantilla_usuario ON plantilla(id_usuario);
CREATE INDEX idx_plantilla_acceso ON plantilla_acceso(id_plantilla);