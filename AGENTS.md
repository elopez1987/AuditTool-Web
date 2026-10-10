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



</laravel-boost-guidelines>