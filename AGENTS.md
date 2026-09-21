<laravel-boost-guidelines>

YMS Development Guidelines

> Reglas de trabajo para agentes y desarrolladores en este proyecto.
> Orden de precedencia ante conflictos: (1) instrucción explícita del usuario,
> (2) convención existente en el código, (3) estas guías, (4) defaults de Laravel.

***
Índice

Contexto y stack
Reglas base de trabajo
Herramientas: Boost, Artisan, Tinker, Herd
Estilo PHP
Arquitectura por capas
DTOs
Vistas y ViewModels
Validación
Excepciones y manejo de errores
Autenticación
Autorización
Base de datos y SQL Server
Migraciones
Modelos y Eloquent
Transacciones y concurrencia
Rendimiento de base de datos
Eventos y Listeners
Jobs y colas
Comandos y tareas programadas
Integraciones externas
Caché
Fechas, horas y zonas horarias
Configuración
Seguridad
Logging y observabilidad
Interfaces y abstracciones
Clean Code
Duplicación
Estrategia de pruebas
Formato de código
Frontend y bundling
Git y commits
Documentación y respuestas
Definition of Done

***
1. Contexto y stack

Esta es una aplicación Laravel. Debes dominar y respetar estas versiones exactas:

Paquete	Versión
php	8.4
laravel/framework	v13
laravel/passport	v13
laravel/prompts	v0
laravel/boost	v2
laravel/mcp	v0
laravel/pint	v1
pestphp/pest	v4
phpunit/phpunit	v12
Stack de infraestructura:

SQL Server como base de datos principal.
Blade como única capa de presentación: la aplicación sirve vistas, no respuestas de API.
Git como control de versiones.
Arquitectura modular por dominio.

Idioma

Código, nombres de clases, métodos, variables, tablas, columnas, commits y comentarios: inglés.
Documentación interna y explicaciones al usuario: español.
No mezclar idiomas dentro de un mismo identificador.

***
2. Reglas base de trabajo

Sigue todas las convenciones existentes en la aplicación. Antes de crear o editar un archivo, revisa archivos hermanos para confirmar estructura, enfoque y nomenclatura.
Usa nombres descriptivos para variables y métodos: isRegisteredForDiscounts, no discount().
Busca componentes existentes que puedas reutilizar antes de escribir uno nuevo.
Respeta la estructura de directorios actual; no crees carpetas base nuevas sin aprobación.
No cambies las dependencias de la aplicación sin aprobación.
Nunca modifiques módulos no relacionados con la tarea.
Nunca elimines funcionalidad existente sin aprobación explícita.
No crees scripts de verificación ni uses tinker cuando ya existen pruebas que cubren esa funcionalidad. Las pruebas unitarias y de feature son más importantes.

Activación de skills

