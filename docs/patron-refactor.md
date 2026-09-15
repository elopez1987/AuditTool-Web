# Patrón de refactorización de controladores legacy (web + Blade)

Documento de referencia del patrón aplicado en **Centros de distribución**
(`systemcenterdistributions`) y **Bodegas** (`systemwarehouses`). Sirve como guía
para replicarlo en el resto de los módulos.

Implementación de referencia (la más completa):
`app/Http/Controllers/Configuration/CenterDistribution/SystemCenterDistributionController.php`

> Aplica a pantallas **web con vistas Blade + DataTables server-side**. No es un
> patrón para API/JSON.

---

## 1. Objetivo del patrón

Sacar del controlador todo lo que no sea HTTP, sin tocar las vistas Blade
existentes:

- **Queries** → Repository
- **Transacciones, logs y orquestación** → Service
- **Permisos** → Policy (delegando a los permisos legacy ya asignados a los roles)
- **Validación** → FormRequest
- **Variables que consume el Blade** → ViewModel
- **Respuestas/redirects con flash** → trait `Responser`

El controlador queda en: `autorizar → delegar → responder`.

---

## 2. Capas y flujo general

```
Route (resource + rutas "verticales")
   │
   ▼
FormRequest ─────────► solo validación (authorize() = true)
   │
   ▼
Controller extends BaseController
   ├─ $this->authorize(...) ──────► Policy ──► $user->can('permiso.legacy')
   ├─ $this->service->...   ──────► Service  (DB::transaction + logError + re-throw)
   │                                   └────► Repository (Interface → Eloquent) ──► Model
   ├─ $this->viewModel->forX(...) ─► ViewModel (array de variables del Blade)
   └─ view(...) | successResponse() | infoResponse() | errorResponse()   [trait Responser]
```

### Responsabilidades (reglas duras)

| Capa | Sí | No |
|---|---|---|
| **Controller** | `authorize()`, llamar al service, elegir vista/redirect | queries, `DB::`, `try` con lógica, armar arrays para el Blade |
| **FormRequest** | `rules()`, `messages()`, `prepareForValidation()` | autorizar (siempre `return true`) |
| **Policy** | traducir habilidad → permiso legacy (`$user->can('...')`) | queries de negocio |
| **Service** | transacciones, orquestar varios repos, filesystem, log de errores, re-lanzar | Eloquent directo (salvo catálogos triviales), `view()`, `redirect()` |
| **Repository** | Eloquent, scoping por accesos del usuario, caché de accesos | transacciones, logs, HTTP |
| **ViewModel** | `compact()` con los nombres exactos que ya usa el Blade | queries, lógica de negocio |
| **Model** | `$fillable`, `$casts`, relaciones, scopes | lógica de aplicación |

---

## 3. Mapa de archivos de la implementación de referencia

| Capa | Archivo |
|---|---|
| Rutas | `routes/Configuracion.php:56-71` |
| Controller principal | `app/Http/Controllers/Configuration/CenterDistribution/SystemCenterDistributionController.php` |
| Controllers verticales | `CenterParameterController.php`, `NotificationCenterController.php`, `SystemCenterDistributionFolderController.php` (misma carpeta) |
| Base del controller | `app/Http/Controllers/BaseController.php` → traits `Responser` + `GetParameters` |
| Requests | `app/Http/Requests/Web/Configuration/CenterDistribution/*.php` (uno por acción) |
| Service | `app/Http/Services/Configuration/ServiceSystemCenterDistribution.php` |
| Repository | `app/Repositories/SystemCenterDistributionRepositoryInterface.php` + `app/Repositories/Eloquent/SystemCenterDistributionRepository.php` |
| Binding DI | `app/Providers/RepositoryServiceProvider.php` (`$repositoryBindings`) |
| Policy | `app/Policies/SystemCenterDistributionPolicy.php` + `app/Providers/AuthServiceProvider.php` (`$policies`) |
| ViewModel | `app/ViewModels/SystemCenterDistributionViewModel.php` |
| Vistas | `resources/views/Configuration/SystemCenterDistributions/**` |

### Convenciones de nombres y ubicación

```
app/Http/Controllers/{Modulo}/{SubModulo}/{Model}Controller.php
app/Http/Requests/Web/{Modulo}/{SubModulo}/{Accion}{Model}Request.php
app/Http/Services/{Modulo}/Service{Model}.php
app/Repositories/{Model}RepositoryInterface.php
app/Repositories/Eloquent/{Model}Repository.php
app/Policies/{Model}Policy.php
app/ViewModels/{Model}ViewModel.php
resources/views/{Modulo}/{Models}/...
```

