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

set -euo pipefail  # -e: abortar en error, -u: variables no definidas son error, -o pipefail: errores en pipes

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# --- Patrones de archivos sensibles que NO deben subirse ---
SENSITIVE_PATTERNS=(
    '\.env$'
    '\.env\.'
    '\.pem$'
    '\.key$'
    '\.p12$'
    '\.pfx$'
    'id_rsa'
    'id_ed25519'
    '\.htpasswd$'
    'secrets\.'
    'credentials\.'
)

# --- Ramas que requieren confirmación explícita antes de hacer push ---
PROTECTED_BRANCHES=("main" "master" "production" "prod")

warn() {
    echo -e "${YELLOW}[!]${NC} $1"
}

error_exit() {
    echo -e "${RED}[✘]${NC} $1"
    exit 1
}

# --- Verificar configuración SSH del sistema ---
if grep -qiE '^\s*StrictHostKeyChecking\s+(no|false|0)' "$HOME/.ssh/config" 2>/dev/null; then
    warn "~/.ssh/config tiene StrictHostKeyChecking deshabilitado globalmente."
    warn "Esto expone las conexiones SSH a ataques MITM. Restrígelo solo a hosts específicos."
fi

# 1. Formatear el código
echo -e "${YELLOW}▶ Ejecutando composer run format...${NC}"
composer run format

# 2. Agregar todos los cambios
echo -e "${YELLOW}▶ Ejecutando git add .${NC}"
git add .

# Verificar si hay algo que commitear
if git diff --cached --quiet; then
    error_exit "No hay cambios staged para commitear."
fi

# --- Detectar archivos sensibles en el staging area ---
staged_files=$(git diff --cached --name-only)
sensitive_found=()

for pattern in "${SENSITIVE_PATTERNS[@]}"; do
    while IFS= read -r file; do
        sensitive_found+=("$file")
    done < <(echo "$staged_files" | grep -E "$pattern" || true)
done

if [[ ${#sensitive_found[@]} -gt 0 ]]; then
    echo ""
    echo -e "${RED}[✘] Se detectaron archivos potencialmente sensibles en el staging area:${NC}"
    printf '      - %s\n' "${sensitive_found[@]}"
    echo ""
    error_exit "Revisa tu .gitignore y ejecuta 'git restore --staged <archivo>' para desagregarlos."
fi

# 3. Solicitar el mensaje del commit
echo ""
read -r -p "📝 Mensaje del commit: " commit_msg

# Validar que no esté vacío
if [[ -z "$commit_msg" ]]; then
    error_exit "El mensaje del commit no puede estar vacío."
fi

# Sanitizar: eliminar caracteres de control y null bytes (excepto newline/tab)
commit_msg=$(printf '%s' "$commit_msg" | tr -d '\000-\010\013\014\016-\037\177')

# Validar longitud razonable
if [[ ${#commit_msg} -gt 200 ]]; then
    error_exit "El mensaje del commit excede los 200 caracteres (${#commit_msg} usados)."
fi

git commit -m "$commit_msg"

# 4. Push a la rama actual
current_branch="$(git rev-parse --abbrev-ref HEAD)"

# Validar nombre de rama
if [[ ! "$current_branch" =~ ^[a-zA-Z0-9/_.-]+$ ]]; then
    error_exit "Nombre de rama inválido: '$current_branch'."
fi

# Confirmación explícita para ramas protegidas
for protected in "${PROTECTED_BRANCHES[@]}"; do
    if [[ "$current_branch" == "$protected" ]]; then
        echo ""
        warn "Estás a punto de hacer push directamente a la rama protegida '${current_branch}'."
        read -r -p "¿Confirmas el push? [s/N]: " confirm
        if [[ ! "$confirm" =~ ^[sS]$ ]]; then
            error_exit "Push cancelado por el usuario."
        fi
        break
    fi
done

echo -e "${YELLOW}▶ Haciendo push a origin/${current_branch}...${NC}"
git push origin "$current_branch"

echo ""
echo -e "${GREEN}✔ Cambios publicados en origin/${current_branch}${NC}"
