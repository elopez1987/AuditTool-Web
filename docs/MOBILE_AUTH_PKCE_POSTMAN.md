# Autenticación móvil: integración Auth0, PKCE y Postman

## 1. Objetivo y alcance

Validar el flujo real de **Authorization Code con PKCE**, desde el login en Auth0 hasta la consulta del perfil local en Laravel. Postman representa al cliente público que después será la aplicación Flutter.

El flujo es:

1. Postman genera un verifier y un challenge PKCE y abre `/authorize` en el navegador.
2. Auth0 autentica al usuario, aplica MFA y solicita consentimiento cuando corresponde.
3. Auth0 devuelve un código al callback de Postman.
4. Postman intercambia el código y el verifier en `/oauth/token`, sin client secret.
5. Postman envía el **access token** a `GET /api/mobile/auth/me`.
6. Laravel valida el token y permite acceder únicamente a un usuario local activo vinculado por `users.auth0`.

Laravel no recibe la contraseña, el código de autorización ni el refresh token. No existen endpoints móviles Laravel de login, refresh o logout. El `/oauth/token` local de Passport pertenece a otro flujo y no debe utilizarse en esta prueba.

Esta guía describe resultados esperados, no certifica que el tenant o dispositivos reales ya se hayan probado. La prueba Postman tampoco sustituye la validación posterior de callbacks, almacenamiento seguro y ciclo de sesión en Android/iOS.

## 2. Preparación

- Usar un tenant, cliente y usuario de **desarrollo o pruebas**, no cuentas productivas.
- Tener acceso administrativo al tenant para configurar la API y la aplicación.
- Utilizar Postman Desktop para acceder al dominio local de Herd. El agente cloud de Postman no resuelve necesariamente `audittool.test`.
- Confirmar que Laravel puede conectarse a su SQL Server y al endpoint HTTPS de claves JWKS de Auth0.
- Mantener la validación TLS activada. Si Postman no confía en el certificado local, instalar/confiar en la CA de desarrollo correspondiente; no desactivar globalmente la verificación SSL.
- No compartir contraseñas, tokens, códigos de autorización o verifiers en capturas, repositorios, tickets ni consolas. Mantener desactivados **Share Token / Sync Token**; usar Postman Vault para tokens que se copien manualmente.

El proveedor implementado es **Auth0**, aunque partes de la aplicación lo denominen “Okta”. Estas instrucciones no corresponden a Okta Identity Engine.

### Valores que deben coincidir

| Valor | Origen | Uso |
| --- | --- | --- |
| Dominio de Auth0 | Dominio del tenant o custom domain elegido | Emisor y endpoint JWKS |
| Audience | **Identifier** de la API en Auth0 | Destinatario del access token |
| Client ID | Aplicación **Native** de pruebas | Cliente público autorizado |
| Callback | Callback exacto mostrado por Postman | Retorno del código de autorización |
| Scope | Permiso `mobile:access` de la API | Acceso al módulo móvil |
| Subject | `user_id` de Auth0 / claim `sub` | Vinculación con `users.auth0` |

**Audience, Client ID y callback son valores distintos.** El audience no es el Client ID, el callback ni la audiencia de Auth0 Management API.

## 3. Configurar Auth0

Los nombres de las pantallas pueden variar según la versión del Dashboard.

### 3.1. Crear o seleccionar la API

En **Applications → APIs**:

1. Crear una API de pruebas o seleccionar la API dedicada al backend móvil. No modificar una API compartida con producción para ejecutar esta guía.
2. Elegir un nombre descriptivo, por ejemplo `Audittool Mobile API - Test`.
3. Definir su **Identifier**, por ejemplo `https://audittool.example.test/api/mobile`.
4. Seleccionar **RS256** como algoritmo de firma.
5. Guardar el Identifier sin modificar mayúsculas, caracteres ni slash final: se utilizará literalmente como audience.
6. En **Permissions**, agregar `mobile:access`, con una descripción como “Acceso a la aplicación móvil”.
7. Configurar una duración de access token corta para pruebas, por ejemplo **600 segundos**. Este valor se configura en Auth0, no en Laravel.
8. Para probar renovación, habilitar **Allow Offline Access** en la API.

El Identifier es un identificador lógico; no necesita ser una URL navegable ni coincidir con la URL local de Herd.

### 3.2. Configurar RBAC y el permiso del usuario

Para comprobar concesión explícita de acceso en la API de pruebas:

