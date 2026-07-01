# Sistema de Inscripciones UPEA

Sistema web para la gestión de inscripciones a cursos preuniversitarios de la Universidad Pública de El Alto (UPEA). Permite a los aspirantes registrarse, inscribirse en cursos, subir documentación y realizar pagos; y a los administradores gestionar cursos, revisar inscripciones, verificar documentos y pagos, y generar reportes.

## Tecnologías utilizadas

### Backend
- **Laravel 12** - Framework PHP
- **PHP 8.2+** - Lenguaje de programación
- **PostgreSQL 15+** - Base de datos relacional
- **JWT Auth (tymon/jwt-auth)** - Autenticación mediante tokens JWT
- **DomPDF (barryvdh/laravel-dompdf)** - Generación de reportes PDF
- **Maatwebsite Excel** - Exportación de reportes a Excel
- **Brevo API** - Envío de correos electrónicos transaccionales
- **Laravel Queue** - Cola de trabajos para envío de correos
- **Laravel Tinker** - Consola interactiva de Laravel

### Frontend
- **Blade** - Motor de plantillas de Laravel
- **Bootstrap 5.3** - Framework CSS (vía CDN)
- **Tailwind CSS 4** - Framework CSS utilitario (vía Vite)
- **Vite** - Empaquetador de assets
- **Font Awesome 6** - Iconos (vía CDN)
- **AOS (Animate On Scroll)** - Animaciones (vía CDN)
- **SweetAlert2** - Notificaciones y modales
- **Axios** - Cliente HTTP para JavaScript
- **Vue.js** - Componente de KPIs del dashboard

## Requisitos previos

- PHP 8.2 o superior
- Composer 2.x
- Node.js 18 o superior
- npm 9 o superior
- PostgreSQL 15 o superior
- Extensión `pdo_pgsql` y `pgsql` habilitadas en PHP

## Instalación de dependencias

```bash
# Clonar el repositorio
git clone https://github.com/Brayan-start/sistema_preuniversitario.git
cd sistema_preuniversitario

# Instalar dependencias de PHP (Composer)
composer install

# Instalar dependencias de JavaScript (Node.js)
npm install
```

## Configuración del archivo .env

```bash
# Copiar el archivo de configuración de entorno
cp .env.example .env
```

Editar el archivo `.env` con los valores correspondientes:

```env
APP_NAME="Sistema de Inscripciones UPEA"
APP_ENV=local
APP_KEY=  # Se genera automáticamente con php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

# Base de datos (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sistema_inscripciones_upea
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña

# Sesión y cola
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

# Correo (Brevo/Sendinblue)
MAIL_FROM_ADDRESS="no-reply@upea.bo"
MAIL_FROM_NAME="Sistema de Inscripciones UPEA"
BREVO_API_KEY=tu_api_key_de_brevo

# JWT
JWT_SECRET=  # Se genera con php artisan jwt:secret
JWT_TTL=60
JWT_ALGO=HS256

# Almacenamiento (Cloudflare R2 opcional)
FILESYSTEM_DISK=local
# Para usar R2:
# FILESYSTEM_DISK=r2
# R2_ACCESS_KEY_ID=...
# R2_SECRET_ACCESS_KEY=...
# R2_BUCKET=...
# R2_ENDPOINT=...
```

Luego generar las claves:

```bash
php artisan key:generate
php artisan jwt:secret
```

## Configuración e importación de la base de datos

### Opción 1: Usando migraciones de Laravel (recomendado)

```bash
# Crear la base de datos en PostgreSQL
psql -U postgres -c "CREATE DATABASE sistema_inscripciones_upea;"

# Ejecutar migraciones
php artisan migrate

# (Opcional) Poblar con datos de ejemplo
php artisan db:seed
```

### Opción 2: Usando scripts SQL

Ejecutar los scripts ubicados en la carpeta `Base de Datos/` en el siguiente orden:

```bash
psql -U postgres -d sistema_inscripciones_upea -f "Base de Datos/01_create_tables.sql"
psql -U postgres -d sistema_inscripciones_upea -f "Base de Datos/02_seed_data.sql"
```

## Cómo ejecutar el backend

