-- =====================================================
-- Script de Creación de Base de Datos MySQL
-- Sistema de Gestión de Empleados Sansare
-- Generado desde migraciones de Laravel
-- =====================================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS `rrhh_sansare`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `rrhh_sansare`;

-- =====================================================
-- TABLA: users
-- =====================================================
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: password_reset_tokens
-- =====================================================
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: sessions
-- =====================================================
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: cache
-- =====================================================
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: cache_locks
-- =====================================================
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: jobs
-- =====================================================
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: job_batches
-- =====================================================
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: failed_jobs
-- =====================================================
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: empleados (Tabla principal de empleados)
-- =====================================================
CREATE TABLE `empleados` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo_empleado` varchar(255) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `dpi` varchar(13) NOT NULL,
  `nit` varchar(12) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('M','F') NOT NULL,
  `estado_civil` enum('soltero','casado','divorciado','viudo','union_libre') NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `direccion` text NOT NULL,
  `departamento` varchar(255) NOT NULL,
  `municipio` varchar(255) NOT NULL,
  `puesto` varchar(255) NOT NULL,
  `area_trabajo` varchar(255) NOT NULL,
  `salario_base` decimal(10,2) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `tipo_contrato` enum('indefinido','temporal','por_obra') NOT NULL,
  `estado` enum('activo','inactivo','suspendido') NOT NULL,
  `numero_igss` varchar(255) DEFAULT NULL,
  `numero_irtra` varchar(255) DEFAULT NULL,
  `contacto_emergencia_nombre` varchar(255) DEFAULT NULL,
  `contacto_emergencia_telefono` varchar(15) DEFAULT NULL,
  `contacto_emergencia_relacion` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empleados_codigo_empleado_unique` (`codigo_empleado`),
  UNIQUE KEY `empleados_dpi_unique` (`dpi`),
  UNIQUE KEY `empleados_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: contratos
-- =====================================================
CREATE TABLE `contratos` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empleado_id` bigint(20) UNSIGNED NOT NULL,
  `numero_contrato` varchar(255) NOT NULL,
  `tipo_contrato` enum('indefinido','temporal','por_obra') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `salario` decimal(10,2) NOT NULL,
  `puesto` varchar(255) NOT NULL,
  `area_trabajo` varchar(255) NOT NULL,
  `descripcion_puesto` text DEFAULT NULL,
  `jornada_laboral` enum('completa','parcial','por_horas') NOT NULL,
  `horas_semanales` int(11) NOT NULL DEFAULT 40,
  `clausulas_especiales` text DEFAULT NULL,
  `estado` enum('activo','vencido','terminado','suspendido') NOT NULL,
  `fecha_firma` date DEFAULT NULL,
  `lugar_trabajo` varchar(255) DEFAULT NULL,
  `beneficios` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contratos_numero_contrato_unique` (`numero_contrato`),
  KEY `contratos_empleado_id_foreign` (`empleado_id`),
  CONSTRAINT `contratos_empleado_id_foreign` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: renovaciones
-- =====================================================
CREATE TABLE `renovaciones` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contrato_id` bigint(20) UNSIGNED NOT NULL,
  `numero_renovacion` varchar(255) NOT NULL,
  `fecha_renovacion` date NOT NULL,
  `nueva_fecha_fin` date NOT NULL,
  `nuevo_salario` decimal(10,2) DEFAULT NULL,
  `nuevo_puesto` varchar(255) DEFAULT NULL,
  `nueva_area_trabajo` varchar(255) DEFAULT NULL,
  `cambios_realizados` text DEFAULT NULL,
  `justificacion` text NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada') NOT NULL,
  `fecha_aprobacion` date DEFAULT NULL,
  `aprobado_por` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `renovaciones_numero_renovacion_unique` (`numero_renovacion`),
  KEY `renovaciones_contrato_id_foreign` (`contrato_id`),
  CONSTRAINT `renovaciones_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: permisos
-- =====================================================
CREATE TABLE `permisos` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empleado_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_permiso` enum('vacaciones','enfermedad','personal','maternidad','paternidad','estudio','duelo','otro') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `dias_solicitados` int(11) NOT NULL,
  `motivo` text NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_aprobacion` date DEFAULT NULL,
  `aprobado_por` varchar(255) DEFAULT NULL,
  `observaciones_empleado` text DEFAULT NULL,
  `observaciones_supervisor` text DEFAULT NULL,
  `con_goce_salario` tinyint(1) NOT NULL DEFAULT 1,
  `documento_adjunto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permisos_empleado_id_foreign` (`empleado_id`),
  CONSTRAINT `permisos_empleado_id_foreign` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: altas_bajas
-- =====================================================
CREATE TABLE `altas_bajas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empleado_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_movimiento` enum('alta','baja','reingreso') NOT NULL,
  `fecha_movimiento` date NOT NULL,
  `motivo` enum('contratacion','renuncia','despido','jubilacion','defuncion','abandono','fin_contrato','reingreso') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `documento_soporte` varchar(255) DEFAULT NULL,
  `autorizado_por` varchar(255) NOT NULL,
  `fecha_efectiva` date NOT NULL,
  `estado` enum('pendiente','aprobado','ejecutado') NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `altas_bajas_empleado_id_foreign` (`empleado_id`),
  CONSTRAINT `altas_bajas_empleado_id_foreign` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: empleados_renglon
-- =====================================================
CREATE TABLE `empleados_renglon` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empleado_id` bigint(20) UNSIGNED NOT NULL,
  `numero_renglon` varchar(10) NOT NULL,
  `tipo_renglon` enum('011','021','022','029','031','032','033','041','051','otro') NOT NULL,
  `descripcion_renglon` varchar(255) NOT NULL,
  `salario_renglon` decimal(10,2) NOT NULL,
  `fecha_asignacion` date NOT NULL,
  `fecha_vigencia_inicio` date NOT NULL,
  `fecha_vigencia_fin` date DEFAULT NULL,
  `estado` enum('activo','inactivo','suspendido') NOT NULL,
  `dependencia` varchar(255) NOT NULL,
  `unidad_ejecutora` varchar(255) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empleados_renglon_numero_renglon_unique` (`numero_renglon`),
  KEY `empleados_renglon_empleado_id_foreign` (`empleado_id`),
  CONSTRAINT `empleados_renglon_empleado_id_foreign` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: contratos_renglon
-- =====================================================
CREATE TABLE `contratos_renglon` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empleado_renglon_id` bigint(20) UNSIGNED NOT NULL,
  `numero_contrato_renglon` varchar(255) NOT NULL,
  `tipo_contrato` enum('temporal','por_servicios','consultoria') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `monto_total` decimal(12,2) NOT NULL,
  `monto_mensual` decimal(10,2) NOT NULL,
  `objeto_contrato` text NOT NULL,
  `productos_entregables` text NOT NULL,
  `fuente_financiamiento` varchar(255) NOT NULL,
  `programa_presupuestario` varchar(255) NOT NULL,
  `estado` enum('borrador','activo','vencido','terminado','cancelado') NOT NULL,
  `fecha_firma` date DEFAULT NULL,
  `supervisor_contrato` varchar(255) NOT NULL,
  `clausulas_especiales` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contratos_renglon_numero_contrato_renglon_unique` (`numero_contrato_renglon`),
  KEY `contratos_renglon_empleado_renglon_id_foreign` (`empleado_renglon_id`),
  CONSTRAINT `contratos_renglon_empleado_renglon_id_foreign` FOREIGN KEY (`empleado_renglon_id`) REFERENCES `empleados_renglon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: alertas_vencimiento