1. Activar **Enable RBAC** en la API.
2. Asignar `mobile:access` al usuario de pruebas, directamente o mediante un rol de Auth0.
3. Confirmar la asignación en **User Management → Users → usuario → Permissions**.

Con RBAC activo, el `scope` emitido contiene la intersección entre permisos solicitados y asignados. **Definir el permiso no basta: hay que solicitarlo y concederlo al usuario.**

**Add Permissions in the Access Token** es opcional para esta integración: Laravel exige `mobile:access` en el claim **`scope`**; tenerlo solamente en `permissions` no satisface ese requisito. Si hay Actions que modifican scopes, revisar su resultado.

Los roles de Auth0 controlan aquí la concesión del scope. Los roles y permisos funcionales que devuelve el perfil siguen siendo los de **Spatie en SQL Server**, con guard `web`; no se sincronizan automáticamente entre ambos sistemas.

### 3.3. Crear la aplicación Native de Postman

En **Applications → Applications**:

1. Crear una aplicación dedicada, por ejemplo `Audittool Postman PKCE - Test`.
2. Seleccionar tipo **Native**.
3. Anotar su **Client ID** y el dominio público del tenant.
4. Confirmar que **Token Endpoint Authentication Method / Authentication Method** sea **None**. Si el Dashboard no muestra este campo, comprobar que la aplicación funciona como cliente público. No cambiarla a aplicación confidencial para resolver errores.
5. En **Grant Types**, habilitar **Authorization Code** y, para renovación, **Refresh Token**.
6. No habilitar Password Grant o Client Credentials para esta prueba.
7. En **Allowed Callback URLs**, registrar exactamente:

   `https://oauth.pstmn.io/v1/browser-callback`

   Si tu versión de Postman muestra otro callback, registrar ese valor exacto y usarlo también en Postman. No usar comodines.
8. Habilitar la conexión del usuario de pruebas en la sección **Connections** de la aplicación.
9. Revisar y autorizar el acceso de esta aplicación a la API si las políticas de acceso a APIs del tenant lo requieren.
10. Guardar los cambios.

No se necesita un client secret, un token de Management API ni un client de Passport. El callback de Postman no es el `/callback` de la aplicación web Laravel.

Para la aplicación Flutter real se configurarán aparte los App Links/Universal Links o esquemas de Android/iOS. No sustituirlos por el callback de Postman.

### 3.4. Configurar renovación y rotación

En la aplicación Native de pruebas:

1. Habilitar **Refresh Token Rotation**.
2. Establecer límites de expiración absoluta e inactividad según la política del entorno.
3. Registrar el **Rotation Overlap Period / Reuse Interval** configurado.
4. Guardar los cambios.

Para recibir un refresh token son necesarios conjuntamente el grant **Refresh Token**, **Allow Offline Access** en la API y el scope solicitado **`offline_access`**.

Las pruebas deben renovar de forma secuencial. Reutilizar un refresh token antiguo fuera de su intervalo de solapamiento puede invalidar la familia y exigir un login nuevo.

### 3.5. Elegir el usuario y la política de autenticación

- El usuario debe poder autenticarse mediante una conexión habilitada para este cliente.
- Mantener MFA y las políticas requeridas por el tenant; Universal Login se encarga de las pantallas adicionales.
- Registrar el `user_id` exacto de Auth0. No asumir que coincide con email, nickname o username.
- Si se usa un custom domain, elegir el mismo dominio en Postman y Laravel. El `iss` emitido debe coincidir exactamente con el dominio configurado en Laravel y su slash final.

## 4. Configurar Laravel

### 4.1. Variables de entorno

Editar la configuración únicamente en el entorno de pruebas autorizado. Ejemplo ficticio:

```dotenv
MOBILE_AUTH_ENABLED=true
MOBILE_AUTH0_DOMAIN=tu-tenant.auth0.com
MOBILE_AUTH0_AUDIENCE=https://audittool.example.test/api/mobile
MOBILE_AUTH0_CLIENT_IDS=client-id-native-postman
```

- `MOBILE_AUTH0_DOMAIN`: solo hostname, **sin** `https://`, path ni slash final.
- `MOBILE_AUTH0_AUDIENCE`: Identifier exacto de la API, no `https://.../api/v2/`.
- `MOBILE_AUTH0_CLIENT_IDS`: uno o varios Client IDs separados por comas. Incluir el cliente dedicado a Postman y no eliminar clientes ya autorizados del entorno.
- No agregar secretos de cliente ni reutilizar credenciales de Management API.
- No alterar las variables `AUTH0_*` del login web para habilitar la API móvil.