```bash
# Iniciar el servidor de desarrollo de Laravel
php artisan serve

# En una terminal aparte, iniciar el worker de colas (para envío de correos)
php artisan queue:listen --tries=1 --timeout=0

# En una terminal aparte, mostrar logs en tiempo real (opcional)
php artisan pail --timeout=0
```

El servidor estará disponible en `http://localhost:8000`.

## Cómo ejecutar el frontend

```bash
# Compilar assets con Vite (modo desarrollo)
npm run dev
```

Para compilar para producción:

```bash
npm run build
```

## Cómo desplegar y ejecutar el proyecto localmente paso a paso

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/Brayan-start/sistema_preuniversitario.git
   cd sistema_preuniversitario
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de JavaScript**
   ```bash
   npm install
   ```

4. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   ```
   Editar `.env` con los datos de conexión a PostgreSQL y demás configuraciones.

5. **Generar claves**
   ```bash
   php artisan key:generate
   php artisan jwt:secret
   ```

6. **Crear la base de datos**
   ```bash
   psql -U postgres -c "CREATE DATABASE sistema_inscripciones_upea;"
   ```

7. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

8. **Crear enlace simbólico para almacenamiento**
   ```bash
   php artisan storage:link
   ```

9. **Compilar assets del frontend**
   ```bash
   npm run build
   ```

10. **Iniciar el servidor**
    ```bash
    php artisan serve
    ```

11. **Iniciar el worker de colas** (en otra terminal)
    ```bash
    php artisan queue:listen --tries=1 --timeout=0
    ```

12. **Abrir el navegador** en `http://localhost:8000`

### Credenciales por defecto

- **Administrador**: admin@upea.bo / admin123

## Comandos útiles

### Ejecutar todo en modo desarrollo (servidor, colas, logs y Vite)

```bash
composer run dev
```

### Ejecutar pruebas

```bash
composer run test
```

### Configuración rápida del proyecto (desde cero)

```bash
composer run setup
```

## Estructura del proyecto

```
sistema_laravel/
├── app/
│   ├── Actions/Admin/           # Acciones del panel administrativo
│   │   ├── GenerateExcelReportAction.php
│   │   ├── GeneratePdfReportAction.php
│   │   └── RecordAuditAction.php
│   ├── Console/Commands/        # Comandos personalizados de Artisan
│   │   ├── DatabaseBackup.php
│   │   └── TestBrevoApi.php
│   ├── DTOs/Admin/              # Objetos de transferencia de datos
│   │   ├── DateRangeFilterData.php
│   │   └── SearchFilterData.php
│   ├── Exports/                 # Exportaciones a Excel/PDF
│   │   ├── AdminReportExport.php
│   │   └── InscripcionesExport.php
│   ├── Http/
│   │   ├── Controllers/         # Controladores
│   │   │   ├── AccountController.php
│   │   │   ├── AdminController.php
│   │   │   ├── AspiranteController.php
│   │   │   ├── AuditoriaController.php
│   │   │   ├── AuthController.php
│   │   │   ├── CursoController.php
│   │   │   ├── DocumentoController.php
│   │   │   ├── InscripcionController.php
│   │   │   ├── PagoController.php
│   │   │   ├── ReporteController.php
│   │   │   └── UsuarioController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php  # Middleware para roles
│   ├── Mail/                    # Clases de correo electrónico
│   │   ├── AdminNuevoRegistroMail.php
│   │   ├── BienvenidaMail.php
│   │   ├── DocumentosObservados.php
│   │   ├── ForgotPasswordMail.php
│   │   ├── InscripcionEstadoCambiado.php
│   │   ├── PagoAprobado.php
│   │   ├── PagoRechazado.php
│   │   └── PagoRecibidoMail.php
│   ├── Models/                  # Modelos Eloquent
│   │   ├── Aspirante.php
│   │   ├── Auditoria.php
│   │   ├── Curso.php
│   │   ├── Documento.php
│   │   ├── EmailLog.php
│   │   ├── Inscripcion.php
│   │   ├── Pago.php
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   ├── Repositories/Admin/      # Repositorios
│   │   ├── AspiranteRepository.php
│   │   ├── AuditRepository.php
│   │   ├── MetricsRepository.php
│   │   └── ReportRepository.php
│   └── Services/                # Servicios
│       ├── Admin/
│       │   ├── AuditService.php
│       │   ├── DashboardService.php
│       │   ├── InterpretationService.php
│       │   ├── ReportService.php
│       │   ├── SearchService.php
│       │   └── StatisticsService.php
│       ├── BrevoService.php
│       └── NotificationEmailService.php
├── Base de Datos/               # Scripts SQL para la base de datos
│   ├── 01_create_tables.sql
│   ├── 02_seed_data.sql
│   ├── 03_drop_tables.sql
│   └── README.md
├── bootstrap/
│   └── app.php
├── config/                      # Archivos de configuración
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── database/
│   ├── migrations/              # Migraciones de la base de datos
│   └── seeders/                 # Seeders (datos de ejemplo)
│       ├── AdminSeeder.php
│       ├── CursoSeeder.php
│       └── DatabaseSeeder.php
├── public/
│   └── index.php
├── resources/
│   ├── css/app.css              # Estilos con Tailwind CSS
│   ├── js/                      # JavaScript (Vite)
│   │   ├── admin/
│   │   │   ├── audit.js
│   │   │   ├── dashboard.js
│   │   │   ├── search.js
│   │   │   └── statistics.js
│   │   ├── components/
│   │   │   └── DashboardKpis.vue
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/                   # Plantillas Blade
│       ├── account/
│       ├── admin/
│       ├── aspirante/
│       ├── auth/
│       ├── components/
│       ├── emails/
│       ├── layouts/
│       ├── reports/
│       └── welcome.blade.php
├── routes/
│   ├── api.php                  # Rutas de la API REST
│   ├── console.php              # Rutas de consola (Artisan)
│   └── web.php                  # Rutas web (Blade)
├── scripts/
│   └── 00-laravel-deploy.sh     # Script de despliegue para Render
├── storage/                     # Archivos de almacenamiento
├── tests/                       # Pruebas automatizadas
├── .env.example                 # Plantilla de configuración de entorno
├── composer.json                # Dependencias de PHP
├── package.json                 # Dependencias de JavaScript
├── Dockerfile                   # Configuración de Docker para Render
├── nginx.conf                   # Configuración de Nginx para Render
└── vite.config.js               # Configuración de Vite
```

