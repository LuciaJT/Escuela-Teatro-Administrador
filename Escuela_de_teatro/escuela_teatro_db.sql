CREATE DATABASE IF NOT EXISTS escuela_teatro_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE escuela_teatro_db;

SET default_storage_engine = InnoDB;

CREATE TABLE usuario (
    id            VARCHAR(20)  PRIMARY KEY,        
    email         VARCHAR(200) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol           ENUM('alumno', 'profesor', 'admin') NOT NULL
);


CREATE TABLE profesor (
    usuario_id  VARCHAR(20)  PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    apellidos   VARCHAR(150) NOT NULL,
    telefono    VARCHAR(20),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);


CREATE TABLE admin (
    usuario_id  VARCHAR(20)  PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    apellidos   VARCHAR(150) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);


CREATE TABLE alumno (
    id                  INT          AUTO_INCREMENT PRIMARY KEY,
    usuario_id          VARCHAR(20)  UNIQUE,
    nombre              VARCHAR(100) NOT NULL,
    apellidos           VARCHAR(150) NOT NULL,
    email               VARCHAR(200),
    telefono            VARCHAR(20),
    estado              ENUM('posible', 'matriculado', 'baja') NOT NULL DEFAULT 'posible',
    fecha_registro      DATE         NOT NULL DEFAULT (CURRENT_DATE),

    nivel               ENUM('iniciacion', 'intermedio', 'avanzado'),
    fecha_interes       DATE,
    tipo_interes        ENUM(
                            'no_insistir',
                            'avisar_sep2026',
                            'avisar_ene2027',
                            'ex_alumno',
                            'sin_horario',
                            'intensivo',
                            'no_vive_madrid'
                        ),
    clase_prueba        BOOLEAN      DEFAULT FALSE,

    fecha_primera_clase DATE,

    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE SET NULL
);


CREATE TABLE horario_posible (
    id            INT  AUTO_INCREMENT PRIMARY KEY,
    alumno_id     INT  NOT NULL,
    dia_semana    ENUM(
                      'lunes','martes','miercoles',
                      'jueves','viernes','sabado','domingo'
                  ) NOT NULL,
    tramo_horario ENUM(
                      '11-13','16-18','18-20','20-22',
                      'fds_11-13','fds_17-19'
                  ) NOT NULL,
    FOREIGN KEY (alumno_id) REFERENCES alumno(id) ON DELETE CASCADE
);


CREATE TABLE sala (
    id             INT          AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(100) NOT NULL,          
    espacio_nombre VARCHAR(100) NOT NULL,          
    direccion      VARCHAR(200) NOT NULL,          
    tipo           ENUM('aula', 'sala_representacion') NOT NULL DEFAULT 'aula',
    aforo_maximo   INT          NOT NULL DEFAULT 0
);


CREATE TABLE grupo (
    id                 INT          AUTO_INCREMENT PRIMARY KEY,
    profesor_id        VARCHAR(20)  NOT NULL,
    sala_id            INT          NOT NULL,
    nombre             VARCHAR(150) NOT NULL,
    dia_semana         ENUM(
                           'lunes','martes','miercoles',
                           'jueves','viernes','sabado','domingo'
                       ) NOT NULL,
    hora_inicio        TIME         NOT NULL,
    hora_fin           TIME         NOT NULL,
    nivel              ENUM('iniciacion','intermedio','avanzado') NOT NULL,
    tipo               ENUM('teatro','improvisacion')             NOT NULL,
    curso              VARCHAR(50),
    fecha_inicio_curso DATE         NOT NULL,
    fecha_fin_curso    DATE         NOT NULL,
    activo             BOOLEAN      DEFAULT TRUE,
    FOREIGN KEY (profesor_id) REFERENCES profesor(usuario_id),
    FOREIGN KEY (sala_id)     REFERENCES sala(id)
);


CREATE TABLE alumno_grupo (
    id                INT  AUTO_INCREMENT PRIMARY KEY,
    alumno_id         INT  NOT NULL,
    grupo_id          INT  NOT NULL,
    fecha_inscripcion DATE NOT NULL DEFAULT (CURRENT_DATE),
    activo            BOOLEAN DEFAULT TRUE,
    UNIQUE KEY uk_alumno_grupo (alumno_id, grupo_id),
    FOREIGN KEY (alumno_id) REFERENCES alumno(id),
    FOREIGN KEY (grupo_id)  REFERENCES grupo(id)
);