Este proyecto tiene skills por dominio en **/skills/**. DEBES activar la skill relevante en cuanto empieces a trabajar en ese dominio — no esperes a atorarte.

Antes de un cambio arquitectónico

Analiza la implementación existente.
Identifica módulos similares.
Explica los cambios propuestos.
Espera aprobación si el cambio afecta a múltiples módulos.

***
3. Herramientas

Laravel Boost

Boost es un servidor MCP con herramientas específicas para esta aplicación. Prefiere las herramientas de Boost sobre alternativas manuales como comandos de shell o lecturas de archivos.
database-query para consultas de solo lectura, en lugar de SQL crudo en tinker.
database-schema para inspeccionar la estructura de una tabla antes de escribir migraciones o modelos.
get-absolute-url para resolver esquema, dominio y puerto correctos. Úsalo siempre antes de compartir una URL con el usuario.
browser-logs para leer logs, errores y excepciones del navegador. Solo los registros recientes son útiles; ignora los viejos.

Buscar documentación (IMPORTANTE)

Usa siempre search-docs antes de hacer cambios de código. No te saltes este paso. Devuelve documentación específica de las versiones instaladas automáticamente.
Pasa un arreglo packages para acotar resultados cuando sepas qué paquetes son relevantes.
Usa varias consultas amplias basadas en tema: ['rate limiting', 'routing rate limiting', 'routing']. Los resultados más relevantes vienen primero.
No agregues nombres de paquetes a las consultas; esa info ya se envía. Usa test resource table, no filament 4 test resource table.

Sintaxis de búsqueda:

Palabras sueltas = AND con stemming: rate limit requiere "rate" Y "limit".
"frases entre comillas" = coincidencia exacta de posición: "infinite scroll" exige palabras adyacentes en ese orden.
Combinaciones: middleware "rate limit".
Varias consultas para lógica OR: queries=["authentication", "middleware"].

Artisan

Ejecuta comandos Artisan directamente por línea de comandos (php artisan route:list). Usa php artisan list para descubrir comandos y php artisan [command] --help para revisar parámetros.
Inspecciona rutas con php artisan route:list. Filtra con --method=GET, --name=users, --path=api, --except-vendor, --only-vendor.
Lee configuración con notación de punto: php artisan config:show app.name, php artisan config:show database.default. O lee directamente los archivos de config/.
Pasa --no-interaction a todos los comandos Artisan para que funcionen sin intervención, junto con las --options correctas.

Tinker

Ejecuta PHP en contexto de la app para depurar. No crees modelos sin aprobación del usuario; prefiere pruebas con factories. Prefiere comandos Artisan existentes sobre código ad-hoc en tinker.
Usa comillas simples para evitar expansión del shell: php artisan tinker --execute 'Your::code();'
Comillas dobles solo para strings de PHP dentro: php artisan tinker --execute 'User::where("active", true)->count();'

Laravel Herd

La aplicación la sirve Herd en https?://[kebab-case-project-dir].test. Usa get-absolute-url para generar URLs válidas. Nunca ejecutes comandos para servir el sitio: siempre está disponible.
Usa el CLI herd para administrar servicios, versiones de PHP y sitios (herd sites, herd services:start <service>, herd php:list). herd list muestra todos los comandos.

Despliegue

Laravel puede desplegarse con Laravel Cloud, la vía más rápida para producción.
No modifiques configuración de servidor, pipelines de despliegue ni archivos de entorno sin aprobación explícita.

***
4. Estilo PHP

Usa siempre llaves en estructuras de control, incluso para cuerpos de una sola línea.
Usa promoción de propiedades en el constructor de PHP 8: public function __construct(public GitHub $github) { }. No dejes constructores vacíos sin parámetros salvo que sean privados.
Declara tipos de retorno explícitos y type hints en todos los parámetros: function isAccessible(User $user, ?string $path = null): bool.
Usa TitleCase para las claves de Enum: FavoritePerson, BestLake, Monthly.
Prefiere bloques PHPDoc sobre comentarios en línea. Reserva los comentarios en línea para lógica excepcionalmente compleja.
Usa definiciones de array shape en los PHPDoc.
Declara readonly en propiedades que no deban cambiar tras construirse.
Usa match en lugar de cadenas largas de switch/elseif cuando se evalúa un mismo valor.
Usa enums nativos (backed enums) en lugar de constantes de clase sueltas para conjuntos cerrados de valores.
No uses @ para suprimir errores.

***
5. Arquitectura por capas

La aplicación sigue una arquitectura modular. Cada capa tiene una responsabilidad única.

Controllers

Solo manejan peticiones HTTP.
Deben permanecer delgados.
No contienen lógica de negocio.
No consultan la base de datos directamente.
No contienen bloques try/catch para excepciones de negocio normales.
No contienen lógica de autorización.

Services

Contienen la lógica de negocio.
Coordinan operaciones de dominio.
Manejan transacciones cuando corresponde.
No ejecutan consultas Eloquent directamente.
Reciben datos ya validados.
No reciben objetos Request.
No devuelven respuestas HTTP.

Repositories

Encapsulan el acceso a datos.
No contienen reglas de negocio.
Usan Eloquent para las consultas.
Prefieren upsert() cuando se procesan múltiples registros.
No devuelven respuestas HTTP ni usan helpers de respuesta.

Models

Representan entidades de persistencia.
Las relaciones deben declararse explícitamente.
No contienen lógica de flujo de trabajo ni de autorización.

Requests

Validación y autorización a nivel de petición.

ViewModels

Preparan y transforman los datos que se entregan a las vistas Blade.
Son la única capa que arma la forma final de los datos de presentación.

Flujo de dependencias

Controller → Request (validación) → Service → Repository → Model
↓                                 ↓
ViewModel  ────────────────────→    DTO
↓
Blade view

Las dependencias van en una sola dirección. Un Repository nunca conoce un Service; un Service nunca conoce un Controller.
Inyecta dependencias por constructor. Evita usar facades y app() dentro de Services y Repositories; en Controllers las facades son aceptables si es la convención existente.
Un Service puede llamar a otro Service. Evita ciclos: si dos Services se necesitan mutuamente, la responsabilidad está mal repartida.

***
6. DTOs

Usa DTOs cuando una operación de servicio requiere un objeto estructurado con múltiples valores relacionados.

Un DTO debería:

Ser inmutable cuando sea posible.
Usar propiedades tipadas.
Representar datos de aplicación/dominio.
Evitar dependencias específicas de HTTP.

No crees DTOs para operaciones simples donde un arreglo basta.
No pases objetos Request a los Services.

Convención: construye el DTO desde el Form Request (por ejemplo, un método estático fromRequest() en el DTO o toDto() en el Request) y mantén esa decisión consistente en todo el proyecto.

***
7. Vistas y ViewModels

Esta aplicación solo entrega datos a vistas Blade. No expone respuestas de API, por lo que no se usan Eloquent API Resources ni versionado de API.

Regla principal

Los datos que llegan a una vista Blade DEBEN pasar por un ViewModel cuando la vista necesita más que un único modelo o colección simple.

Un Controller NO DEBE:

Armar arreglos de presentación a mano dentro del método.
Pasar decenas de variables sueltas a view().
Formatear datos (fechas, montos, etiquetas, estatus) antes de enviarlos a la vista.
Pasar el modelo crudo cuando la vista requiere datos derivados o combinados.

Un Controller DEBE:

public function index(YardFilterRequest $request, YardBoardViewModel $viewModel): View
{
return view('yards.index', $viewModel->forFilters($request->toDto()));
}

Responsabilidades del ViewModel

Un ViewModel DEBE:

Recibir datos ya obtenidos por un Service (o solicitarlos a un Service, nunca a un Repository ni a Eloquent directamente).
Exponer propiedades o métodos con nombres orientados a la vista: availableDoors(), statusLabel(), formattedArrival().
Encapsular el formateo de presentación: fechas en zona horaria del sitio, montos, etiquetas de estatus, clases CSS derivadas de estado, textos traducidos.
Ser específico de una vista o de un grupo pequeño de vistas relacionadas.
Tener tipos explícitos en todo lo que expone.

Un ViewModel NO DEBE:

Contener lógica de negocio. Decidir si una unidad puede salir del patio es del Service; decidir cómo se muestra ese estado es del ViewModel.
Ejecutar consultas Eloquent ni acceder a Repositories.
Escribir en la base de datos ni disparar efectos secundarios.
Manejar autorización.
Convertirse en un contenedor genérico compartido por vistas que no tienen relación entre sí.

Convenciones

Ubicación: app/ViewModels/{Modulo}/{Nombre}ViewModel.php. Sufijo ViewModel obligatorio.
Nombra el ViewModel por la vista o pantalla que sirve, no por la entidad: YardBoardViewModel, no YardViewModel.
Un ViewModel puede implementar Arrayable y pasarse con view('x', $viewModel->toArray()), o pasarse como objeto único (view('x', ['vm' => $viewModel])). Elige una sola convención y respétala en todo el proyecto.
Prefiere que el ViewModel se construya con datos ya resueltos (inyectando el DTO o la colección) sobre que reciba el Request.
Para vistas triviales que solo muestran un modelo sin transformación, pasar el modelo directamente es aceptable. No crees un ViewModel vacío solo por cumplir la regla.
Las vistas Blade no contienen lógica condicional compleja ni cálculos: si una vista necesita un if anidado o una operación, esa decisión se resuelve en el ViewModel.
Blade nunca llama a Services, Repositories ni consulta modelos (Model::where(...) dentro de una plantilla está prohibido).
Los View Composers se usan solo para datos globales de layout (menú, usuario actual, notificaciones), no como sustituto de un ViewModel.
Un ViewModel debe poder probarse de forma unitaria sin levantar HTTP ni base de datos.

Presentación general

Al generar enlaces a otras páginas, prefiere rutas nombradas y la función route().
Pagina siempre las colecciones que puedan crecer; la paginación se resuelve en el Service/Repository y el ViewModel solo la expone a la vista.
Usa los códigos HTTP correctos también en flujos web: 302 tras una escritura exitosa (patrón POST-redirect-GET), 403 sin permisos, 404 no encontrado, 419 sesión/CSRF expirado, 422 validación.
Los errores de validación y los mensajes de resultado se comunican por sesión (withErrors, with('status', ...)), no armando respuestas manuales.
Escapa toda la salida: {{ }} por defecto. {!! !!} solo con contenido que tú generas y con justificación.

Si en el futuro se requiere exponer una API

No agregues respuestas JSON ad-hoc dentro de los controladores de vistas. Plantéalo como un cambio arquitectónico (sección 2) y espera aprobación antes de introducir Resources, versionado o un grupo de rutas de API.

***
8. Validación

Toda entrada del cliente se valida en un Form Request. No uses $request->validate() en el controlador salvo que sea la convención existente del módulo.
Valida tipos, rangos, formatos y existencia de llaves foráneas (exists).
No confíes en la validación del frontend.
Los mensajes de validación deben ser útiles y no filtrar detalles internos del esquema.
Usa reglas personalizadas (Rule objects) cuando una validación se repita o tenga lógica propia.
Los Services asumen datos ya validados; no revalidan formato, pero sí validan reglas de negocio.

***
9. Excepciones y manejo de errores

Las excepciones de negocio DEBEN lanzarse desde la capa Service/Application.
Los Repositories NO DEBEN devolver respuestas HTTP.
Los Repositories NO DEBEN usar helpers de respuesta.
Los Controllers NO DEBEN contener try/catch para excepciones de negocio normales.
Las excepciones se manejan centralmente mediante el mecanismo de excepciones de Laravel siempre que sea posible.
Usa excepciones específicas de dominio/aplicación en lugar de RuntimeException genérica cuando la excepción representa una condición de negocio conocida.

Ejemplo:

CatalogEntityNotFoundException::centerAccess($username);

Reglas adicionales:

Cada excepción de dominio define su código HTTP y su mensaje de cara al cliente en un solo lugar (por ejemplo, implementando render() o registrándola en el handler).
No uses excepciones para control de flujo esperado.
Nunca expongas trazas, nombres de tabla, SQL ni rutas de archivo en respuestas de producción.
Si capturas una excepción de librería externa, envuélvela en una excepción de dominio conservando la original como previous.

***
10. Autenticación

Usa los mecanismos de autenticación de Laravel. No implementes esquemas propios.
La autenticación de la aplicación es por sesión web. Passport está instalado, pero no se usa para exponer una API propia: no lo introduzcas en rutas de vistas ni mezcles guards sin aprobación.
Los tokens deben tener expiración definida y scopes acotados al uso previsto.
Nunca registres, retornes ni persistas tokens, secretos o contraseñas en texto plano.
Nunca confíes en identificadores de usuario, tenant o cliente enviados por el cliente para decidir identidad: derívalos del token autenticado.
Las credenciales de integraciones máquina a máquina se manejan como secretos de configuración, no en base de datos sin cifrar ni en el repositorio.

***
11. Autorización

Usa Policies y Gates para las decisiones de autorización.

La autorización NO DEBE implementarse dentro de:

Controllers
Repositories
Models como lógica de flujo de trabajo
Plantillas Blade

Reglas:

Los Form Requests PUEDEN hacer autorización a nivel de petición.
Las Policies DEBERÍAN usarse cuando la autorización depende de un modelo o recurso de dominio.
Las reglas de negocio son distintas de las reglas de autorización y deben permanecer en la capa Service/dominio correspondiente.
Todo endpoint que exponga datos de un cliente, patio o centro debe verificar que el usuario autenticado tiene acceso a esa entidad. No asumas alcance por el simple hecho de estar autenticado.
Deniega por defecto: si no hay Policy aplicable, la operación no se permite.

***
12. Base de datos y SQL Server

SQL Server es la base de datos principal.
No asumas comportamiento específico de MySQL.
No generes SQL específico de MySQL.
Considera los tipos de dato y limitaciones de SQL Server.
Evita SQL crudo salvo que sea necesario.
Cuando uses SQL crudo, explica por qué.

Diferencias a tener presentes:

No existe LIMIT; SQL Server usa TOP u OFFSET ... FETCH NEXT. Deja que el query builder lo resuelva.
No existe tipo ENUM. Usa una columna corta con validación en la aplicación (enum de PHP) o una tabla de catálogo.
Usa nvarchar para texto con posibles caracteres no ASCII. Evita text/ntext (obsoletos): usa nvarchar(max).
Usa datetime2 en lugar de datetime.
El collation por defecto suele ser insensible a mayúsculas y acentos: no asumas comparaciones sensibles a mayúsculas ni las uses como mecanismo de unicidad.
La concatenación es + o CONCAT(), no ||.
SQL Server no permite múltiples rutas de cascada hacia la misma tabla. Define onDelete con cuidado y resuelve las cascadas conflictivas en la aplicación.
Hay un límite de tamaño para llaves de índice: cuida los índices sobre columnas nvarchar largas.
upsert() genera MERGE: verifica que las columnas únicas realmente tengan un índice único, o el resultado será incorrecto.
El parámetro de consulta tiene un límite de 2100 valores: divide los whereIn grandes en lotes (chunk).
Ten cuidado con la conversión implícita de tipos en filtros (nvarchar vs varchar): puede invalidar el uso de índices.

Reglas generales:

Nunca interpoles entrada del usuario dentro de SQL. Usa siempre bindings.
No ejecutes DDL en tiempo de ejecución fuera de migraciones.
Las consultas de solo lectura usadas para diagnóstico se hacen con database-query, no modificando código.

***
13. Migraciones

Las migraciones DEBEN:

Ser reversibles cuando sea práctico.
Usar tipos compatibles con SQL Server.
Definir índices de forma intencional.
Definir llaves foráneas cuando corresponda.
Evitar cambios destructivos sin aprobación explícita.

Reglas adicionales:

Nunca modifiques una migración ya desplegada. Crea una migración nueva para cada cambio de esquema.
Separa los cambios de esquema de los de datos. Los rellenos de datos masivos van en un comando o job, no dentro de una migración pesada.
Añade índices para las columnas usadas en filtros, joins y ordenamientos frecuentes; no agregues índices "por si acaso".
Nombra explícitamente índices y llaves foráneas cuando el nombre generado pueda exceder límites o ser ambiguo.
Evita dropColumn y renombrados en tablas grandes sin un plan acordado: en SQL Server pueden bloquear la tabla.
Los seeders deben ser idempotentes y no depender de datos preexistentes.

***
14. Modelos y Eloquent

Al crear modelos nuevos, crea también factories y seeders útiles. Pregunta al usuario si necesita algo más, revisando php artisan make:model --help.
Declara $fillable (o $guarded según la convención existente) de forma explícita. Nunca uses asignación masiva con $request->all().
Declara los casts de fechas, booleanos, decimales, enums y JSON. No parsees tipos manualmente en los Services.
Oculta campos sensibles con $hidden, pero la exposición real se controla en el Resource.
Los scopes de consulta viven en el modelo; la lógica de negocio no.
Usa Observers solo para efectos secundarios de persistencia consistentes y baratos. No escondas flujo de negocio en Observers.
Usa soft deletes solo cuando el dominio realmente requiera conservar el registro; entonces revisa que todas las consultas y llaves únicas lo contemplen.

***
15. Transacciones y concurrencia

Las transacciones se manejan en la capa Service, nunca en Repositories ni en Controllers.
Mantén las transacciones cortas. No hagas llamadas HTTP externas ni despaches jobs dentro de una transacción abierta; usa DB::afterCommit() o marca el job/evento para ejecutarse después del commit.
Cuando varias peticiones puedan modificar el mismo registro (movimientos de unidades, asignación de puertas, folios), usa bloqueo pesimista (lockForUpdate) o una restricción única que haga imposible el estado inválido.
En SQL Server, anticipa deadlocks en operaciones concurrentes: ordena los accesos de forma consistente y reintenta la operación completa cuando aplique.
No anides transacciones manualmente esperando savepoints implícitos sin verificar el comportamiento.

***
16. Rendimiento de base de datos

Antes de introducir una consulta dentro de un bucle, verifica si los datos pueden obtenerse con:

eager loading
joins
whereIn
subconsultas
operaciones en bloque

Reglas:

Evita consultas N+1.
No optimices prematuramente, pero considera los patrones de acceso a datos al manejar colecciones o conjuntos grandes.
Usa chunk(), chunkById() o lazy() para procesar grandes volúmenes; nunca cargues todo en memoria.
Selecciona solo las columnas necesarias en consultas pesadas.
Agrega y cuenta en la base de datos, no en PHP sobre colecciones completas.
Prefiere upsert(), insert() masivo y update() masivo sobre bucles de save().

***
17. Eventos y Listeners

Usa Events cuando la aplicación necesite comunicar que algo ocurrió.

Los Events DEBERÍAN representar hechos que ya sucedieron (nombres en pasado: UnitCheckedIn).
Los Listeners DEBERÍAN manejar reacciones a esos eventos.
No uses Events para esconder lógica de negocio central que debe ejecutarse síncronamente como parte de la operación original.
Los listeners lentos o que dependen de sistemas externos deben ser encolados.
Un listener no debe asumir el orden de ejecución respecto a otros listeners.
Un fallo en un listener no crítico no debe romper la operación original: maneja y registra el error.

***
18. Jobs y colas

Usa Jobs para:

Operaciones de larga duración.
Integraciones externas.
Procesamiento pesado.
Operaciones reintentables.
Trabajo que no necesita bloquear la petición HTTP.

Reglas:

Los Jobs NO DEBEN convertirse en una alternativa a los Services para la lógica de negocio central.
Un Job debe delegar la lógica de negocio a un Service o Action cuando la operación es compleja.
Los Jobs deben ser idempotentes: un reintento no puede duplicar efectos.
Define $tries, $backoff y $timeout explícitamente en jobs que llaman a sistemas externos.
Pasa identificadores, no modelos completos serializados con estado volátil; recarga desde la base de datos dentro del job.
Implementa failed() para registrar contexto útil cuando el job agote reintentos.
Usa colas separadas por criticidad cuando el volumen lo justifique; no metas todo en default.

***
19. Comandos y tareas programadas

Los comandos Artisan son una capa de entrada, igual que un Controller: delegan a Services y no contienen lógica de negocio.
Toda tarea programada debe poder ejecutarse manualmente y ser segura ante ejecuciones repetidas.
Usa withoutOverlapping() en tareas que puedan solaparse y onOneServer() cuando aplique.
Los comandos deben reportar resultado con códigos de salida y logging, no solo con echo.

***
20. Integraciones externas

Toda llamada a un sistema externo (WMS, TMS, ERP, servicios de terceros) se encapsula en una clase cliente dedicada. Los Services no usan el HTTP client directamente.
Define timeout y retry explícitos en cada llamada. Nunca dejes una petición sin timeout.
Nunca registres credenciales, tokens ni cuerpos completos con datos sensibles.
Valida y normaliza la respuesta externa antes de usarla; no confíes en su forma.
Registra un identificador de correlación por operación para poder rastrear el flujo completo entre sistemas.
Las fallas de un sistema externo no deben dejar la base de datos en estado inconsistente: confirma primero el efecto local o compensa explícitamente.
Los endpoints y credenciales van en config/services.php, nunca hardcodeados.

***
21. Caché

Cachea solo lo que sea costoso y tolere estar desactualizado.
Usa llaves de caché descriptivas y versionadas; incluye en la llave todo lo que afecte el resultado (cliente, centro, filtros).
Define siempre un TTL. Evita caché indefinida salvo con una estrategia de invalidación explícita.
Invalida la caché en el mismo Service que modifica el dato.
No caches datos por usuario en llaves compartidas.

***
22. Fechas, horas y zonas horarias

Almacena marcas de tiempo en UTC. Convierte a la zona horaria del sitio o del usuario solo al presentar.
Usa Carbon y los casts del modelo; no manipules fechas como strings.
Nunca uses now() implícito dentro de lógica que deba probarse: permite inyectar el tiempo o usa los helpers de viaje en el tiempo de las pruebas.
Sé explícito con el tipo: date cuando no hay hora, datetime2 cuando sí.
Considera los cambios de horario de verano en cálculos de duración y ventanas de operación.

***
23. Configuración

La configuración de la aplicación DEBE accederse mediante el sistema de configuración de Laravel.
No uses env() directamente fuera de los archivos de configuración.

// Correcto
config('services.example.url')

// Incorrecto
env('EXAMPLE_URL')

Toda variable de entorno nueva debe documentarse en .env.example con un valor de ejemplo seguro.
Los valores por defecto en config/ deben ser seguros para producción.
No commitees archivos .env.

***
24. Seguridad

Nunca:

Almacenar contraseñas manualmente.
Registrar contraseñas, tokens, secretos o credenciales.
Exponer campos sensibles en las vistas o en los datos que pasa el ViewModel.
Confiar en información de autorización provista por el cliente.
Usar entrada de usuario cruda en SQL.
Deshabilitar la protección CSRF sin justificación explícita.

Usa:

Autenticación de Laravel.
Policies/Gates.
Autorización en Form Requests.
Validación de Laravel.
Las utilidades de hashing.
Variables de entorno para secretos.

Reglas adicionales:

Valida archivos subidos: tipo real, tamaño y extensión. No los sirvas desde una ruta pública sin control de acceso.
Escapa toda salida en Blade; usa {!! !!} solo con contenido que tú generas y con justificación.
No expongas identificadores internos secuenciales cuando puedan usarse para enumerar recursos de otros clientes sin verificación de acceso.
Mantén las dependencias actualizadas ante vulnerabilidades reportadas (previa aprobación del cambio de versión).

***
25. Logging y observabilidad

Usa las utilidades de logging de Laravel.

Los logs DEBERÍAN:

Aportar contexto útil.
Usar el nivel adecuado.
Incluir los identificadores necesarios para diagnosticar.

Los logs NO DEBEN contener:

Contraseñas.
Tokens de acceso.
Secretos de API.
Información personal sensible.

Reglas adicionales:

No uses dd(), dump() ni salida de depuración en código de producción.
Niveles: debug desarrollo, info hechos de negocio relevantes, warning situaciones recuperables inesperadas, error fallos de operación, critical fallos que requieren atención inmediata.
Pasa contexto como arreglo estructurado, no concatenado en el mensaje: Log::info('Unit checked in', ['unit_id' => $id]);
Registra siempre las fallas de integraciones externas con endpoint, código de estado y correlación — sin credenciales.
No registres dentro de bucles sobre colecciones grandes.

***
26. Interfaces y abstracciones

No crees interfaces solo para agregar una capa de abstracción.

Usa una interfaz cuando:

Se esperan múltiples implementaciones.
La inversión de dependencias aporta un beneficio arquitectónico real.
Una integración externa necesita abstraerse.
Las pruebas o la sustitución requieren un contrato significativo.

No crees:

RepositoryInterface
↓
Repository

solo porque "la arquitectura pide una interfaz".

Prefiere clases concretas cuando hay una sola implementación y no existe una necesidad real de sustitución.

***
27. Clean Code

Prefiere:

Métodos pequeños y enfocados.
Responsabilidad única.
Nombres descriptivos.
Retornos tempranos.
Tipos explícitos.
Datos inmutables cuando aplique.
Clases pequeñas con responsabilidades cohesivas.

Evita:

Clases Dios.
Métodos Dios.
Anidamiento profundo.
Parámetros booleanos con significado poco claro.
Lógica duplicada.
Números mágicos.
Cadenas mágicas.
Código muerto.
Abstracciones prematuras.

No extraigas una clase o método salvo que la extracción mejore legibilidad, reutilización, testabilidad o separación de responsabilidades.

***
28. Duplicación

Evita duplicar lógica de negocio.

No extraigas automáticamente cada línea repetida a un helper.

Extrae lógica compartida cuando:

Representa el mismo concepto de negocio.
Cambia por la misma razón.
La reutilización mejora la mantenibilidad.

Prefiere la duplicación sobre una abstracción incorrecta cuando dos fragmentos solo se parecen pero representan conceptos de negocio distintos.

***
29. Estrategia de pruebas

Este proyecto usa Pest.

Crea pruebas con php artisan make:test --pest {name}. El argumento {name} no debe incluir el directorio de la suite: usa SomeFeatureTest, no Feature/SomeFeatureTest. Pasa --unit para prueba unitaria. La mayoría deben ser pruebas de feature.
Ejecuta pruebas con php artisan test --compact o filtra con php artisan test --compact --filter=testName.
NO elimines pruebas sin aprobación.

Reglas:

Todo comportamiento de negocio nuevo DEBE tener pruebas automatizadas.

Prefiere pruebas de Feature para:

Endpoints HTTP.
Autenticación.
Autorización.
Flujos de servicio que involucran persistencia.
Comportamiento de base de datos.

Usa pruebas unitarias para:

Lógica de negocio pura.
Value objects.
Algoritmos aislados complejos.

Además:

Las pruebas DEBEN verificar comportamiento, no detalles de implementación.
No mockees modelos de Eloquent innecesariamente.
Usa factories en lugar de construir registros manualmente. Revisa si la factory tiene estados personalizados antes de configurar el modelo a mano.
Faker: usa $this->faker->word() o fake()->randomDigit(), siguiendo la convención existente del proyecto.
Las pruebas DEBEN ser deterministas e independientes.
No deshabilites pruebas para que pase un cambio.
Cubre también los caminos de error: validación fallida, acceso denegado, recurso inexistente, conflicto de estado.
Falsea servicios externos (Http::fake(), Queue::fake(), Event::fake(), Mail::fake()). Las pruebas nunca deben salir a la red.
No dependas del orden de ejecución ni de datos residuales entre pruebas.

***
30. Formato de código

Si modificaste archivos PHP, debes ejecutar vendor/bin/pint --dirty --format agent antes de finalizar los cambios, para que el código cumpla el estilo del proyecto.
No ejecutes vendor/bin/pint --test --format agent; simplemente ejecuta vendor/bin/pint --format agent para corregir problemas de formato.

***
31. Frontend y bundling

Si el usuario no ve reflejado un cambio de frontend en la UI, puede ser que necesite ejecutar npm run build, npm run dev o composer run dev. Pregúntale.
Error Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest: ejecuta npm run build o pide al usuario que corra npm run dev / composer run dev.

***
32. Git y commits

Mantén los commits enfocados y relacionados con un solo cambio lógico.

No:

Modifiques archivos no relacionados.
Commitees artefactos generados salvo que sea necesario.
Commitees secretos o archivos de entorno.
Reescribas historia compartida sin aprobación explícita.

Antes de crear un commit:

Revisa los archivos modificados.
Revisa el diff.
Ejecuta las pruebas relevantes.
Ejecuta el formateador de código.

Además:

Trabaja en ramas por tarea; no commitees directamente sobre la rama principal.
Escribe mensajes en imperativo y en inglés, describiendo el porqué cuando no sea obvio.
No commitees código comentado ni dd()/dump() olvidados.

***
33. Documentación y respuestas

Solo crea archivos de documentación si el usuario lo pide explícitamente.
Sé conciso en las explicaciones: enfócate en lo importante, no en detalles obvios.
Cuando un cambio afecte las vistas, el esquema de base de datos o la configuración de entorno, menciónalo explícitamente en la respuesta aunque no generes documentación.

***
34. Definition of Done

Un cambio está terminado cuando:

Sigue las convenciones existentes del módulo.
La lógica está en la capa correcta.
La entrada está validada y la autorización verificada.
Los errores de negocio usan excepciones de dominio.
No introduce consultas N+1 ni consultas dentro de bucles evitables.
No registra ni expone datos sensibles.
Tiene pruebas automatizadas que cubren el comportamiento nuevo y sus caminos de error.
php artisan test --compact pasa.
vendor/bin/pint --dirty --format agent fue ejecutado.
El diff está revisado y no toca archivos no relacionados.

</laravel-boost-guidelines>