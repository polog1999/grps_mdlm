<<<<<<< HEAD
# Intranet 

Plataforma administrativa modular construida con **Laravel** y **Filament**.
La Intranet centraliza procesos municipales en un solo panel. Cada funcionalidad vive como **módulo**.

---

## Tecnologías

* **Laravel**: backend, migraciones, Eloquent, servicios y controladores.
* **Filament**: panel administrativo (resources CRUD, páginas, tablas, formularios, widgets).
* **PostgreSQL**: base de datos (migrada de v12.22 a v18.0).
* *(Opcional según caso de uso)* **PhpSpreadsheet / Laravel-Excel** para plantillas y reportes.

---

# Módulos

## SIL

Módulo enfocado en licencias y su interoperabilidad con datos históricos.

---

### Certificados de Inspección (ITSE)

#### 1) Contexto de migración

* **Origen (sistema antiguo – devlocal)**: PostgreSQL **12.22** → migrado a **18.0**.
* **Tablas migradas** al esquema `itse`:

  * `certificadoinspeccion` (principal)
  * `tipoedificacion` (catálogo)

#### 2) Hallazgos del sistema heredado

* **Duplicados en licencias**: en `licencia.licencia` (o `licencias.licencias`) se repiten *número de licencia* y *número de expediente*.
  → Requiere **deduplicación** o **reglas** antes de tratar esos campos como únicos.
* **`usa_id` sin semántica clara**: aparece en varias tablas; podría ser identificador de usuario o QR (no confirmado).
  → **Acción**: revisar el sistema anterior y normalizar su uso.
* **Relaciones no normalizadas**: tabla `lictotal` actúa como unión entre `per_idsolicitante` y datos del solicitante.
  → **Recomendación**: crear una tabla dedicada a **personas solicitantes** y normalizar FKs.
* **Riesgo en búsquedas**: por duplicados, consultas por *número de expediente* o *número de licencia* pueden devolver múltiples filas.
  → **Acción**: desambiguar en la aplicación y reforzar integridad a futuro.

---

#### 3) Qué incluye el submódulo ITSE

* **Migraciones** de base de datos ajustadas al esquema `itse`.
* **Modelo Eloquent** `App\Models\CertificadoInspeccion`.
* **Resource Filament** `CertificadoInspeccionResource` (CRUD completo).
* **Servicio de integración** `App\Services\LicenciaService` para consultar DB legada de licencias (`pgsql_licencias`).
* **Rutas de apoyo** para autocompletado (respuestas JSON) consumidas por formularios Filament.

---

#### 4) Procedimiento de trabajo (migraciones, modelo, resource)

**4.1 Crear migración**

```bash
php artisan make:migration create_certificadoinspeccion_table --create=certificadoinspeccion
php artisan migrate
# o una migración específica:
php artisan migrate --path=/database/migrations/2025_10_21_125804_create__talble__certificadoinspeccion.php
```

**4.2 Modelo Eloquent**

```bash
php artisan make:model CertificadoInspeccion
```

`app/Models/CertificadoInspeccion.php`:

```php
class CertificadoInspeccion extends Model
{
    protected $table = 'certificadoinspeccion'; // si la conexión ya apunta al esquema itse
    protected $primaryKey = 'cin_id';
    public $timestamps = true;

    protected $fillable = [
        'cin_anio','tie_id','cin_numero','cin_area','cin_capacidad',
        'cin_fecha','cin_fec_inicio','cin_fec_fin','cin_indeterminado',
        'cin_filafecha','cin_filaoriginal','cin_filaeliminada','usa_id',
        'cin_consello','lic_id','cin_departamento','cin_provincia',
        'cin_licencia','cin_procedimiento','cin_distrito','cin_expediente',
        'cin_ubicacion','cin_nota','cin_resolucion_sigla','cin_giro',
        'cin_resolucion','cin_establecimiento',
    ];
}
```

**4.3 Resource Filament (CRUD)**

```bash
php artisan make:filament-resource CertificadoInspeccion --model=App\\Models\\CertificadoInspeccion
```

Archivos generados (ruta base `app/Filament/Resources/CertificadoInspeccionResource/`):

* `CertificadoInspeccionResource.php` (definición principal del resource)
* `Pages/`

  * `CreateCertificadoInspeccion.php`
  * `EditCertificadoInspeccion.php`
  * `ListCertificadoInspeccions.php`
  * `ViewCertificadoInspeccion.php`
* `Schemas/`

  * `CertificadoInspeccionForm.php` (formulario)
  * `CertificadoInspeccionInfolist.php` (vista detallada)
* `Tables/`

  * `CertificadoInspeccionsTable.php` (listado)

**4.4 Probar**

```bash
php artisan serve
# acceder a /admin
```

---

#### 5) Formulario ITSE (comportamientos y validaciones)

Archivo: `app/Filament/Resources/CertificadoInspeccionResource/Schemas/CertificadoInspeccionForm.php`

* **Defaults bloqueados**:
  `cin_departamento = Lima`, `cin_provincia = Lima`, `cin_distrito = La Molina`
  (via `default()` + `disabled()`; usar `->dehydrated()` si se requiere persistencia).