-- =====================================================
CREATE TABLE `alertas_vencimiento` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `alertable_type` varchar(255) NOT NULL,
  `alertable_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_alerta` enum('contrato_vencimiento','contrato_renglon_vencimiento','permiso_vencimiento','documento_vencimiento') NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `dias_anticipacion` int(11) NOT NULL DEFAULT 30,
  `fecha_alerta` date NOT NULL,
  `prioridad` enum('baja','media','alta','critica') NOT NULL,
  `estado` enum('pendiente','notificada','atendida','vencida') NOT NULL,
  `destinatarios` json DEFAULT NULL,
  `fecha_notificacion` timestamp NULL DEFAULT NULL,
  `acciones_tomadas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alertas_vencimiento_alertable_type_alertable_id_index` (`alertable_type`,`alertable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: reportes
-- =====================================================
CREATE TABLE `reportes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_reporte` varchar(255) NOT NULL,
  `tipo_reporte` enum('general_interno','informacion_publica','historial_empleados','historial_permisos','historial_contratos','dashboard','personalizado') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `parametros` json DEFAULT NULL,
  `columnas` json DEFAULT NULL,
  `formato` varchar(10) NOT NULL DEFAULT 'excel',
  `generado_por` varchar(255) NOT NULL,
  `fecha_generacion` timestamp NOT NULL,
  `archivo_path` varchar(255) DEFAULT NULL,
  `total_registros` int(11) NOT NULL DEFAULT 0,
  `estado` enum('generando','completado','error') NOT NULL,
  `mensaje_error` text DEFAULT NULL,
  `es_publico` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_desde` date DEFAULT NULL,
  `fecha_hasta` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTAR USUARIO ADMINISTRADOR POR DEFECTO
-- =====================================================
INSERT INTO `users` (`name`, `email`, `password`, `created_at`, `updated_at`) VALUES
('Administrador', 'admin@sansare.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());
-- Contraseña: password

-- =====================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================
CREATE INDEX `idx_empleados_estado` ON `empleados` (`estado`);
CREATE INDEX `idx_empleados_departamento` ON `empleados` (`departamento`);
CREATE INDEX `idx_empleados_fecha_ingreso` ON `empleados` (`fecha_ingreso`);
CREATE INDEX `idx_contratos_estado` ON `contratos` (`estado`);
CREATE INDEX `idx_contratos_fecha_inicio` ON `contratos` (`fecha_inicio`);
CREATE INDEX `idx_contratos_fecha_fin` ON `contratos` (`fecha_fin`);
CREATE INDEX `idx_permisos_estado` ON `permisos` (`estado`);
CREATE INDEX `idx_permisos_fecha_inicio` ON `permisos` (`fecha_inicio`);
CREATE INDEX `idx_alertas_fecha_vencimiento` ON `alertas_vencimiento` (`fecha_vencimiento`);
CREATE INDEX `idx_alertas_estado` ON `alertas_vencimiento` (`estado`);

-- =====================================================
-- SCRIPT COMPLETADO
-- =====================================================
-- Este script crea la base de datos completa para el
-- Sistema de Gestión de Empleados Sansare
--
-- Para ejecutar:
-- 1. Conectarse a MySQL como root
-- 2. Ejecutar: SOURCE /ruta/al/archivo/create_database_mysql.sql
-- 3. Configurar el archivo .env de Laravel con estos datos
-- =====================================================
