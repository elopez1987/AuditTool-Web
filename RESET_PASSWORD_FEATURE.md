# Funcionalidad de Envío de Correo de Reinicio de Contraseña

## 📋 Descripción
Se ha implementado una nueva funcionalidad que permite enviar correos de reinicio de contraseña directamente desde la vista de usuarios en el módulo de seguridad.

## ✨ Características

### 🔗 Ubicación
La opción se encuentra en el menú desplegable "Acciones" de cada usuario en la tabla de usuarios (`/securityusers`).

### 🎯 Funcionalidad
- **Botón**: "Enviar reset de contraseña"
- **Icono**: Utiliza el mismo ícono que "Enviar a Okta" (save02.png)
- **Disponibilidad**: Solo se muestra si el usuario tiene un email registrado

### 🔐 Seguridad
- **Confirmación**: Utiliza SweetAlert2 para confirmar la acción antes de enviarla
- **Logging**: Registra todos los intentos de envío (exitosos y fallidos)
- **Validación**: Verifica que el usuario tenga un email válido antes del envío

## 🚀 Implementación Técnica

### Archivos Modificados/Creados:

1. **Controlador Principal**: `app/Http/Controllers/Security/Users/UserController.php`
   - Método `sendPasswordReset()` agregado
   - Manejo de errores y logging implementado

2. **Controlador Personalizado**: `app/Http/Controllers/Auth/CustomResetPasswordController.php`
   - **NUEVO**: Controlador personalizado para reset sin auto-login
   - Evita conflictos con Auth0 al no intentar hacer login automático
   - Implementa reset de contraseña puro sin guards de Auth0

3. **Rutas**: `routes/Security.php`
   - Nueva ruta POST: `securityusers/{uuiduser}/send-password-reset`

4. **Rutas de Autenticación**: `routes/web.php`
   - Rutas personalizadas para reset de contraseña
   - Usa `CustomResetPasswordController` en lugar del estándar
   - Evita conflictos con el sistema Auth0

5. **Vista**: `resources/views/Security/Users/partials/buttons.blade.php`
   - Nuevo botón condicional agregado

6. **Vista de Reset**: `resources/views/auth/passwords/reset.blade.php`
   - **RENOVADA**: Diseño moderno consistente con el tema de la aplicación
   - Animaciones y efectos visuales mejorados
   - Toggle de visibilidad de contraseña
   - Validación visual en tiempo real

7. **JavaScript**: `resources/views/Security/Users/index.blade.php`
   - Función `sendPasswordReset()` implementada
   - Integración con SweetAlert2

8. **Modelo**: `app/User.php`
   - Interface `CanResetPassword` implementada
   - Trait `CanResetPassword` agregado

## 🔐 Compatibilidad con Auth0

### 🚨 Problema Resuelto
La aplicación utiliza Auth0 para autenticación, lo que generaba un conflicto con el sistema estándar de reset de contraseña de Laravel. El error era:

```
Auth0\Laravel\Guards\AuthenticationGuard::login(): Argument #1 ($credential) must be of type ?Auth0\Laravel\Entities\CredentialEntityContract, App\User given
```

### 🛡️ Solución Implementada
- **Controlador personalizado** que evita el auto-login después del reset
- **Reset puro de contraseña** sin intentar autenticar al usuario
- **Redirección segura** a la página de login después del reset exitoso
- **Compatibilidad completa** con el sistema Auth0 existente

### 🔄 Flujo Mejorado
1. **Admin envía** correo de reset desde la vista de usuarios
2. **Usuario recibe** correo con token seguro
3. **Usuario accede** al formulario de reset con diseño moderno
4. **Sistema actualiza** la contraseña sin hacer auto-login
5. **Usuario es redirigido** con mensaje de éxito
6. **Usuario puede autenticarse** normalmente con Auth0

## 🛠️ Configuración Necesaria

### Sistema de Correo
Asegúrate de que el sistema de correo esté configurado correctamente en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=tu-servidor-smtp
MAIL_PORT=587
MAIL_USERNAME=tu-usuario
MAIL_PASSWORD=tu-contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@tu-dominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Base de Datos
La tabla `password_resets` debe existir con la estructura estándar de Laravel:
- `email` (string, indexed)
- `token` (string)
- `created_at` (timestamp, nullable)

## 📝 Uso

### Desde la Vista de Usuarios:
1. Navega a `/securityusers`
2. En la columna "Opciones", haz clic en el botón "Acciones"
3. Si el usuario tiene email, verás la opción "Enviar reset de contraseña"
4. Haz clic en la opción
5. Confirma el envío en el modal de SweetAlert2
6. El sistema enviará el correo y mostrará un mensaje de confirmación

### Flujo del Usuario Final:
1. El usuario recibe un correo con el link de reset
2. Hace clic en el link del correo
3. Es dirigido al formulario de reset de contraseña
4. Ingresa su nueva contraseña
5. Su contraseña es actualizada

## 📊 Logging

### Eventos Registrados:
- ✅ **Envío exitoso**: Email, usuario solicitante, timestamp
- ❌ **Error de envío**: UUID del usuario, error, stack trace
- ℹ️ **Validaciones**: Intentos con email faltante

### Ubicación de Logs:
- Archivo: `storage/logs/laravel.log`
- Canal: Default de Laravel

## 🔧 Personalización

### Plantilla de Correo
Para personalizar el correo de reset, publica las vistas de Laravel:

```bash
php artisan vendor:publish --tag=laravel-notifications
```

Luego edita: `resources/views/notifications/reset-password.blade.php`

### Configuración de Expiración
En `config/auth.php`:

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60, // minutos
        'throttle' => 60, // segundos entre intentos
    ],
],
```

## 🐛 Troubleshooting

### Problema: "No se pudo enviar el correo"
**Causas posibles:**
- Configuración de correo incorrecta
- Servidor SMTP no disponible
- Email del usuario no válido

**Solución:**
1. Verificar configuración SMTP
2. Revisar logs en `storage/logs/laravel.log`
3. Probar envío manual con `php artisan tinker`

### Problema: "El usuario no tiene un correo electrónico registrado"
**Causa:** El campo `email` del usuario está vacío o es null

**Solución:**
1. Editar el usuario y agregar un email válido
2. El botón aparecerá automáticamente

### Problema: Link de reset no funciona
**Causas posibles:**
- Token expirado (más de 60 minutos por defecto)
- Tabla `password_resets` no existe
- Configuración de APP_URL incorrecta

**Solución:**
1. Verificar que `APP_URL` en `.env` sea correcto
2. Enviar nuevo correo de reset
3. Verificar que la tabla `password_resets` exista

## 🔄 Próximas Mejoras

### Sugeridas para futuras versiones:
1. **Notificación al administrador** cuando se envía un reset
2. **Restricción de permisos** específicos para la acción
3. **Historial de resets** por usuario
4. **Plantillas de correo personalizadas** por rol/centro
5. **Límite de intentos** por usuario/día

## ✅ Verificación de Funcionamiento

Para verificar que todo esté funcionando correctamente:

```bash
# Verificar rutas
php artisan route:list | grep password

# Verificar configuración
php artisan config:show auth.passwords.users

# Probar envío (reemplaza con datos reales)
php artisan tinker
Password::sendResetLink(['email' => 'test@example.com']);
```

---
**Implementado por**: Sistema YMS-OKTA  
**Fecha**: Septiembre 2025  
**Versión**: 1.0