Notas: los services viven bajo `app/Http/Services` y llevan prefijo `Service`
(no es lo ideal, pero **se respeta** para no fragmentar el proyecto);
los ViewModels están en un namespace plano `App\ViewModels`.

---

## 4. Flujo detallado por acción

### 4.1 Listado (`index` + DataTables server-side)

1. `GET /systemcenterdistributions` → `index()`:
   `authorize('viewAny', Model::class)` y devuelve **solo la vista**, sin datos.
2. `index.blade.php` renderiza la tabla vacía e incluye
   `partials/table.blade.php`, que hace AJAX a la ruta de datos enviando los
   filtros del formulario (`d.name = $('input[name=name]').val()`).
3. `getDataCenterDistributions(IndexSystemCenterDistributionRequest)` →
   `service->search($request->validated('name'))`.
4. El repository devuelve un **`Builder`** (nunca `get()`), para que DataTables
   filtre/ordene/pagine en SQL.
5. `showDataToView($data, '...partials.buttons')` (`Responser`) envuelve con
   `DataTables::of()` y agrega la columna `btn` renderizando el partial de
   botones, que a su vez usa `@can(...)` por fila.

El **scoping de seguridad vive en el repository**
(`whereIn('centerid', $this->getAccessibleCenterIds())`), no en el controlador.

Si la columna viene de una relación, hay que declarar `name` en el JS para que
ordenar/buscar funcione:

```js
{data : 'status.name', name : 'status.name'}
```

### 4.2 `create` / `store`

```php
public function create()
{
    $this->authorize('create', Model::class);

    // catálogos → service → ViewModel (si la vista los necesita)
    return view("{$this->viewPath}.create");
}

public function store(StoreRequest $request): RedirectResponse
{
    $this->authorize('create', Model::class);

    try {
        $this->service->store($request->validated());
    } catch (Throwable) {
        return $this->errorResponse('No fue posible crear el registro.');
    }

    return $this->successResponse("{$this->routePath}.index");
}
```

Cuando la vista de alta necesita catálogos de otro dominio, se inyecta el
service correspondiente (ver `SystemWarehouseController::create()`, que usa
`ServiceSystemCenterDistribution::getCenterAll()`).

### 4.3 `edit` / `update`

- `edit(Model $model)` usa **route model binding implícito**: funciona con PK
  string porque el modelo declara `$primaryKey`, `$keyType = 'string'` e
  `$incrementing = false`. El nombre del parámetro **debe coincidir** con el de
  la ruta resource (`{systemcenterdistribution}` → `$systemcenterdistribution`).
- `update()` pasa `$request->validated()` al service. El `Request` completo se
  pasa **solo** si hay archivos (`$request->hasFile('file')` → `updateLogo()`).
- `update` responde con `infoResponse()` ("Registro modificado con exito"),
  `store` con `successResponse()`.

### 4.4 Sub-recursos / pestañas ("verticales")

Cada vertical (parámetros, parámetros de app, notificaciones, directorios) es un
**controller propio** que reutiliza **el mismo Service y el mismo ViewModel**:

```php
public function parameters(string $id)
{
    $center = $this->service->findOrFail($id);
    $this->authorize('parametrize', $center);

    $parameters = $this->service->getStatusForSchema('SDC');

    return view('...Parameters.create', $this->viewModel->forParameters($center, $parameters));
}
```

- Cada vertical tiene su **propia habilidad en el Policy**
  (`parametrize`, `parametrizeApp`, `notify`, `manageFolders`).
- Los esquemas de estatus se resuelven por código: `SDC` (parámetros del
  centro), `APP` (parámetros de app), `MCD` (notificaciones), `STS` (estatus
  generales), `FOLDERS` (catálogo de directorios).
- El PUT de una vertical regresa `redirectBackSuccess()` para quedarse en la
  misma pantalla, no en el index.

### 4.5 Service: transacciones, log y re-throw

```php
public function store(array $data): Model
{
    DB::beginTransaction();
    try {
        $model = $this->repository->create($data);
        DB::commit();

        return $model;
    } catch (Throwable $e) {
        DB::rollBack();
        $this->logError('Error al crear ...', $e);

        throw $e;              // el controller decide la respuesta HTTP
    }
}
```

