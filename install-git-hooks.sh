#!/bin/bash

# Script para instalar hooks de Git para Laravel Pint

echo "🔧 Instalando Git hooks para Laravel Pint..."

# Verificar que estamos en un repositorio Git
if [ ! -d ".git" ]; then
    echo "❌ Error: No estás en la raíz de un repositorio Git."
    exit 1
fi

# Crear directorio de hooks si no existe
mkdir -p .git/hooks

# Contenido del hook de pre-commit
cat > .git/hooks/pre-commit << 'EOF'
#!/bin/bash

# Hook de pre-commit para Laravel Pint
# Valida el estilo de código antes de hacer commit

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🔍 Ejecutando validación de código con Laravel Pint...${NC}"

# Verificar si Pint está instalado
if [ ! -f "./vendor/bin/pint" ]; then
    echo -e "${RED}❌ Laravel Pint no está instalado. Ejecuta: composer require laravel/pint --dev${NC}"
    exit 1
fi

# Obtener lista de archivos PHP modificados
STAGED_FILES=$(git diff --cached --name-only --diff-filter=ACM | grep -E '\.(php)$')

if [ -z "$STAGED_FILES" ]; then
    echo -e "${GREEN}✅ No hay archivos PHP para validar.${NC}"
    exit 0
fi

echo -e "${YELLOW}📂 Archivos PHP a validar:${NC}"
echo "$STAGED_FILES" | while read -r file; do
    echo "   - $file"
done
echo ""

# Ejecutar Pint en modo test (solo validar, no corregir)
echo -e "${BLUE}🧪 Validando estilo de código...${NC}"

# Ejecutar Pint solo en los archivos modificados
./vendor/bin/pint --test $(echo "$STAGED_FILES" | tr '\n' ' ') 2>&1

PINT_EXIT_CODE=$?

if [ $PINT_EXIT_CODE -ne 0 ]; then
    echo ""
    echo -e "${RED}❌ Se encontraron problemas de estilo en el código.${NC}"
    echo ""
    echo -e "${YELLOW}💡 Opciones para solucionarlo:${NC}"
    echo ""
    echo -e "   1️⃣  ${GREEN}Formatear automáticamente:${NC}"
    echo "      composer run format"
    echo ""
    echo -e "   2️⃣  ${GREEN}Formatear solo archivos modificados:${NC}"
    echo "      ./vendor/bin/pint $(echo "$STAGED_FILES" | tr '\n' ' ')"
    echo ""
    echo -e "   3️⃣  ${GREEN}Ver diferencias específicas:${NC}"
    echo "      composer run format-diff"
    echo ""
    echo -e "   4️⃣  ${YELLOW}Saltar validación (NO recomendado):${NC}"
    echo "      git commit --no-verify -m \"tu mensaje\""
    echo ""
    echo -e "${RED}🚫 Commit cancelado. Corrige los problemas de estilo y vuelve a intentar.${NC}"
    exit 1
else
    echo ""
    echo -e "${GREEN}✅ ¡Excelente! El código cumple con las reglas de estilo.${NC}"
    echo -e "${GREEN}🎉 Commit permitido.${NC}"
    exit 0
fi
EOF

# Hacer ejecutable el hook
chmod +x .git/hooks/pre-commit

echo ""
echo "✅ ¡Hook de pre-commit instalado correctamente!"
echo ""
echo "💡 Comandos útiles:"
echo "   - Probar hook: git commit (con archivos PHP modificados)"
echo "   - Saltar hook: git commit --no-verify"
echo "   - Formatear código: composer run format"
echo ""
echo "🎯 Ahora todos los commits validarán automáticamente el estilo del código PHP con Laravel Pint."