CREATE TABLE clase (
    id          INT  AUTO_INCREMENT PRIMARY KEY,
    grupo_id    INT  NOT NULL,
    sala_id     INT  NOT NULL,
    fecha       DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin    TIME NOT NULL,
    cupo_maximo INT  NOT NULL,
    estado      ENUM('programada','cancelada','realizada') DEFAULT 'programada',
    FOREIGN KEY (grupo_id) REFERENCES grupo(id),
    FOREIGN KEY (sala_id)  REFERENCES sala(id)
);


CREATE TABLE asistencia (
    id           INT  AUTO_INCREMENT PRIMARY KEY,
    alumno_id    INT  NOT NULL,
    clase_id     INT  NOT NULL,
    estado       ENUM('asiste','ausente','avisado') NOT NULL,
    fecha_aviso  DATETIME,
    aviso_valido BOOLEAN DEFAULT FALSE,
    UNIQUE KEY uk_asistencia (alumno_id, clase_id),
    FOREIGN KEY (alumno_id) REFERENCES alumno(id),
    FOREIGN KEY (clase_id)  REFERENCES clase(id)
);


CREATE TABLE token (
    id                   INT  AUTO_INCREMENT PRIMARY KEY,
    alumno_id            INT  NOT NULL,
    asistencia_origen_id INT  NOT NULL,
    fecha_generacion     DATE NOT NULL DEFAULT (CURRENT_DATE),
    fecha_caducidad      DATE NOT NULL,
    usado                BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (alumno_id)            REFERENCES alumno(id),
    FOREIGN KEY (asistencia_origen_id) REFERENCES asistencia(id)
);


CREATE TABLE recuperacion (
    id                    INT      AUTO_INCREMENT PRIMARY KEY,
    alumno_id             INT      NOT NULL,
    token_id              INT      NOT NULL,
    clase_origen_id       INT      NOT NULL,
    clase_recuperacion_id INT      NOT NULL,
    estado                ENUM('pendiente','confirmada','realizada','cancelada') DEFAULT 'pendiente',
    fecha_solicitud       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (alumno_id)             REFERENCES alumno(id),
    FOREIGN KEY (token_id)              REFERENCES token(id),
    FOREIGN KEY (clase_origen_id)       REFERENCES clase(id),
    FOREIGN KEY (clase_recuperacion_id) REFERENCES clase(id)
);


CREATE TABLE bloque_pago (
    id           INT          AUTO_INCREMENT PRIMARY KEY,
    alumno_id    INT          NOT NULL,
    grupo_id     INT          NOT NULL,
    descripcion  VARCHAR(200),
    fecha_inicio DATE         NOT NULL,
    fecha_fin    DATE         NOT NULL,
    importe      DECIMAL(8,2) NOT NULL,
    pagado       BOOLEAN      DEFAULT FALSE,
    fecha_pago   DATE,
    FOREIGN KEY (alumno_id) REFERENCES alumno(id),
    FOREIGN KEY (grupo_id)  REFERENCES grupo(id)
);


CREATE TABLE evento_grupal (
    id             INT          AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(200) NOT NULL,
    tipo           ENUM('intensivo','salida_teatro') NOT NULL,
    descripcion    TEXT,
    fecha          DATE         NOT NULL,
    plazas_maximas INT,
    sala_id        INT,
    FOREIGN KEY (sala_id) REFERENCES sala(id)
);


CREATE TABLE inscripcion_evento (
    id                INT      AUTO_INCREMENT PRIMARY KEY,
    alumno_id         INT      NOT NULL,
    evento_id         INT      NOT NULL,
    fecha_inscripcion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado            ENUM('inscrito','cancelado') DEFAULT 'inscrito',
    UNIQUE KEY uk_inscripcion_evento (alumno_id, evento_id),
    FOREIGN KEY (alumno_id) REFERENCES alumno(id),
    FOREIGN KEY (evento_id) REFERENCES evento_grupal(id)
);