Si existe configuración Laravel cacheada, ejecutar después del cambio:

```bash
php artisan config:clear --no-interaction
```

En un despliegue que utiliza caché de configuración, regenerarla mediante su procedimiento habitual. No ejecutar `cache:clear` global ni reiniciar servicios como parte de esta prueba.

La configuración se encuentra en `config/mobile_auth.php`. Si falta un valor requerido o el módulo está deshabilitado, responde `503 MOBILE_AUTH_UNAVAILABLE`.

### 4.2. Vinculación y acceso local

Usando las herramientas administrativas autorizadas de la aplicación y una cuenta de pruebas, confirmar:

- Existe **un solo** registro local cuyo `users.auth0` coincide exactamente con el `user_id` / `sub` del usuario Auth0.
- `users.statusid = E`.
- `users.deleted_at IS NULL`.
- No hay otro registro con el mismo `auth0`, incluso eliminado: la implementación rechaza asociaciones ambiguas.
- El usuario tiene los roles y permisos locales esperados y sus accesos en `securityaccesswarehouses`.

No modificar usuarios productivos ni crear registros ad hoc para completar la guía. El login móvil no crea usuarios, no vincula por email y no compara la contraseña con `users.password`.

### 4.3. URL de la API

Para el sitio local se verificó con Laravel Boost:

`GET https://audittool.test/api/mobile/auth/me`

Para otro entorno, sustituir la base por la URL de ese sitio. El backend necesita salida HTTPS hacia:

`https://TU_DOMINIO_AUTH0/.well-known/jwks.json`

Las claves se cachean durante 600 segundos; el SDK busca nuevas claves si recibe un `kid` desconocido. Los tiempos del token admiten una tolerancia de reloj de 30 segundos.

## 5. Configurar Postman

### 5.1. Entorno y petición

Crear un entorno de pruebas con estas variables **públicas**, usando los valores del entorno elegido:

| Variable | Valor de ejemplo |
| --- | --- |
| `base_url` | `https://audittool.test` |
| `auth0_domain` | `tu-tenant.auth0.com` |
| `auth0_client_id` | Client ID Native de Postman |
| `auth0_audience` | Identifier exacto de la API |
| `auth0_callback` | `https://oauth.pstmn.io/v1/browser-callback` |

Crear la petición **Mobile - Me**:

- Método: **GET**.
- URL: `{{base_url}}/api/mobile/auth/me`.
- Header: `Accept: application/json`.
- Body: vacío.

### 5.2. Autorización OAuth 2.0

En **Authorization → OAuth 2.0 → Configure New Token**:

| Campo | Valor |
| --- | --- |
| Token Name | `Audittool Mobile PKCE Test` |
| Add authorization data to | Request Headers |
| Header Prefix | `Bearer` |
| Grant Type | Authorization Code (With PKCE) |
| Authorize using browser | Activado |
| Callback URL | `{{auth0_callback}}` |
| Auth URL | `https://{{auth0_domain}}/authorize` |
| Access Token URL | `https://{{auth0_domain}}/oauth/token` |
| Client ID | `{{auth0_client_id}}` |
| Client Secret | Vacío |
| Code Challenge Method | SHA-256 / S256 |
| Code Verifier | Dejar que Postman genere uno nuevo por flujo |
| Scope | `openid profile email offline_access mobile:access` |
| State | Valor aleatorio nuevo por intento; usar el generador si la versión lo ofrece |
| Client Authentication | Send client credentials in body, sin secret |

Si tu versión ofrece **None** como autenticación de cliente, usarla y confirmar que el token request incluye `client_id`, pero no Authorization Basic ni `client_secret` no vacío. No utilizar la autenticación Basic del cliente web.

Un verifier manual debe ser aleatorio, de 43 a 128 caracteres. No reutilizar el de otro intento.

En **Advanced Options → Auth Request**, agregar:

| Key | Value | Ubicación |
| --- | --- | --- |
| `audience` | `{{auth0_audience}}` | URL / query parameters |

El audience debe viajar en la petición **`/authorize`**, no solamente en el token request. Algunas versiones de Postman ubican parámetros importados en Token Request: comprobar y corregir esta ubicación. No agregar dos parámetros `audience`.

Configurar **Refresh Token URL**, si aparece, como `https://{{auth0_domain}}/oauth/token`.

### 5.3. Obtener y utilizar el token

