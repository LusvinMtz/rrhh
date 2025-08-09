# Sistema de Gestión de Empleados - Sansare

## Descripción
Sistema de gestión de recursos humanos desarrollado con Laravel 12 y Filament para la empresa Sansare. Permite gestionar empleados, contratos, renovaciones y permisos de manera eficiente.

## Tecnologías Utilizadas
- **Laravel 12**: Framework PHP
- **Filament 3.3**: Panel de administración
- **SQLite**: Base de datos (configurable para MySQL)
- **Livewire**: Componentes reactivos
- **Tailwind CSS**: Framework CSS
- **Vite**: Build tool
- **maatwebsite/excel**: Exportación/Importación Excel
- **barryvdh/laravel-dompdf**: Generación de PDFs

## Instalación

### Requisitos
- PHP 8.2 o superior
- Composer
- Node.js y NPM

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd SistemaGestionEmpleadosSansare
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Configurar el archivo de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar la base de datos**
   - Para SQLite (por defecto): El archivo ya está creado
   - Para MySQL: Editar el archivo `.env` con las credenciales de MySQL

5. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

6. **Crear usuario administrador**
   ```bash
   php artisan make:filament-user
   ```

7. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

## Acceso al Sistema

- **URL del sistema**: http://127.0.0.1:8000
- **Panel de administración**: http://127.0.0.1:8000/admin
- **Credenciales**: Las configuradas durante la instalación

## Módulos del Sistema

### 1. Gestión de Empleados
- Registro completo de empleados
- Información personal y laboral
- Contactos de emergencia
- Estados: activo, inactivo, suspendido

### 2. Gestión de Contratos
- Contratos por empleado
- Tipos: indefinido, temporal, por obra
- Fechas de inicio y fin
- Salarios y beneficios

### 3. Renovaciones de Contratos
- Renovaciones vinculadas a contratos
- Cambios de condiciones
- Proceso de aprobación
- Historial de renovaciones

### 4. Gestión de Permisos
- Solicitudes de permisos
- Tipos: vacaciones, enfermedad, personal, etc.
- Proceso de aprobación
- Control de días con/sin goce de salario

## Características de Filament

- **Dashboard**: Panel principal con estadísticas
- **CRUD completo**: Crear, leer, actualizar, eliminar registros
- **Filtros avanzados**: Búsqueda y filtrado de datos
- **Exportación**: Exportar datos a Excel y PDF
- **Importación**: Importar datos desde Excel
- **Validaciones**: Validación de formularios
- **Relaciones**: Gestión de relaciones entre modelos

## Estructura de la Base de Datos

### Tabla: empleados
- Información personal completa
- Datos laborales
- Contactos de emergencia
- Números de seguridad social

### Tabla: contratos
- Vinculación con empleados
- Detalles contractuales
- Fechas y condiciones
- Estados del contrato

### Tabla: renovaciones
- Vinculación con contratos
- Nuevas condiciones
- Proceso de aprobación
- Historial de cambios

### Tabla: permisos
- Vinculación con empleados
- Tipos de permisos
- Fechas y duración
- Estados de aprobación

## Configuración Adicional

### Para usar MySQL
1. Crear base de datos `rrhh_sansare`
2. Configurar `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rrhh_sansare
   DB_USERNAME=root
   DB_PASSWORD=tu_password
   ```

### Configuración de correo (opcional)
Configurar las variables de correo en `.env` para notificaciones.

## Desarrollo

### Comandos útiles
```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Crear nuevos recursos
php artisan make:filament-resource NombreModelo

# Ejecutar migraciones
php artisan migrate:fresh --seed
```

## Soporte

Para soporte técnico o consultas sobre el sistema, contactar al equipo de desarrollo.

## Licencia

Este proyecto está desarrollado específicamente para Sansare y es de uso interno de la empresa.