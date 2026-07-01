@echo off
REM ============================================
REM REGENERAR.bat - Restaurar proyecto Laravel
REM ============================================
echo.
echo ============================================
echo  REGENERANDO PROYECTO LARAVEL...
echo ============================================
echo.

REM 1. Restaurar dependencias PHP
echo [1/4] Instalando dependencias PHP...
composer install --no-interaction
if %errorlevel% neq 0 (
    echo ERROR: Fallo al ejecutar composer install
    pause
    exit /b %errorlevel%
)
echo OK
echo.

REM 2. Restaurar dependencias JS
echo [2/4] Instalando dependencias JavaScript...
npm install
if %errorlevel% neq 0 (
    echo ERROR: Fallo al ejecutar npm install
    pause
    exit /b %errorlevel%
)
echo OK
echo.

REM 3. Compilar assets
echo [3/4] Compilando assets con Vite...
npm run build
if %errorlevel% neq 0 (
    echo ERROR: Fallo al compilar assets
    pause
    exit /b %errorlevel%
)
echo OK
echo.

REM 4. Regenerar cache
echo [4/4] Regenerando cache de Laravel...
php artisan view:cache
php artisan config:cache
echo OK
echo.

echo ============================================
echo  PROYECTO REGENERADO EXITOSAMENTE
echo ============================================
echo.
echo  Para iniciar el servidor:
echo    php artisan serve
echo.
pause