1. Seleccionar **Get New Access Token**.
2. Completar Universal Login en el navegador y MFA si corresponde.
3. Aceptar el consentimiento solicitado.
4. Autorizar el regreso a Postman.
5. Seleccionar **Proceed / Use Token**.
6. Confirmar que la petición GET utiliza el **access token**, no el ID token.
7. Enviar **Mobile - Me**.

Postman debe enviar un único header `Authorization: Bearer ...`. No enviar usuario/contraseña en Body, no usar cookies Laravel para autorizar esta petición y no pegar el token en la URL.

## 6. Prueba positiva

Resultado esperado: **HTTP 200** con una estructura como la siguiente; los datos son ilustrativos:

```json
{
  "data": {
    "id": 123,
    "uuiduser": "a8d50b98-d09b-44a8-ab54-5a18449de916",
    "username": "mobile.user",
    "name": "MOBILE USER",
    "email": "mobile.user@example.test",
    "roles": ["OPERATOR"],
    "permissions": ["receipts.read"],
    "warehouse_access": [
      {"centerid": "CD01", "warehouseid": "WH01"}
    ]
  }
}
```

Verificar:

- Identidad del usuario **local** correcto.
- Roles y permisos locales esperados; no se agregan permisos por tener un token válido.
- Pares centro/bodega exactos, sin accesos de otro usuario.
- `roles`, `permissions` o `warehouse_access` pueden estar vacíos si no existen asignaciones.
- Header `Cache-Control` contiene `no-store`.
- No hay `Set-Cookie`, redirección al login ni contraseñas/tokens en el JSON.

### Aserciones Postman

Agregar en **Scripts → Post-response** de la petición positiva:

```javascript
pm.test('Profile request succeeds', function () {
    pm.response.to.have.status(200);
});

pm.test('Profile uses the documented contract', function () {
    const profile = pm.response.json().data;
    pm.expect(profile).to.be.an('object');
    ['id', 'uuiduser', 'username', 'name', 'email', 'roles', 'permissions', 'warehouse_access']
        .forEach(function (key) {
            pm.expect(profile).to.have.property(key);
        });
    ['roles', 'permissions', 'warehouse_access'].forEach(function (key) {
        pm.expect(profile[key]).to.be.an('array');
    });
    ['password', 'remember_token', 'access_token', 'refresh_token', 'id_token']
        .forEach(function (key) {
            pm.expect(profile).not.to.have.property(key);
        });
});

pm.test('Profile is not cached or backed by a session cookie', function () {
    pm.expect(pm.response.headers.get('Cache-Control') || '').to.include('no-store');
    pm.expect(pm.response.headers.has('Set-Cookie')).to.eql(false);
});
```

Para comprobar identidad y autorización, agregar las expectativas específicas del usuario de pruebas. No usar solamente la presencia de campos como evidencia de autorización correcta.

## 7. Renovar y revocar credenciales

Estas peticiones se envían a **Auth0**, no al sitio Laravel. Deben usar **No Auth** en Postman para no heredar un bearer token o credenciales Basic de la colección.

### 7.1. Renovación

Puede utilizarse **Refresh** en el administrador OAuth de Postman. Para probar manualmente:

1. Guardar el refresh token actual en Postman Vault con el nombre `mobile_refresh_token`.
2. Crear `POST https://{{auth0_domain}}/oauth/token`.
3. Seleccionar **Body → x-www-form-urlencoded**:

| Key | Value |
| --- | --- |
| `grant_type` | `refresh_token` |
| `client_id` | `{{auth0_client_id}}` |
| `refresh_token` | `{{vault:mobile_refresh_token}}` |

4. Enviar una sola petición y esperar la respuesta.
5. Confirmar `200` y un nuevo access token. Con rotación activa, guardar el refresh token nuevo, sustituyendo el anterior en Vault.
6. Usar el nuevo access token en **Mobile - Me**, con Authorization **Bearer Token**, y esperar `200`.

No ejecutar la renovación manual y la automática simultáneamente. Reutilizar tokens anteriores puede revocar la familia. Una rotación exitosa por sí sola no demuestra que el usuario continúe autorizado localmente: siempre comprobar `/me`.

### 7.2. Revocación y cierre

1. Crear `POST https://{{auth0_domain}}/oauth/revoke`.
2. Seleccionar **No Auth** y **Body → x-www-form-urlencoded**:

| Key | Value |
| --- | --- |
| `client_id` | `{{auth0_client_id}}` |
| `token` | `{{vault:mobile_refresh_token}}` |
| `token_type_hint` | `refresh_token` |

