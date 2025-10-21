# ITSE App

Este sistema es una refactorización / migración del sistema anterior llamado "Sistema de Registro de Certificados ITSE".

Contexto de la migración
------------------------

Origen (sistema antiguo - devlocal):

La migración de la base de datos completa se realizó desde PostgreSQL versión 12.22 a la versión 18.0.

Base de datos anterior: observaciones
------------------------------------

Durante el análisis del esquema original se detectaron inconsistencias relevantes en el esquema `licencias.licencias` que conviene documentar:

- Campos duplicados / repetidos: los valores de "número de licencia" y "número de expediente" aparecen repetidos en varias filas. Esto puede causar problemas al consultar registros por identificador (ambigüedad de claves o resultados inesperados) y requiere limpieza o reglas de normalización antes de depender de esos campos como identificadores únicos.

- Dudas sobre `usa_id`: existe un campo `usa_id` en varias tablas cuya semántica no está completamente documentada. Una hipótesis es que podría tener relación con un identificador de usuario o incluso con un identificador de QR, pero esto no está confirmado. Es necesario revisar el sistema antiguo o consultar con el equipo que mantuvo la base de datos para confirmar su propósito y normalizar su uso en la nueva estructura.


Descripción del nuevo sistema
-----------------------------

El nuevo sistema está implementado con:

- Laravel: framework PHP para aplicaciones web. Se usa para la lógica de servidor, migraciones de base de datos, modelos Eloquent y controladores.
- Filament: conjunto de herramientas (panel administrativo) sobre Laravel para construir interfaces CRUD rápidamente (recursos, formularios, tablas y páginas administrativas).

En este proyecto se han migrado las tablas principales del sistema antiguo (`certificadoinspeccion` y `tipoedificacion`) al esquema `itse` en la nueva base de datos. Se proporcionan migraciones en `database/migrations/` para crear las tablas con la estructura actualizada.

Qué incluye esta refactorización
---------------------------------

- Migraciones de base de datos adaptadas al nuevo esquema `itse`.
- Modelos Eloquent para las tablas migradas.
- Recursos Filament (resources):  generar certificados de inspeccion `CertificadoInspeccion`).

Procedimiento General de migración y comandos
------------------------------------

A continuación se listan los comandos y el procedimiento habitual para trabajar con migraciones, modelos y recursos Filament en este proyecto. Aquí se explica desde crear una migración hasta ejecutarla y generar el CRUD en Filament.

1. Crear una nueva migración

```powershell
php artisan make:migration create_certificadoinspeccion_table --create=certificadoinspeccion
```

2. Editar la migración (ubicada en `database/migrations/`) y definir las columnas y el esquema (si usa esquema `itse`, especificar el nombre de la tabla como `itse.certificadoinspeccion` en las consultas SQL o ajustar el prefijo del schema en la conexión).

3. Ejecutar las migraciones (entorno local)

```powershell
php artisan migrate
```

Si necesita ejecutar una migración específica (por ejemplo, el archivo X):

```powershell
php artisan migrate --path=/database/migrations/2025_10_21_125804_create__talble__certificadoinspeccion.php
```

4. Generar el modelo Eloquent

```powershell
php artisan make:model CertificadoInspeccion
```

Editar `app/Models/CertificadoInspeccion.php` para establecer `protected $table = 'itse.certificadoinspeccion';` y definir `$fillable`, `$casts` y relaciones.

5. Generar recurso Filament (CRUD)

```powershell
php artisan make:filament-resource CertificadoInspeccion --model=App\\Models\\CertificadoInspeccion
```

Editar los archivos generados en `app/Filament/Resources/CertificadoInspeccionResource.php` y las subcarpetas `Pages` para ajustar formularios, columnas y validaciones.

6. Probar la aplicación

```powershell
php artisan serve
```

Luego acceder a la interfaz de Filament (por defecto `/admin`) y verificar el CRUD.

Ciclo de vida del proyecto: migración y modelo
---------------------------------------------

Migración creada: `database/migrations/2025_10_21_125804_create__talble__certificadoinspeccion.php`

- Objetivo: crear la tabla `certificadoinspeccion` con sus campos principales para almacenar los certificados de inspección.
- Decisiones clave:
	- Se utilizó `id('cin_id')` como clave primaria para mantener compatibilidad con el sistema anterior.
	- Campos numéricos (`cin_area`, `cin_capacidad`, `cin_numero`, `cin_anio`) se definieron con tipos adecuados (decimal/integer).
	- Fechas (`cin_fecha`, `cin_fec_inicio`, `cin_fec_fin`) como `date`, y marcas de auditoría con `timestamps()`.
	- Campos booleanos/flags (`cin_indeterminado`, `cin_filaoriginal`, `cin_filaeliminada`, `cin_consello`) para lógica de estado.
	- Texto largo `cin_nota` con longitud aumentada (400) para comentarios.

Modelo Eloquent: `app/Models/CertificadoInspeccion.php`

- Propósito: representar la tabla en Laravel, definir la tabla, la PK, los campos asignables y comportamientos (timestamps).
- Detalles del modelo actual:
	- `protected $table = 'certificadoinspeccion';` — mapeo directo a la tabla creada por la migración.
	- `protected $primaryKey = 'cin_id';` — clave primaria personalizada.
	- `$fillable` contiene los campos que se permiten asignar en masa; se añadió una lista inicial con los campos más relevantes.

Comando ejecutado para la Generacion del Resource Filament de CertificadoInspeccions:

```powershell
php artisan make:filament-resource CertificadoInspeccion --model=App\\Models\\CertificadoInspeccion
```

Archivos creados en `app/Filament/Resources/CertificadoInspeccions/`:

- `CertificadoInspeccionResource.php`: Archivo principal del Resource, define el modelo, navegación, formularios y tablas.

- `Pages/`:
  - `CreateCertificadoInspeccion.php`: Página para crear nuevos registros.
  - `EditCertificadoInspeccion.php`: Página para editar registros existentes.
  - `ListCertificadoInspeccions.php`: Página para listar registros.
  - `ViewCertificadoInspeccion.php`: Página para ver detalles de un registro.

- `Schemas/`:
  - `CertificadoInspeccionForm.php`: Esquema del formulario para crear/editar.
  - `CertificadoInspeccionInfolist.php`: Esquema para mostrar información en vista de detalles.

- `Tables/`:
  - `CertificadoInspeccionsTable.php`: Definición de la tabla para listar registros.


