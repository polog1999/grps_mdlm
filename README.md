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

- Relación entre `licencias.licencias` y tablas auxiliares: durante el análisis se identificó que la tabla `licencias.licencias` contiene un identificador de la persona solicitante que se relaciona con registros en una tabla llamada `lictotal` . En `lictotal` se encuentra información asociada, y el nombre de la persona solicitante aparece en registros relacionados con `personasolicitante`. Esto implica que las consultas para autocompletar datos (por ejemplo, nombre del solicitante) requieren unir estas tablas en la base de datos antigua.
- Campos repetidos/duplicados en `licencias.licencias` pueden provocar resultados múltiples al buscar por `número de expediente` o `número de licencia`. Tener reglas de deduplicación o comprobaciones en la aplicación es recomendable antes de aceptar una coincidencia como única.


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



Se han realizado cambios en el formulario Filament asociado a `CertificadoInspeccion` para mejorar la entrada de datos y soportar autocompletado desde la base de datos histórica de licencias. Los cambios principales son:

- Archivo del formulario: `app/Filament/Resources/CertificadoInspeccions/Schemas/CertificadoInspeccionForm.php`.
- Comportamientos y validaciones añadidos:
	- Campos por defecto y deshabilitados: `cin_departamento` = "Lima", `cin_provincia` = "Lima", `cin_distrito` = "La Molina" (son campos con `default()` y `disabled()` para evitar cambios manuales desde el formulario).
	- `cin_fec_fin` se calcula automáticamente como dos años después de `cin_fec_inicio` cuando `cin_indeterminado` está desactivado. `cin_indeterminado` es un `Toggle` reactivo que oculta/mostrar las fechas según corresponda.
	- Validaciones: `cin_capacidad` exige valores enteros positivos (`->minValue(1)` y reglas `integer|min:1`), `cin_area` exige valor decimal positivo con paso `0.01`, `cin_resolucion` tiene máscara y regla regex para formato `YYYY-YYYY`.
	- `cin_resolucion_sigla` y otros campos de solo lectura se configuran con `->disabled()->dehydrated()` para que su valor se guarde pero no pueda editarse desde la UI.
	- Búsqueda por expediente: `cin_establecimiento` incluye una `suffixAction` que abre un modal con un campo `search_expediente`. Al ejecutar la acción se consulta la base de datos de licencias y, si se encuentra una coincidencia, el formulario se autocompleta con datos como: número de licencia, giro, área, dirección, fecha de inicio y fin calculada, razón social y el expediente.

Servicio que consulta la BD de licencias
---------------------------------------
Se implementó el servicio `app/Services/LicenciaService.php` con el propósito de encapsular las consultas a la base de datos legada de licencias (`pgsql_licencias`).  
Este servicio centraliza la lógica de acceso a datos, promoviendo la separación de responsabilidades y facilitando el mantenimiento del sistema.

El servicio expone métodos específicos para realizar búsquedas dentro del esquema `licencia.licencia`, tales como:

- **`obtenerPorNumeroExpediente($expediente)`**  
  Permite buscar registros según el número de expediente, devolviendo un único resultado si existe coincidencia o indicando si no se encontró o existen duplicados.

- **`obtenerPorNumeroLicencia($licencia)`**  
  Busca registros asociados a un número de licencia determinado.

- **`obtenerPorNumeroLicenciaYExpediente($licencia, $expediente)`**  
  Realiza una búsqueda combinada, verificando coincidencias tanto por número de licencia como por número de expediente.

Cada método ejecuta la consulta mediante la conexión `pgsql_licencias` y gestiona los posibles escenarios de resultado (`ok`, `duplicado`, `no_encontrado` o `error`).  
De esta forma, `LicenciaService` actúa como una capa de integración entre el sistema actual y la base de datos antigua, garantizando un acceso **controlado**, **seguro** y **reutilizable** a la información de licencias.

Conexión a la base de datos de producción (licencias)
---------------------------------------------------

Este proyecto incluye una conexión adicional a la base de datos histórica/prod para recuperar datos de `licencias`. Por seguridad, las credenciales no se incluyen en el repositorio: se debe configurar una conexión en `config/database.php` con un nombre como `pgsql_licencias` y añadir las variables al archivo `.env` (o usar un vault/secret manager). 