3. Enviar y esperar `200`. La respuesta puede tener body vacío y no prueba por sí sola que el token presentado fuera válido.
4. Intentar renovar utilizando ese mismo refresh token. Esperar rechazo de Auth0, normalmente `invalid_grant`; considerar la propagación de la revocación.
5. Eliminar las credenciales locales de Postman/Vault al terminar.

**No esperar que `/me` rechace inmediatamente un access token aún vigente.** Revocar el refresh token no agrega ese JWT a una denylist Laravel. Puede seguir siendo aceptado hasta `exp`, más la tolerancia de reloj.

Cerrar la sesión SSO del navegador es otra operación: usar el mecanismo de logout del proveedor/SDK cuando se pruebe ese comportamiento. No usar el logout web Laravel como prueba de revocación móvil.

Revisar la opción de tenant **Refresh Token Revocation Deletes Grant** antes de probar. La revocación puede afectar otros refresh tokens del mismo usuario, aplicación y audiencia; no prometer un cierre aislado por dispositivo.

## 8. Pruebas negativas

Duplicar la petición positiva y quitar sus aserciones de éxito antes de ejecutar cada caso. Restablecer siempre el estado de la cuenta de pruebas.

| Caso | Procedimiento | Resultado esperado |
| --- | --- | --- |
| Sin token | No Auth, eliminar Authorization heredado | `401 UNAUTHENTICATED` |
| Token malformado o alterado | Bearer Token inválido | `401 UNAUTHENTICATED` |
| ID token | Usar el `id_token` en vez del access token | `401 UNAUTHENTICATED` |
| Access token vencido | Desactivar auto-refresh y esperar más allá de `exp + 30 s` | `401 UNAUTHENTICATED` |
| Otro cliente | Token válido de un cliente de pruebas fuera de la allowlist | `401 UNAUTHENTICATED` |
| Otro audience/emisor | Token real de otra API/tenant de pruebas | `401 UNAUTHENTICATED` |
| Sin scope móvil | Solicitar un token nuevo sin `mobile:access` y confirmar que el scope no fue concedido | `403 MOBILE_ACCESS_DENIED` |
| Usuario no vinculado | Usuario Auth0 de pruebas sin correspondencia en `users.auth0` | `403 MOBILE_ACCESS_DENIED`; no crea usuario |
| Usuario inactivo | Desactivar localmente la cuenta de pruebas con la herramienta administrativa autorizada | `403 MOBILE_ACCESS_DENIED` incluso con token vigente |
| Usuario eliminado o vínculo duplicado | Usar fixtures aprobados del entorno o la suite automatizada; no duplicar usuarios reales | `403 MOBILE_ACCESS_DENIED` |
| Configuración inválida/deshabilitada | En un entorno aislado, deshabilitar el módulo y recargar configuración | `503 MOBILE_AUTH_UNAVAILABLE` |
| Límite de solicitudes | En entorno aislado, superar 60 peticiones en un minuto desde la misma IP | `429 TOO_MANY_REQUESTS` |

No modificar manualmente los claims de un JWT para simular otro audience o scope: eso invalida la firma y solo comprueba rechazo de firmas alteradas.

Las solicitudes de una misma IP comparten el límite, incluso si pertenecen a distintos usuarios. Esperar la ventana indicada por `Retry-After` antes de continuar.

La caída de JWKS y la rotación de claves se cubren con el proveedor simulado de la suite; no rotar claves del tenant ni bloquear red productiva para probar esos casos.

## 9. Diagnóstico

### Auth0 rechaza antes de regresar a Postman

- **Callback URL mismatch:** comparar el callback exacto en Auth0 y Postman. No reemplazarlo por `/callback` de Laravel.
- **invalid_client / unauthorized_client:** verificar Client ID, aplicación Native, método de autenticación None, conexión habilitada, grant Authorization Code y políticas de acceso a la API.
- **Invalid code verifier / invalid_grant durante el canje:** generar un flujo nuevo con PKCE S256. El código es de un solo uso y debe canjearse con el mismo verifier y redirect URI.
- **No se recibió refresh token:** comprobar grant Refresh Token, Allow Offline Access y `offline_access`.
- **Login rechazado por MFA/políticas:** resolver la política o asignación del usuario; no desactivar MFA para hacer pasar la prueba.

### Laravel devuelve 401

Comprobar, sin enviar tokens a decodificadores externos:

