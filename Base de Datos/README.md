# Base de Datos - Sistema de Inscripciones UPEA

Este directorio contiene los scripts SQL necesarios para crear y restaurar la base de datos del proyecto.

## Archivos

- **01_create_tables.sql**: Crea todas las tablas necesarias para el funcionamiento del sistema (usuarios, aspirantes, cursos, inscripciones, documentos, pagos, auditoría, etc.).
- **02_seed_data.sql**: Inserta los datos iniciales (administrador por defecto y curso de ejemplo).
- **03_drop_tables.sql**: Elimina todas las tablas de la base de datos (útil para reiniciar la base de datos).

## Requisitos

- PostgreSQL 15 o superior.

## Cómo ejecutar los scripts

### Opción 1: Usando psql (línea de comandos)

```bash
# Crear la base de datos (si no existe)
psql -U postgres -c "CREATE DATABASE sistema_inscripciones_upea;"

# Ejecutar el script de creación de tablas
psql -U postgres -d sistema_inscripciones_upea -f "01_create_tables.sql"

# Ejecutar el script de datos iniciales
psql -U postgres -d sistema_inscripciones_upea -f "02_seed_data.sql"
```

### Opción 2: Usando pgAdmin (interfaz gráfica)

1. Abrir pgAdmin y conectarse al servidor PostgreSQL.
2. Crear una nueva base de datos llamada `sistema_inscripciones_upea`.
3. Abrir la herramienta "Query Tool" para la base de datos creada.
4. Abrir y ejecutar el archivo `01_create_tables.sql`.
5. Abrir y ejecutar el archivo `02_seed_data.sql`.

## Credenciales por defecto

- **Administrador**: admin@upea.bo / admin123

## Notas

- Los scripts están diseñados para PostgreSQL. Si se utiliza otro motor de base de datos, es necesario realizar las adaptaciones correspondientes.
- Se recomienda ejecutar estos scripts únicamente en entornos de desarrollo o para la configuración inicial del proyecto.
- Para entornos de producción, utilizar el sistema de migraciones de Laravel (`php artisan migrate`).
