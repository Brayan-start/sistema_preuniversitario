-- ============================================
-- SISTEMA DE INSCRIPCIONES UPEA
-- Script de datos iniciales (PostgreSQL)
-- ============================================

-- Insertar administrador por defecto
-- Email: admin@upea.bo
-- Contraseña: admin123
INSERT INTO users (name, email, password, role, is_active, email_notifications, created_at, updated_at)
VALUES (
    'Administrador General',
    'admin@upea.bo',
    '$2y$12$LJ3m4ys3Lk0TSwHnbfOMiOXPm1Qlq5Gz0Pd0Cd0Xm1Qlq5Gz0Pd0C', -- hash de 'admin123'
    'administrador',
    true,
    true,
    NOW(),
    NOW()
);

-- Insertar curso por defecto
INSERT INTO cursos (nombre_curso, descripcion, fecha_inicio, fecha_fin, monto_arancel, cupos_disponibles, requisitos, horario, is_active, created_at, updated_at)
VALUES (
    'Preuniversitario Gestión II/2026',
    'Curso de nivelación para ingreso a la Carrera de Ingeniería de Sistemas.',
    '2026-08-01',
    '2026-12-15',
    500.00,
    200,
    '1. Fotocopia de CI. 2. Certificado de Bachillerato. 3. Fotografía 4x4 fondo azul.',
    NULL,
    true,
    NOW(),
    NOW()
);