- Se utilizó access token y no ID token o un token de Passport.
- `iss` coincide con `https://MOBILE_AUTH0_DOMAIN/`.
- `aud` contiene el Identifier configurado en `MOBILE_AUTH0_AUDIENCE`.
- `azp` o `client_id` pertenece a `MOBILE_AUTH0_CLIENT_IDS`. Si ambos aparecen, deben coincidir.
- La firma es RS256 con una clave publicada por el emisor configurado.
- `iat`/`exp` tienen valores numéricos válidos; el token no está vencido ni anticipado mediante `iat` o `nbf`.
- Se trata de un usuario, no de un sujeto de máquina terminado en `@clients`.

Decodificar un JWT localmente sirve para diagnóstico; **no valida su firma**.

### Laravel devuelve 403

- Confirmar `mobile:access` en **`scope`**. Revisar asignación RBAC y Actions en Auth0.
- Confirmar correspondencia exacta `sub = users.auth0`, estado `E`, ausencia de SoftDeletes y unicidad del vínculo.
- No intentar resolverlo cambiando `users.password`: esa columna no participa en este flujo.

### Laravel devuelve 503

- Revisar activación, dominio sin esquema, audience y allowlist no vacíos, y caché de configuración.
- Revisar DNS, TLS y salida HTTPS desde Laravel hacia las claves del dominio elegido.
- La API devuelve el mismo código genérico para configuración incompleta o errores de red del SDK. Una respuesta JWKS inválida o sin clave utilizable puede terminar en `401`; no interpretar todo fallo de claves como indisponibilidad.

### Problemas locales de Postman

- Usar Desktop/desktop agent y comprobar acceso a `audittool.test`.
- Confiar en la CA local sin desactivar la verificación TLS.
- Revisar que la petición no tenga dos headers Authorization, cookies heredadas o un refresh automático que invalide el caso negativo.
- Inspeccionar la consola únicamente en una sesión privada y sin exportarla: puede contener tokens, códigos y verifiers.

## 10. Evidencia y criterio de aceptación

Registrar sin credenciales:

1. Fecha, entorno, versión del backend y cliente Native de pruebas utilizado.
2. Usuario local de pruebas, resultado de `/me` y verificación de identidad/permisos/accesos.
3. Login PKCE y MFA, si la política lo requiere, completados.
4. Renovación con rotación y posterior `/me` satisfactorios.
5. Revocación que impide renovar y observación de la vigencia residual del access token.
6. Casos negativos ejecutados y códigos obtenidos.
7. Cambios temporales revertidos y tokens de pruebas eliminados.

No incluir valores de tokens, passwords, códigos de autorización, verifiers o secretos en la evidencia.

### Pruebas automatizadas complementarias

Desde la raíz del proyecto:

```bash
vendor/bin/pest tests/Feature/MobileAuthenticationTest.php
```

La suite usa SQLite en memoria, claves efímeras y JWKS simulados. No autentica contra el tenant real, no prueba dispositivos Flutter ni sustituye la comprobación de conectividad/esquema en SQL Server del entorno de integración.

## 11. Referencias de implementación y documentación oficial

- `config/mobile_auth.php`: parámetros de activación y confianza.
- `app/Services/Security/MobileAuthConfiguration.php`: configuración aislada del SDK.
- `app/Services/Security/MobileAuthenticationService.php`: cliente, scope y acceso local.
- `app/Http/Middleware/AuthenticateMobile.php`: errores y autenticación sin sesión.
- `app/Http/Resources/MobileUserResource.php`: contrato del perfil.
- `routes/api.php`: endpoint móvil y middleware.
- `tests/Feature/MobileAuthenticationTest.php`: pruebas automatizadas.
- [Auth0: Authorization Code con PKCE](https://auth0.com/docs/get-started/authentication-and-authorization-flow/authorization-code-flow-with-pkce/call-your-api-using-the-authorization-code-flow-with-pkce).
- [Auth0: RBAC para APIs](https://auth0.com/docs/get-started/apis/enable-role-based-access-control-for-apis).
- [Auth0: configuración de rotación](https://auth0.com/docs/secure/tokens/refresh-tokens/configure-refresh-token-rotation).
- [Auth0: revocación de refresh tokens](https://auth0.com/docs/secure/tokens/refresh-tokens/revoke-refresh-tokens).
- [Postman: OAuth 2.0 y PKCE](https://learning.postman.com/docs/sending-requests/authorization/oauth-20/).