`logError()` es privado y siempre registra `message`, `file`, `line`,
`user_id` (`Auth::id()`), `trace` y la PK del registro afectado.

Las operaciones de **filesystem van fuera de transacción** (una transacción de
BD no revierte archivos); ver `createFolders()` y la nota de `updateLogo()` en
`ServiceSystemCenterDistribution.php`.

---

## 5. Cómo replicar el patrón en otro modelo

Orden recomendado (cada paso compila contra algo que ya existe):

1. **Repository**
   - `app/Repositories/{Model}RepositoryInterface.php`
   - `app/Repositories/Eloquent/{Model}Repository.php`
   - Mínimo: `search(?string $name): Builder`, `findOrFail()`, `create()`,
     `update()` y, si aplica, `getAccessible*Ids(): array`.
   - Registrar en `RepositoryServiceProvider::$repositoryBindings`.
2. **Service** → `app/Http/Services/{Modulo}/Service{Model}.php`
   (transacciones + `logError()` privado + re-throw).
3. **Policy** → `app/Policies/{Model}Policy.php` delegando a los **strings de
   permiso que ya usan las vistas** (`$user->can('{prefijo}.edit')`), y
   registrarla en `AuthServiceProvider::$policies`.
4. **Requests** → uno por acción en
   `app/Http/Requests/Web/{Modulo}/{SubModulo}/`.
5. **ViewModel** → `app/ViewModels/{Model}ViewModel.php`, un método `forX()` por
   vista, con `compact()` usando **los nombres exactos** que ya espera el Blade.
6. **Controller** → `extends BaseController`, con `$routePath`/`$viewPath` y
   constructor con promoción de propiedades `private readonly`.
7. **Rutas** → `Route::resource(...)->except([...])` + la ruta `getDataX` del
   DataTable + las rutas verticales.
8. **Tests de feature** y `vendor/bin/pint --dirty --format agent`.

Las vistas **no se tocan**: el patrón está diseñado para dejarlas intactas. Si
hay que mover una query desde el Blade, pasa por el ViewModel.

### Checklist de PR

- [ ] Interface + implementación del repository, y binding en el provider
- [ ] Service con transacción en cada escritura y `throw $e` tras `rollBack()`
- [ ] Policy registrada en `AuthServiceProvider` y permisos alineados con los `@can` de los Blade
- [ ] `authorize()` en **todas** las acciones, incluida la ruta `getDataX`
- [ ] `Route::resource(...)->except([...])` acorde a los métodos que existen
- [ ] `search()` devuelve `Builder` (no `get()`) y el DataTable sigue en `serverSide: true`
- [ ] `store → successResponse`, `update → infoResponse`, vertical → `redirectBackSuccess`
- [ ] Nombres de variables del ViewModel idénticos a los del Blade
- [ ] `vendor/bin/pint --dirty --format agent` sin cambios pendientes
- [ ] Tests de feature: autorización denegada, filtros del listado, alta y edición

---

## 6. Consideraciones y trampas conocidas

Puntos detectados en la implementación de referencia. **Los stubs de
`docs/patron-refactor/stubs/` ya incorporan las correcciones**; si copias código
existente en lugar de los stubs, revisa esta lista.

> Varios de estos puntos ya están corregidos en el árbol de trabajo de
> `develop` (marcados como **corregido**); se documentan igual porque son las
> trampas que hay que evitar al replicar el patrón.

### 6.1 Riesgos a evitar

1. **Colisión de claves de caché de accesos.**
   `SystemCenterDistributionRepository::getAccessibleCenterIds()` guarda
   `SecurityAccessWarehouse::AccessScope()` (`['centers' => [...], 'warehouses' => [...]]`)
   bajo `security_centers_{userId}`, pero `Responser::getReceiptProviderForSelect()`
   guarda `SecurityAccessWarehouse::Centers()` (array de arrays) bajo **la misma
   clave**. Quien escriba primero contamina al otro.
   → Usa claves propias y explícitas (`access_scope_{$userId}`) y define dónde se
   invalidan cuando cambian los accesos del usuario.
2. **`Route::resource` sin `except`** (*corregido*). La ruta de centros
   registraba `show` y `destroy` sin que el controller los implemente → error al
   entrar a `systemcenterdistributions/{id}`. Declara siempre
   `->except(['show', 'destroy'])` acorde a los métodos existentes.
