# Git Hooks para Laravel Pint

Este proyecto incluye un hook de pre-commit que valida automáticamente el estilo del código PHP usando Laravel Pint antes de permitir commits.

## 🚀 Instalación Rápida

```bash
# Opción 1: Usando Composer
composer run install-hooks

# Opción 2: Ejecutar script directamente
./install-git-hooks.sh
```

## 🔧 Comandos Disponibles

### Formatear Código
```bash
# Formatear todo el proyecto
composer run format

# Solo verificar (sin corregir)
composer run format-test

# Ver diferencias antes de aplicar
composer run format-diff

# Formatear solo carpeta app/
composer run format-app

# Formatear solo carpeta config/
composer run format-config

# Modo verbose (más detalles)
composer run format-verbose
```

### Formatear Archivos Específicos
```bash
# Un archivo
./vendor/bin/pint app/Models/User.php

# Múltiples archivos
./vendor/bin/pint app/Models/User.php app/Http/Controllers/UserController.php

# Una carpeta específica
./vendor/bin/pint app/Http/Controllers/
```

## ⚡ Cómo Funciona el Pre-commit Hook

1. **Detección Automática**: Cuando haces `git commit`, el hook detecta automáticamente archivos PHP modificados
2. **Validación**: Ejecuta Laravel Pint en modo test (solo valida, no corrige)
3. **Bloqueo**: Si encuentra problemas de estilo, bloquea el commit y te muestra opciones para solucionarlo
4. **Permiso**: Si el código cumple con las reglas, permite el commit

### Ejemplo de Flujo

```bash
# 1. Modificas archivos PHP
vim app/Models/User.php

# 2. Agregas cambios
git add .

# 3. Intentas commit
git commit -m "Update User model"

# 4a. Si hay problemas de estilo:
# 🔍 Ejecutando validación de código con Laravel Pint...
# ❌ Se encontraron problemas de estilo en el código.
# 💡 Opciones para solucionarlo:
#    1️⃣ Formatear automáticamente: composer run format

# 4b. Si está correcto:
# ✅ ¡Excelente! El código cumple con las reglas de estilo.
# 🎉 Commit permitido.
```

## 🛠️ Solución de Problemas

### El hook no se ejecuta
```bash
# Reinstalar hook
./install-git-hooks.sh

# Verificar permisos
ls -la .git/hooks/pre-commit
```

### Saltarse el hook (NO recomendado)
```bash
# Solo en casos excepcionales
git commit --no-verify -m "mensaje"
```

### Formatear código con problemas
```bash
# Opción 1: Todo el proyecto
composer run format

# Opción 2: Solo archivos con problemas
./vendor/bin/pint path/to/problematic/file.php

# Opción 3: Ver qué se va a cambiar
composer run format-diff
```

## 📋 Reglas de Estilo

El proyecto usa el preset "Laravel" de Pint con algunas reglas personalizadas definidas en `pint.json`:

- **Espaciado en concatenación**: Un espacio antes y después del operador `.`
- **Operadores lógicos**: Espacio después de `!`
- **Returns simplificados**: Conversión de `return null` a `return`
- **Exclusiones**: `bootstrap/`, `storage/`, `vendor/`

## 👥 Para el Equipo de Desarrollo

### Al clonar el repositorio por primera vez:
```bash
git clone <repository>
cd <project>
composer install
composer run install-hooks  # ← IMPORTANTE: No olvides esto
```

### Flujo de trabajo recomendado:
1. Desarrollar código normalmente
2. Antes del commit: `composer run format-test` (opcional)
3. `git add .`
4. `git commit -m "mensaje"` (el hook se ejecuta automáticamente)
5. Si falla, ejecutar `composer run format` y repetir desde paso 3

### Configuración del IDE
Se recomienda configurar tu IDE para usar Laravel Pint automáticamente:

**VS Code**: Instalar extensión "Laravel Pint"
**PhpStorm**: Configurar External Tool para ejecutar `./vendor/bin/pint $FilePath$`

## 🔄 Actualizar Hooks

Si se actualiza el hook, todos los desarrolladores deben ejecutar:
```bash
composer run install-hooks
```

## ❓ Preguntas Frecuentes

**Q: ¿Puedo personalizar las reglas?**
A: Sí, edita el archivo `pint.json` en la raíz del proyecto.

**Q: ¿El hook afecta archivos que no están en el commit?**
A: No, solo valida archivos PHP que están en el staging area.

**Q: ¿Qué pasa si Laravel Pint no está instalado?**
A: El hook detecta esto y muestra un mensaje de error con instrucciones.

**Q: ¿Puedo usar este hook en otros proyectos?**
A: Sí, copia `install-git-hooks.sh` a otro proyecto Laravel con Pint instalado.