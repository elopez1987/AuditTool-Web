#!/bin/bash

# =============================================================================
# deploy.sh — Script de despliegue para proyecto Laravel
# Uso: bash deploy.sh [rama] (default: main)
# =============================================================================

set -euo pipefail  # -e: abortar en error, -u: variables no definidas son error, -o pipefail: errores en pipes

# --- Configuración ---
BRANCH="${1:-main}"
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SSH_KEY="${DEPLOY_SSH_KEY:-"$HOME/.ssh/id_ed25519"}"  # Configurable via variable de entorno
SSH_KNOWN_HOSTS="${DEPLOY_KNOWN_HOSTS:-"$HOME/.ssh/known_hosts"}"  # Archivo known_hosts explícito
LOG_FILE="$PROJECT_DIR/storage/logs/deploy.log"

# --- Colores para output ---
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Timestamp dinámico por cada entrada de log
log() {
    local ts
    ts=$(date '+%Y-%m-%d %H:%M:%S')
    echo -e "${GREEN}[✔]${NC} $1"
    echo "[$ts] $1" >> "$LOG_FILE"
}

warn() {
    local ts
    ts=$(date '+%Y-%m-%d %H:%M:%S')
    echo -e "${YELLOW}[!]${NC} $1"
    echo "[$ts] WARNING: $1" >> "$LOG_FILE"
}

error_exit() {
    local ts
    ts=$(date '+%Y-%m-%d %H:%M:%S')
    echo -e "${RED}[✘]${NC} $1"
    echo "[$ts] ERROR: $1" >> "$LOG_FILE"
    exit 1
}

# --- Validar nombre de rama ---
# Solo permite caracteres alfanuméricos, guiones, puntos y slashes
if [[ ! "$BRANCH" =~ ^[a-zA-Z0-9/_.-]+$ ]]; then
    error_exit "Nombre de rama inválido: '$BRANCH'. Solo se permiten caracteres alfanuméricos, '/', '-', '_' y '.'"
fi

# --- Verificar existencia y permisos de la clave SSH ---
if [[ ! -f "$SSH_KEY" ]]; then
    error_exit "Clave SSH no encontrada: $SSH_KEY"
fi

if [[ "$(stat -c '%a' "$SSH_KEY" 2>/dev/null || stat -f '%A' "$SSH_KEY")" != "600" ]]; then
    warn "Permisos de la clave SSH no son 600. Corrigiendo..."
    chmod 600 "$SSH_KEY"
fi

# --- Verificar existencia del archivo known_hosts ---
if [[ ! -f "$SSH_KNOWN_HOSTS" ]]; then
    error_exit "Archivo known_hosts no encontrado: $SSH_KNOWN_HOSTS. Ejecuta 'ssh-keyscan <host> >> $SSH_KNOWN_HOSTS' antes del primer deploy."
fi

# =============================================================================
echo ""
echo "========================================"
echo "  Despliegue Laravel — $(date '+%Y-%m-%d %H:%M:%S')"
echo "  Rama: $BRANCH"
echo "  Directorio: $PROJECT_DIR"
echo "========================================"
echo ""

# Construir GIT_SSH_COMMAND una sola vez, con verificación de host habilitada
GIT_SSH_COMMAND="ssh -i \"$SSH_KEY\" -o StrictHostKeyChecking=yes -o UserKnownHostsFile=\"$SSH_KNOWN_HOSTS\""
export GIT_SSH_COMMAND

# --- 1. Actualizar código desde GitHub ---
log "Haciendo fetch desde origin..."
git fetch origin || error_exit "Falló git fetch"

log "Mergeando origin/$BRANCH..."
git merge "origin/$BRANCH" || error_exit "Falló git merge"

# --- 2. Instalar dependencias PHP ---
log "Instalando dependencias con Composer..."
composer install --no-dev --optimize-autoloader || error_exit "Falló composer install"

# --- 3. Limpiar y regenerar cachés ---
log "Limpiando cachés..."
php artisan optimize:clear || error_exit "Falló optimize:clear"
php artisan cache:clear    || error_exit "Falló cache:clear"
php artisan config:clear   || error_exit "Falló config:clear"
php artisan route:clear    || error_exit "Falló route:clear"
php artisan view:clear     || error_exit "Falló view:clear"

# --- 4. Reiniciar queue workers ---
log "Reiniciando queue workers..."
php artisan queue:restart || error_exit "Falló queue:restart"

# =============================================================================
echo ""
echo -e "${GREEN}========================================"
echo -e "  ✔  Despliegue completado exitosamente"
echo -e "========================================${NC}"
echo ""