* **Fechas**:
  `cin_fec_fin` = `cin_fec_inicio` + **2 años** cuando `cin_indeterminado` es **false**.
  `cin_indeterminado` (Toggle) controla visibilidad/habilitación de fechas.
* **Validaciones**:

  * `cin_capacidad`: entero positivo (`min:1`).
  * `cin_area`: decimal positivo (paso `0.01`).
  * `cin_resolucion`: máscara/regex `YYYY-YYYY`.
* **Solo lectura con guardado**:
  Campos como `cin_resolucion_sigla` con `->disabled()->dehydrated()`.
* **Autocompletado por expediente**:
  Acción (`suffixAction`) sobre `cin_establecimiento` abre modal con `search_expediente`.
  Consulta DB legada y, si hay coincidencia, **autocompleta**: nro de licencia, giro, área, dirección, fechas (inicio/fin), razón social y expediente.
  Respuesta manejada con estados: `ok | duplicado | no_encontrado`.

---

#### 6) Servicio de integración con DB legada

Archivo: `app/Services/LicenciaService.php` (conexión **`pgsql_licencias`**).

* **Métodos expuestos**:

  * `obtenerPorNumeroExpediente($expediente)`
  * `obtenerPorNumeroLicencia($licencia)`
  * `obtenerPorNumeroLicenciaYExpediente($licencia, $expediente)`
* **Comportamiento**:

  * Consulta `licencia.licencia` (u homóloga en legado).
  * Retorna estados: `ok | duplicado | no_encontrado | error`.
  * Encapsula acceso a datos legados y reglas de negocio.

---

#### 7) Rutas para autocompletado

* Rutas bajo `/test` en `routes/web.php` con middleware `auth` y `verified`.
* Controladores como `LicenciaController` y `PersonaSolicitanteController` retornan **JSON** para autocompletar campos del formulario.
* **Sugerencia**: para integraciones M2M, exponer endpoints **API** separados con autenticación de tokens y respuestas **401/403** en lugar de redirecciones a login.

---

#### 8) Conexión a base histórica/producción (licencias)

* Agregar conexión `pgsql_licencias` en `config/database.php`.
* Definir credenciales en `.env` (no versionar).
* Consumir esta conexión exclusivamente desde el **Servicio** para mantener separación de responsabilidades.

---

#### 9) Decisiones de diseño de la tabla `certificadoinspeccion`

* **PK**: `id('cin_id')` (compatibilidad con legado).
* **Numéricos**: `cin_area`, `cin_capacidad`, `cin_numero`, `cin_anio`.
* **Fechas**: `cin_fecha`, `cin_fec_inicio`, `cin_fec_fin`.
* **Flags**: `cin_indeterminado`, `cin_filaoriginal`, `cin_filaeliminada`, `cin_consello`.
* **Texto**: `cin_nota` (hasta 400).

---

#### 10) Funcionamiento del sistema (UI/UX, acciones y reglas)

**Vista principal (Listado ITSE):**
La pantalla inicial es una **tabla de Certificados de Inspección** con los **datos principales** del certificado (p. ej.: año, número, establecimiento, expediente, fecha, giro, ubicación, etc.).
Incluye:

* **Búsqueda y filtros** por campos clave.
* **Paginación** y **ordenamiento**.
* **Exportación a Excel** según columnas visibles/seleccionadas y filtros aplicados.

**Acciones por registro (CRUD + utilidades):**

* **Ver** → Abre una página de detalle con **Infolist** (lectura).
* **Editar** → Formulario para actualizar campos.
* **Borrar** → **Eliminación lógica**: marca `cin_filaeliminada = true` para **preservar histórico** (no se borra físicamente).
* **Exportar PDF** → Genera el **“CERTIFICADO DE INSPECCIÓN TÉCNICA”** en formato PDF para descarga/impresión.

**Creación de Certificado (autocompletado inteligente):**

* Permite **autocompletar** datos a partir de:

  * **Número de Expediente**, **Número de Licencia** o **ambos**.
* **Regla de unicidad y vigencia:**
  Antes de poblar el formulario, el sistema verifica que el registro fuente:

  * Tenga `filaeliminada = false` (**vigente**), y
  * Sea **único** (sin duplicados para los criterios ingresados).
* Si hay duplicidad o el registro está eliminado, se notifica al usuario y **no se autocompleta**, evitando inconsistencias.

**Filtros y exportaciones:**

* Los **filtros del listado** se respetan en las **exportaciones a Excel** (y, opcionalmente, en reportes PDF).
* Las exportaciones permiten ajustarse a las **columnas seleccionadas** o predeterminadas.

---

## Comandos útiles

```bash
# Migraciones
php artisan migrate
php artisan migrate:rollback
php artisan migrate --path=/database/migrations/xxxx_xx_xx_xxxxxx_create__talble__certificadoinspeccion.php

# Modelo / Resource
php artisan make:model CertificadoInspeccion
php artisan make:filament-resource CertificadoInspeccion --model=App\\Models\\CertificadoInspeccion

# Servidor local
php artisan serve

# Limpieza de cachés
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---


=======
# grps-mldm
Sistema de Planificación de Recursos de Gubernamentales (Government Resource Planning System)
>>>>>>> 70ef79449570912a485715276b1a177d55b1840d
