#!/bin/bash

# =============================================================================
# deploy.sh — Script de despliegue para proyecto Laravel
# Uso: bash deploy.sh [rama] (default: main)
# =============================================================================

set -e  # Detener el script si algún comando falla

# --- Configuración ---
BRANCH="${1:-main}"
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SSH_KEY="/home/elopez/.ssh/id_ed25519_github"
LOG_FILE="$PROJECT_DIR/storage/logs/deploy.log"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

# --- Colores para output ---
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

log() {
    echo -e "${GREEN}[✔]${NC} $1"
    echo "[$TIMESTAMP] $1" >> "$LOG_FILE"
}

warn() {
    echo -e "${YELLOW}[!]${NC} $1"
    echo "[$TIMESTAMP] WARNING: $1" >> "$LOG_FILE"
}

error_exit() {
    echo -e "${RED}[✘]${NC} $1"
    echo "[$TIMESTAMP] ERROR: $1" >> "$LOG_FILE"
    exit 1
}

# =============================================================================
echo ""
echo "========================================"
echo "  Despliegue Laravel — $(date '+%Y-%m-%d %H:%M:%S')"
echo "  Rama: $BRANCH"
echo "  Directorio: $PROJECT_DIR"
echo "========================================"
echo ""

# --- 1. Actualizar código desde GitHub ---
log "Haciendo fetch desde origin..."
GIT_SSH_COMMAND="ssh -i $SSH_KEY -o StrictHostKeyChecking=no" git fetch origin || error_exit "Falló git fetch"

log "Mergeando origin/$BRANCH..."
GIT_SSH_COMMAND="ssh -i $SSH_KEY -o StrictHostKeyChecking=no" git merge origin/$BRANCH || error_exit "Falló git merge"

# --- 2. Instalar dependencias PHP ---
log "Instalando dependencias con Composer..."
composer install --no-dev --optimize-autoloader || error_exit "Falló composer install"

# --- 3. Limpiar y regenerar cachés ---
log "Limpiando cachés..."
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# --- 4. Reiniciar queue workers ---
log "Reiniciando queue workers..."
php artisan queue:restart

# =============================================================================
echo ""
echo -e "${GREEN}========================================"
echo -e "  ✔  Despliegue completado exitosamente"
echo -e "========================================${NC}"
echo ""