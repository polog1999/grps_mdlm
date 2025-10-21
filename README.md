# ITSE App

Este sistema es una refactorización / migración del sistema anterior llamado "Sistema de Registro de Certificados ITSE".

Contexto de la migración
------------------------

Origen (sistema antiguo - devlocal):

La migración de la base de datos completa se realizó desde PostgreSQL versión 12.22 a la versión 18.0.

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