## Observaciones importantes

1. **Base de datos**: El sistema utiliza PostgreSQL. Asegúrese de tener la extensión `pdo_pgsql` habilitada en PHP. La configuración de la base de datos se realiza en el archivo `.env`.

2. **Cola de trabajos**: El envío de correos electrónicos se procesa mediante colas (Queue). Es necesario ejecutar `php artisan queue:listen` para que los correos se envíen correctamente.

3. **Almacenamiento de archivos**: Los documentos y comprobantes de pago se pueden almacenar localmente o en Cloudflare R2 (compatible con S3). La configuración se define mediante la variable `FILESYSTEM_DISK` en el archivo `.env`.

4. **Correos electrónicos**: El sistema utiliza la API de Brevo (anteriormente Sendinblue) para el envío de correos transaccionales. Es necesario configurar la clave de API de Brevo en la variable `BREVO_API_KEY`.

5. **Assets**: El frontend utiliza Vite como empaquetador. Para desarrollo, ejecutar `npm run dev`. Para producción, ejecutar `npm run build`.

6. **Autenticación**: El sistema utiliza JWT para la API REST y sesiones de Laravel para las rutas web. Ambos métodos de autenticación están configurados para funcionar en paralelo.

7. **Despliegue en Render**: El proyecto incluye un `Dockerfile` y `nginx.conf` para despliegue en Render. El script `scripts/00-laravel-deploy.sh` se ejecuta automáticamente durante el despliegue.

8. **Roles de usuario**: El sistema maneja dos roles: `aspirante` y `administrador`. Cada rol tiene acceso a diferentes funcionalidades, controladas mediante el middleware `RoleMiddleware`.

9. **Soft Deletes**: La tabla `aspirantes` utiliza Soft Deletes, lo que significa que los registros eliminados no se borran físicamente de la base de datos.

10. **Pruebas**: Las pruebas se ejecutan con SQLite en memoria para evitar afectar la base de datos de desarrollo.
