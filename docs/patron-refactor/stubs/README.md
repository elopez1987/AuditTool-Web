# Stubs del patrón de refactorización

Plantillas copy-paste para replicar el patrón descrito en
[`docs/patron-refactor.md`](../../patron-refactor.md).

No son stubs de Artisan: se copian a mano, se renombran y se sustituyen los
placeholders. Ya incluyen las correcciones de la sección 6 del documento
(`authorize` antes de `findOrFail`, `authorize` en la ruta de datos,
`infoResponse` en `update`, clave de caché propia, mensaje de error amigable).

## Placeholders

| Placeholder | Significado | Ejemplo (centros) |
|---|---|---|
| `{{Model}}` | Clase del modelo | `SystemCenterDistribution` |
| `{{ModelNamespace}}` | Namespace del modelo | `App\Models\Configuration\CenterDistribution` |
| `{{variable}}` | Nombre de variable / parámetro de ruta (sin `$`) | `systemcenterdistribution` |
| `{{Module}}` | Carpeta de módulo (controllers, services, requests) | `Configuration` |
| `{{SubModule}}` | Subcarpeta del módulo | `CenterDistribution` |
| `{{routePath}}` | Prefijo de ruta / nombre del resource | `systemcenterdistributions` |
| `{{permissionPrefix}}` | Prefijo de los permisos legacy | `systemcenterdistributions` |
| `{{viewPath}}` | Ruta de vistas con puntos | `Configuration.SystemCenterDistributions` |
| `{{dataRoute}}` | Ruta/método del AJAX de DataTables | `getDataCenterDistributions` |
| `{{primaryKey}}` | Llave primaria del modelo | `centerid` |
| `{{table}}` | Tabla (para queries calificadas) | `systemcenterdistributions` |
| `{{entityLabel}}` | Etiqueta en español para logs/mensajes | `centro de distribución` |
| `{{Ability}}` / `{{ability}}` | Habilidad del Policy para una vertical | `Parametrize` / `parametrize` |
| `{{Vertical}}` | Nombre de la vertical (carpeta de vistas) | `Parameters` |
| `{{schemaid}}` | Código de esquema de estatus | `SDC` |

## Archivos y destino

| Stub | Destino |
|---|---|
| `RepositoryInterface.stub` | `app/Repositories/{{Model}}RepositoryInterface.php` |
| `Repository.stub` | `app/Repositories/Eloquent/{{Model}}Repository.php` |
| `Service.stub` | `app/Http/Services/{{Module}}/Service{{Model}}.php` |
| `Policy.stub` | `app/Policies/{{Model}}Policy.php` |
| `IndexRequest.stub` | `app/Http/Requests/Web/{{Module}}/{{SubModule}}/Index{{Model}}Request.php` |
| `StoreRequest.stub` | `app/Http/Requests/Web/{{Module}}/{{SubModule}}/Store{{Model}}Request.php` |
| `UpdateRequest.stub` | `app/Http/Requests/Web/{{Module}}/{{SubModule}}/Update{{Model}}Request.php` |
| `ViewModel.stub` | `app/ViewModels/{{Model}}ViewModel.php` |
| `Controller.stub` | `app/Http/Controllers/{{Module}}/{{SubModule}}/{{Model}}Controller.php` |
| `VerticalController.stub` | `app/Http/Controllers/{{Module}}/{{SubModule}}/{{Ability}}{{Model}}Controller.php` |
| `routes.stub` | bloque para `routes/{{Module}}.php` |
| `FeatureTest.stub` | `tests/Feature/{{Module}}/{{Model}}ControllerTest.php` |

`VerticalController.stub` y `FeatureTest.stub` son opcionales: el primero solo si
el modelo tiene pestañas/sub-recursos; el segundo requiere el andamiaje de tests
(ver sección 6.4 del documento).

## Pasos

1. Copia el stub al destino y quítale la extensión `.stub`.
2. Sustituye los placeholders. Con `sed` (macOS), para `StorageZone`:

   ```bash
   sed -e 's/{{Model}}/StorageZone/g' \
       -e 's/{{ModelNamespace}}/App\\Models\\Yard\\StorageZone/g' \
       -e 's/{{variable}}/storagezone/g' \
       -e 's/{{Module}}/Yard/g' \
       -e 's/{{SubModule}}/Zones/g' \
       -e 's/{{routePath}}/storagezones/g' \
       -e 's/{{permissionPrefix}}/storagezones/g' \
       -e 's/{{viewPath}}/Yards.StorageZones/g' \
       -e 's/{{dataRoute}}/getDataZones/g' \
       -e 's/{{primaryKey}}/id/g' \
       -e 's/{{table}}/storagezones/g' \
       -e 's/{{entityLabel}}/zona/g' \
       docs/patron-refactor/stubs/Controller.stub \
       > app/Http/Controllers/Yard/Zones/StorageZoneController.php
   ```

3. Registra el binding en `RepositoryServiceProvider::$repositoryBindings` y la
   policy en `AuthServiceProvider::$policies`.
4. Ajusta `rules()` de los requests a las columnas reales y los métodos del
   ViewModel a las variables que ya usa el Blade.
5. `vendor/bin/pint --dirty --format agent`.
