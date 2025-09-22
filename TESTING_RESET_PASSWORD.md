# Testing - Funcionalidad Reset de Contraseña

## 🧪 Plan de Pruebas

### ✅ Pruebas de Funcionalidad

#### 1. **Envío de Correo desde Vista de Usuarios**
- [ ] Navegar a `/securityusers`
- [ ] Verificar que el botón "Enviar reset de contraseña" aparece solo para usuarios con email
- [ ] Hacer clic en el botón y confirmar en el modal SweetAlert2
- [ ] Verificar mensaje de éxito/error
- [ ] Verificar que el correo llega a la bandeja del usuario

#### 2. **Formulario de Reset de Contraseña**
- [ ] Abrir el link del correo recibido
- [ ] Verificar que el formulario tiene el diseño moderno esperado
- [ ] Verificar que el email aparece prellenado y bloqueado
- [ ] Probar toggle de visibilidad de contraseña (ambos campos)
- [ ] Intentar enviar con contraseñas que no coinciden
- [ ] Intentar enviar con contraseña muy corta (<8 caracteres)
- [ ] Enviar con datos válidos

#### 3. **Proceso Completo sin Auto-Login**
- [ ] Completar el reset de contraseña exitosamente
- [ ] Verificar redirección con mensaje de éxito
- [ ] Verificar que NO se hace auto-login
- [ ] Intentar hacer login manual con la nueva contraseña
- [ ] Verificar que el login funciona correctamente con Auth0

#### 4. **Validaciones y Seguridad**
- [ ] Intentar usar un token expirado
- [ ] Intentar usar un token inválido
- [ ] Verificar logging de eventos en `storage/logs/laravel.log`
- [ ] Verificar que el token se invalida después del uso

### 🔍 Comandos de Verificación

```bash
# Verificar rutas
php artisan route:list | grep -E "(password|reset|forgot)"

# Verificar configuración de auth
php artisan tinker
>>> config('auth.passwords.users')

# Verificar tabla password_resets
php artisan tinker
>>> DB::table('password_resets')->count()

# Limpiar tokens expirados (opcional)
php artisan auth:clear-resets
```

### 📧 Configuración de Correo para Testing

#### Opción 1: Usar Mailtrap (Recomendado para testing)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

#### Opción 2: Usar Log Driver (Para desarrollo local)
```env
MAIL_MAILER=log
```

### 🐛 Checklist de Troubleshooting

#### Si no llegan los correos:
- [ ] Verificar configuración SMTP en `.env`
- [ ] Verificar que `APP_URL` esté configurado correctamente
- [ ] Revisar logs en `storage/logs/laravel.log`
- [ ] Probar envío manual: `php artisan tinker` → `Password::sendResetLink(['email' => 'test@test.com'])`

#### Si el formulario no carga:
- [ ] Verificar que las rutas estén registradas
- [ ] Verificar que el controlador `CustomResetPasswordController` existe
- [ ] Verificar permisos de archivos en `resources/views/auth/passwords/reset.blade.php`

#### Si hay errores de Auth0:
- [ ] Verificar que se usa `CustomResetPasswordController` en las rutas
- [ ] Verificar que el método `reset()` no intenta hacer auto-login
- [ ] Revisar logs para errores específicos de Auth0

### 📊 Métricas de Éxito

✅ **100% Compatible con Auth0**: Sin errores de login automático
✅ **UX Mejorada**: Formulario moderno y responsive
✅ **Seguridad Mantenida**: Tokens seguros y validaciones completas
✅ **Logging Completo**: Trazabilidad de todas las acciones

### 🎯 Casos de Prueba Específicos

#### Caso 1: Usuario con Email Válido
```
1. Admin ve botón "Enviar reset de contraseña"
2. Hace clic → Confirma en modal
3. Usuario recibe correo
4. Hace clic en link → Ve formulario moderno
5. Ingresa nueva contraseña → Submit exitoso
6. Ve mensaje "Contraseña restablecida correctamente"
7. Puede hacer login normalmente
```

#### Caso 2: Usuario sin Email
```
1. Admin NO ve botón "Enviar reset de contraseña"
2. Funcionalidad no disponible hasta que se agregue email
```

#### Caso 3: Token Expirado
```
1. Usuario hace clic en link después de 60 minutos
2. Ve mensaje de token expirado
3. Puede solicitar nuevo reset
```

### 📝 Registro de Pruebas

#### Fecha: ________________
#### Tester: ________________

| Prueba | Estado | Notas |
|--------|--------|-------|
| Envío de correo | ⬜ Pass / ⬜ Fail | |
| Formulario moderno | ⬜ Pass / ⬜ Fail | |
| Reset sin auto-login | ⬜ Pass / ⬜ Fail | |
| Login posterior | ⬜ Pass / ⬜ Fail | |
| Validaciones | ⬜ Pass / ⬜ Fail | |
| Logging | ⬜ Pass / ⬜ Fail | |

### 🚀 Notas para Producción

- [ ] Configurar servidor SMTP real
- [ ] Ajustar tiempo de expiración de tokens si es necesario
- [ ] Configurar limpieza automática de tokens con cron job
- [ ] Monitorear logs para detectar patrones sospechosos
- [ ] Considerar implementar rate limiting por usuario/IP

---
**Documento de Testing v1.0**  
**Fecha**: Septiembre 2025  
**Funcionalidad**: Reset Password sin conflictos Auth0