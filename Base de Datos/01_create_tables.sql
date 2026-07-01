-- ============================================
-- SISTEMA DE INSCRIPCIONES UPEA
-- Script de creación de tablas (PostgreSQL)
-- ============================================

-- Eliminar tablas existentes (si existe la base de datos)
DROP TABLE IF EXISTS email_logs CASCADE;
DROP TABLE IF EXISTS auditoria CASCADE;
DROP TABLE IF EXISTS pagos CASCADE;
DROP TABLE IF EXISTS documentos CASCADE;
DROP TABLE IF EXISTS inscripciones CASCADE;
DROP TABLE IF EXISTS cursos CASCADE;
DROP TABLE IF EXISTS aspirantes CASCADE;
DROP TABLE IF EXISTS failed_jobs CASCADE;
DROP TABLE IF EXISTS job_batches CASCADE;
DROP TABLE IF EXISTS jobs CASCADE;
DROP TABLE IF EXISTS cache_locks CASCADE;
DROP TABLE IF EXISTS cache CASCADE;
DROP TABLE IF EXISTS sessions CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ============================================
-- TABLA: users
-- ============================================
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) DEFAULT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'aspirante' CHECK (role IN ('aspirante', 'administrador')),
    is_active BOOLEAN NOT NULL DEFAULT true,
    profile_photo_path VARCHAR(255) DEFAULT NULL,
    email_notifications BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
);

-- ============================================
-- TABLA: password_reset_tokens
-- ============================================
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
);

-- ============================================
-- TABLA: sessions
-- ============================================
CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id BIGINT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX sessions_user_id_index ON sessions (user_id);
CREATE INDEX sessions_last_activity_index ON sessions (last_activity);

-- ============================================
-- TABLA: cache
-- ============================================
CREATE TABLE cache (
    key VARCHAR(255) NOT NULL PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);
CREATE INDEX cache_expiration_index ON cache (expiration);

-- ============================================
-- TABLA: cache_locks
-- ============================================
CREATE TABLE cache_locks (
    key VARCHAR(255) NOT NULL PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);
CREATE INDEX cache_locks_expiration_index ON cache_locks (expiration);

-- ============================================
-- TABLA: jobs
-- ============================================
CREATE TABLE jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER DEFAULT NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX jobs_queue_index ON jobs (queue);

-- ============================================
-- TABLA: job_batches
-- ============================================
CREATE TABLE job_batches (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT DEFAULT NULL,
    cancelled_at INTEGER DEFAULT NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER DEFAULT NULL
);

-- ============================================
-- TABLA: failed_jobs
-- ============================================
CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLA: aspirantes
-- ============================================
CREATE TABLE aspirantes (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    ci VARCHAR(20) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    celular VARCHAR(20) NOT NULL,
    colegio_procedencia VARCHAR(150) NOT NULL,
    anio_egreso INTEGER NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    CONSTRAINT aspirantes_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- ============================================
-- TABLA: cursos
-- ============================================
CREATE TABLE cursos (
    id BIGSERIAL PRIMARY KEY,
    nombre_curso VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    monto_arancel NUMERIC(10, 2) NOT NULL,
    cupos_disponibles INTEGER NOT NULL,
    requisitos TEXT NOT NULL,
    horario VARCHAR(255) DEFAULT NULL,
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
);

-- ============================================
-- TABLA: inscripciones
-- ============================================
CREATE TABLE inscripciones (
    id BIGSERIAL PRIMARY KEY,
    aspirante_id BIGINT NOT NULL,
    curso_id BIGINT NOT NULL,
    estado VARCHAR(255) NOT NULL DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'en_revision', 'aprobado', 'rechazado')),
    grupo VARCHAR(50) DEFAULT NULL,
    motivo_rechazo TEXT DEFAULT NULL,
    fecha_cambio_estado TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    admin_responsable_id BIGINT DEFAULT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    CONSTRAINT inscripciones_aspirante_id_foreign FOREIGN KEY (aspirante_id) REFERENCES aspirantes (id),
    CONSTRAINT inscripciones_curso_id_foreign FOREIGN KEY (curso_id) REFERENCES cursos (id),
    CONSTRAINT inscripciones_admin_responsable_id_foreign FOREIGN KEY (admin_responsable_id) REFERENCES users (id)
);

-- ============================================
-- TABLA: documentos
-- ============================================
CREATE TABLE documentos (
    id BIGSERIAL PRIMARY KEY,
    aspirante_id BIGINT NOT NULL,
    inscripcion_id BIGINT NOT NULL,
    tipo VARCHAR(255) NOT NULL CHECK (tipo IN ('ci', 'certificado_bachillerato', 'fotografia')),
    archivo_path VARCHAR(255) NOT NULL,
    formato VARCHAR(255) NOT NULL CHECK (formato IN ('pdf', 'jpg', 'jpeg')),
    estado VARCHAR(255) NOT NULL DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'aprobado', 'rechazado')),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    CONSTRAINT documentos_aspirante_id_foreign FOREIGN KEY (aspirante_id) REFERENCES aspirantes (id),
    CONSTRAINT documentos_inscripcion_id_foreign FOREIGN KEY (inscripcion_id) REFERENCES inscripciones (id)
);

-- ============================================
-- TABLA: pagos
-- ============================================
CREATE TABLE pagos (
    id BIGSERIAL PRIMARY KEY,
    inscripcion_id BIGINT NOT NULL UNIQUE,
    numero_comprobante VARCHAR(100) NOT NULL,
    comprobante_path VARCHAR(255) NOT NULL,
    monto NUMERIC(10, 2) NOT NULL,
    fecha_pago DATE NOT NULL,
    estado VARCHAR(255) NOT NULL DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'en_revision', 'aprobado', 'rechazado')),
    motivo_rechazo TEXT DEFAULT NULL,
    admin_id BIGINT DEFAULT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    CONSTRAINT pagos_inscripcion_id_foreign FOREIGN KEY (inscripcion_id) REFERENCES inscripciones (id),
    CONSTRAINT pagos_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES users (id)
);

-- ============================================
-- TABLA: auditoria
-- ============================================
CREATE TABLE auditoria (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    accion VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT auditoria_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id)
);

-- ============================================
-- TABLA: email_logs
-- ============================================
CREATE TABLE email_logs (
    id BIGSERIAL PRIMARY KEY,
    destinatario VARCHAR(255) NOT NULL,
    asunto VARCHAR(255) NOT NULL,
    tipo_evento VARCHAR(255) NOT NULL,
    estado_envio VARCHAR(255) NOT NULL DEFAULT 'pendiente' CHECK (estado_envio IN ('enviado', 'fallido', 'pendiente')),
    mensaje_error TEXT DEFAULT NULL,
    usuario_responsable_id BIGINT DEFAULT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    CONSTRAINT email_logs_usuario_responsable_id_foreign FOREIGN KEY (usuario_responsable_id) REFERENCES users (id) ON DELETE SET NULL
);
