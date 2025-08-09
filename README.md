# Sistema de Gestión de Empleados Sansare

Sistema completo de gestión de empleados desarrollado con Laravel 11 y Filament 3, diseñado para la administración integral de recursos humanos.

## 🚀 Características Principales

- **Gestión de Empleados**: Registro completo con datos personales, laborales y documentos
- **Administración de Contratos**: Control de contratos laborales con diferentes tipos y estados
- **Sistema de Permisos**: Gestión de solicitudes y aprobaciones de permisos
- **Alertas de Vencimiento**: Notificaciones automáticas para documentos y contratos próximos a vencer
- **Generación de Reportes**: Exportación en múltiples formatos (PDF, Excel, CSV, HTML)
- **Panel de Administración**: Interfaz moderna y responsiva con Filament
- **Autenticación y Autorización**: Sistema seguro de usuarios y roles

## 📋 Requisitos del Sistema

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Node.js**: >= 18.0
- **NPM**: >= 9.0
- **MySQL**: >= 8.0 o **MariaDB**: >= 10.3
- **Servidor Web**: Apache o Nginx

### Extensiones PHP Requeridas

```
php-curl
php-dom
php-fileinfo
php-filter
php-hash
php-mbstring
php-openssl
php-pcre
php-pdo
php-session
php-tokenizer
php-xml
php-zip
php-gd
php-intl
```

## 🛠️ Instalación

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/SistemaGestionEmpleadosSansare.git
cd SistemaGestionEmpleadosSansare
```

### 2. Instalar Dependencias de PHP

```bash
composer install
```

### 3. Instalar Dependencias de Node.js

```bash
npm install
```

### 4. Configurar Variables de Entorno

```bash
# Copiar el archivo de configuración
cp .env.example .env

# Generar la clave de aplicación
php artisan key:generate
```

### 5. Configurar Base de Datos

Editar el archivo `.env` con los datos de tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_empleados_sansare
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Crear Base de Datos

```sql
CREATE DATABASE sistema_empleados_sansare CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Ejecutar Migraciones y Seeders

```bash
# Ejecutar migraciones y poblar la base de datos
php artisan migrate:fresh --seed
```

### 8. Crear Enlace Simbólico para Storage

```bash
php artisan storage:link
```

### 9. Compilar Assets

```bash
# Para desarrollo
npm run dev

# Para producción
npm run build
```

### 10. Crear Usuario Administrador

```bash
php artisan make:filament-user
```

Sigue las instrucciones para crear tu usuario administrador.

## 🚀 Ejecutar el Proyecto

### Modo Desarrollo

```bash
# Iniciar servidor de desarrollo
php artisan serve

# En otra terminal, compilar assets en tiempo real
npm run dev
```

El sistema estará disponible en: `http://localhost:8000`

### Panel de Administración

Accede al panel de administración en: `http://localhost:8000/admin`

**Credenciales por defecto:**
- Email: `admin@example.com`
- Contraseña: `admin123`

## 📁 Estructura del Proyecto

```
SistemaGestionEmpleadosSansare/
├── app/
│   ├── Console/Commands/          # Comandos Artisan personalizados
│   ├── Exports/                   # Clases de exportación
│   ├── Filament/                  # Recursos y widgets de Filament
│   │   ├── Resources/             # Recursos CRUD
│   │   └── Widgets/               # Widgets del dashboard
│   ├── Http/Controllers/          # Controladores HTTP
│   ├── Models/                    # Modelos Eloquent
│   └── Services/                  # Servicios de negocio
├── database/
│   ├── migrations/                # Migraciones de base de datos
│   └── seeders/                   # Seeders de datos
├── resources/
│   ├── views/reportes/            # Plantillas para reportes
│   ├── css/                       # Estilos CSS
│   └── js/                        # JavaScript
└── storage/
    └── app/public/reportes/       # Archivos de reportes generados
```

## 🔧 Configuración Adicional

### Configuración de Correo (Opcional)

Para notificaciones por email, configura en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="Sistema Empleados Sansare"
```

### Configuración de Cola de Trabajos (Opcional)

Para procesamiento en segundo plano:

```env
QUEUE_CONNECTION=database
```

```bash
# Ejecutar worker de cola
php artisan queue:work
```

## 📊 Módulos del Sistema

### 1. Gestión de Empleados
- Registro completo de empleados
- Datos personales y laborales
- Historial de cambios
- Estados: activo, inactivo, suspendido

### 2. Administración de Contratos
- Contratos por tiempo determinado e indeterminado
- Renovaciones automáticas
- Control de vencimientos
- Historial contractual

### 3. Sistema de Permisos
- Solicitudes de permisos
- Flujo de aprobación
- Tipos: vacaciones, enfermedad, personal
- Calendario de permisos

### 4. Alertas y Notificaciones
- Vencimiento de contratos
- Documentos próximos a vencer
- Cumpleaños de empleados
- Notificaciones automáticas

### 5. Generación de Reportes
- Reportes de empleados
- Historial de contratos
- Estadísticas de permisos
- Exportación en múltiples formatos

## 🔐 Usuarios y Roles

### Roles Disponibles
- **Super Admin**: Acceso completo al sistema
- **Administrador**: Gestión de empleados y reportes
- **RRHH**: Gestión de permisos y contratos
- **Empleado**: Acceso limitado a información personal

## 📈 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generar reporte específico
php artisan reportes:generate {id}

# Optimizar para producción
php artisan optimize
composer install --optimize-autoloader --no-dev
```

## 🐛 Solución de Problemas

### Error de Permisos
```bash
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Windows (ejecutar como administrador)
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T
```

### Error de Memoria
Aumentar límite de memoria en `.env`:
```env
PHP_MEMORY_LIMIT=512M
```

### Problemas con Migraciones
```bash
# Resetear migraciones
php artisan migrate:reset
php artisan migrate:fresh --seed
```

## 🔄 Actualización

```bash
# Actualizar dependencias
composer update
npm update

# Ejecutar nuevas migraciones
php artisan migrate

# Limpiar caché
php artisan optimize:clear
```

## 📝 Desarrollo

### Crear Nuevo Recurso Filament
```bash
php artisan make:filament-resource NombreRecurso --generate
```

### Crear Nueva Migración
```bash
php artisan make:migration create_tabla_name
```

### Crear Nuevo Modelo
```bash
php artisan make:model NombreModelo -m
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📞 Soporte

Para soporte técnico o consultas:
- Email: soporte@sansare.com
- Documentación: [Wiki del Proyecto](https://github.com/tu-usuario/SistemaGestionEmpleadosSansare/wiki)
- Issues: [GitHub Issues](https://github.com/tu-usuario/SistemaGestionEmpleadosSansare/issues)

## 🎯 Roadmap

- [ ] Módulo de Nómina
- [ ] Integración con APIs externas
- [ ] Aplicación móvil
- [ ] Dashboard avanzado con BI
- [ ] Módulo de Capacitaciones
- [ ] Sistema de Evaluaciones

---

**Desarrollado con ❤️ para la gestión eficiente de recursos humanos**
