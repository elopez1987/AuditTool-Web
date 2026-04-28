#!/usr/bin/env bash
# =============================================================================
# push-to-github.sh — Sube cambios de local a GitHub
# Pasos:
#   1) composer run format
#   2) git add .
#   3) git commit -m "<mensaje solicitado>"
#   4) git push origin <rama actual>
# Uso: bash push-to-github.sh
# =============================================================================

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# 1. Formatear el código
echo -e "${YELLOW}▶ Ejecutando composer run format...${NC}"
composer run format

# 2. Agregar todos los cambios
echo -e "${YELLOW}▶ Ejecutando git add .${NC}"
git add .

# Verificar si hay algo que commitear
if git diff --cached --quiet; then
  echo -e "${RED}✘ No hay cambios staged para commitear.${NC}"
  exit 1
fi

# 3. Solicitar el mensaje del commit
echo ""
read -r -p "📝 Mensaje del commit: " commit_msg
if [ -z "$commit_msg" ]; then
  echo -e "${RED}✘ El mensaje del commit no puede estar vacío.${NC}"
  exit 1
fi

git commit -m "$commit_msg"

# 4. Push a la rama actual
current_branch="$(git rev-parse --abbrev-ref HEAD)"
echo -e "${YELLOW}▶ Haciendo push a origin/${current_branch}...${NC}"
git push origin "$current_branch"

echo ""
echo -e "${GREEN}✔ Cambios publicados en origin/${current_branch}${NC}"