3. **`errorResponse($e->getMessage())` filtra detalles internos a la UI**
   (mensajes de SQL/infra). El service ya loguea el detalle: en la UI usa un
   mensaje amigable. Para eso existe `App\Exceptions\BusinessException`, que
   separa el mensaje del usuario (`getMessage()`) del detalle interno
   (`internalDetail()`): el service lanza la excepción de negocio y el
   controller solo muestra `getMessage()`.
4. **La ruta `getDataX` sin `authorize()`** (*corregido*). Estaba protegida solo
   por el middleware `auth` y el scoping del repository. Lleva su propio
   `authorize('viewAny', Model::class)`.
5. **Orden de `authorize()` y `findOrFail()`** en los controllers verticales.
   Con rutas `{id}` (sin binding), `authorize('ability', $model)` **necesita el
   modelo ya resuelto**: ponerlo antes del `findOrFail()` produce
   `Undefined variable $model` y revienta la acción. Si quieres evitar filtrar
   la existencia del registro a un usuario sin permiso, tienes dos opciones
   válidas:
   - usar **binding implícito** (`funcion(Model $model)`), donde Laravel
     resuelve el modelo antes de entrar al método, o
   - autorizar primero una habilidad **de clase**
     (`$this->authorize('ability', Model::class)`) y después resolver el modelo.

   Lo que no funciona es reordenar las dos líneas tal cual.
6. **`store` y `update` con el mismo flash** (*corregido*). `update()` usaba
   `successResponse()` y mostraba "Registro agregadó con exito" al modificar;
   ahora usa `infoResponse()`.

### 6.2 Inconsistencias de convención

7. **Nombres de permisos.** Mantén un solo esquema (`{prefijo}.{accion}`) entre
   Policy, controller y `@can` de los Blade. En centros ya está alineado
   (`systemcenterdistributions.create`, `.edit`, `.parametrizar`,
   `.parametrizarapp`, `.folder`, `.notification`).
8. **`search()` vs `searchAll()`.** `search()` devuelve `Builder` y `searchAll()`
   hace `get()` sin declarar tipo de retorno en la interface. Al replicar,
   nombra distinto lo que devuelve colección (`allActive(): Collection`) y tipa
   todo, incluida la interface.
9. **`$routePath` / `$viewPath`** solo los usan algunos controllers; los demás
   hardcodean strings. Los stubs los usan siempre.
10. **`$fillable` duplicado** (*corregido*). `SystemCenterDistribution` repetía
    `'code'`; revisa el modelo al copiar.

### 6.3 Decisiones del patrón que **sí** hay que mantener

11. `search()` devolviendo `Builder`: es lo que permite el DataTable
    server-side. No hagas `get()` (a menos que cambies el JS a
    `serverSide: false`, ver `docs/PERMISSION_CACHE.md`).
12. Eager loading explícito y acotado: `with(['status:statusid,name'])`.
13. Filesystem fuera de transacción, con comentario del **por qué**.
14. Rutas relativas al disco `local` (p. ej. `auxiliary04` = `public/ImageProfile/x.jpg`,
    sin prefijo `storage/`): si se prefijan, `Storage::exists()/delete()` fallan
    en silencio y los archivos viejos nunca se borran.
15. **`Gate::before` da bypass total al rol `ADMINISTRADOR`**
    (`AuthServiceProvider::boot()`): las policies no se evalúan para ese rol.
    Tenlo presente al probar permisos manualmente y en los tests.
16. Los ViewModels existen para **congelar los nombres de variables** que los
    Blade legacy ya consumen. No renombres esas claves sin tocar la vista.

### 6.4 Prerrequisito de tests

Hoy el proyecto **no tiene el andamiaje de tests configurado**: no existe
`phpunit.xml`, `tests/TestCase.php` ni `tests/Pest.php` (solo
`tests/Performance/`). Pest está instalado como dependencia. Antes de usar el
stub de test hay que inicializar ese andamiaje y una BD de pruebas; queda fuera
del alcance de este documento.

---

## 7. Stubs reutilizables

En `docs/patron-refactor/stubs/` hay una plantilla por capa, con placeholders
(`{{Model}}`, `{{routePath}}`, etc.) y las correcciones de la sección 6 ya
aplicadas. Ver `docs/patron-refactor/stubs/README.md` para la tabla de
placeholders y un ejemplo de sustitución completo.
