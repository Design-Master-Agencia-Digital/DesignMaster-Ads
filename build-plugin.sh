#!/bin/bash

# DesignMaster Ads - Build Distribution Script
# Gera arquivo ZIP pronto para submissão ao WordPress.org

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}  DesignMaster Ads - Build Distribution${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# Get plugin directory
PLUGIN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$PLUGIN_DIR"

# Get version from main plugin file
VERSION=$(grep "Version:" designmaster-ads.php | head -1 | awk '{print $3}')

if [ -z "$VERSION" ]; then
    echo -e "${RED}❌ Erro: Não foi possível detectar a versão do plugin${NC}"
    exit 1
fi

echo -e "${GREEN}📦 Versão detectada: ${VERSION}${NC}"
echo ""

# Define output directory and filename
BUILD_DIR="build"
PLUGIN_NAME="designmaster-ads"
OUTPUT_FILE="${PLUGIN_NAME}-${VERSION}.zip"
TEMP_DIR="${BUILD_DIR}/${PLUGIN_NAME}"

# Create build directory
echo -e "${YELLOW}🔨 Preparando diretório de build...${NC}"
rm -rf "$BUILD_DIR"
mkdir -p "$TEMP_DIR"

# Copy necessary files
echo -e "${YELLOW}📋 Copiando arquivos do plugin...${NC}"

# Main plugin file
cp designmaster-ads.php "$TEMP_DIR/"
echo "  ✓ designmaster-ads.php"

# Core directories
cp -r includes "$TEMP_DIR/"
echo "  ✓ includes/"

cp -r templates "$TEMP_DIR/"
echo "  ✓ templates/"

cp -r assets "$TEMP_DIR/"
echo "  ✓ assets/"

cp -r languages "$TEMP_DIR/"
echo "  ✓ languages/"

# Documentation files
cp readme.txt "$TEMP_DIR/"
echo "  ✓ readme.txt"

cp README.md "$TEMP_DIR/"
echo "  ✓ README.md"

cp CHANGELOG.md "$TEMP_DIR/"
echo "  ✓ CHANGELOG.md"

if [ -f LICENSE ]; then
    cp LICENSE "$TEMP_DIR/"
    echo "  ✓ LICENSE"
fi

if [ -f license.txt ]; then
    cp license.txt "$TEMP_DIR/"
    echo "  ✓ license.txt"
fi

# Optional documentation
if [ -f INSTALLATION.md ]; then
    cp INSTALLATION.md "$TEMP_DIR/"
    echo "  ✓ INSTALLATION.md"
fi

if [ -f TECHNICAL.md ]; then
    cp TECHNICAL.md "$TEMP_DIR/"
    echo "  ✓ TECHNICAL.md"
fi

if [ -f TROUBLESHOOTING.md ]; then
    cp TROUBLESHOOTING.md "$TEMP_DIR/"
    echo "  ✓ TROUBLESHOOTING.md"
fi

echo ""

# Clean up unnecessary files
echo -e "${YELLOW}🧹 Removendo arquivos desnecessários...${NC}"

# Remove hidden files
find "$TEMP_DIR" -name ".DS_Store" -delete 2>/dev/null || true
find "$TEMP_DIR" -name ".gitignore" -delete 2>/dev/null || true
find "$TEMP_DIR" -name ".gitattributes" -delete 2>/dev/null || true
echo "  ✓ Arquivos ocultos removidos"

# Remove development files
find "$TEMP_DIR" -name "*.log" -delete 2>/dev/null || true
find "$TEMP_DIR" -name "*.tmp" -delete 2>/dev/null || true
find "$TEMP_DIR" -name "*.bak" -delete 2>/dev/null || true
echo "  ✓ Arquivos temporários removidos"

# Remove node_modules if exists
if [ -d "$TEMP_DIR/node_modules" ]; then
    rm -rf "$TEMP_DIR/node_modules"
    echo "  ✓ node_modules/ removido"
fi

# Remove vendor if exists (unless needed)
if [ -d "$TEMP_DIR/vendor" ]; then
    echo "  ⚠️  vendor/ mantido (se usar Composer, mantenha; senão remova)"
fi

echo ""

# Create ZIP file
echo -e "${YELLOW}📦 Criando arquivo ZIP...${NC}"
cd "$BUILD_DIR"
zip -r "$OUTPUT_FILE" "$PLUGIN_NAME" -q
cd ..

# Get file size
FILE_SIZE=$(du -h "$BUILD_DIR/$OUTPUT_FILE" | cut -f1)

echo -e "${GREEN}✅ ZIP criado com sucesso!${NC}"
echo ""

# Summary
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}📊 Resumo da Build${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "  ${GREEN}Arquivo:${NC} $OUTPUT_FILE"
echo -e "  ${GREEN}Tamanho:${NC} $FILE_SIZE"
echo -e "  ${GREEN}Versão:${NC}  $VERSION"
echo -e "  ${GREEN}Local:${NC}   $BUILD_DIR/$OUTPUT_FILE"
echo ""

# Check file size (warn if > 5MB)
FILE_SIZE_BYTES=$(stat -f%z "$BUILD_DIR/$OUTPUT_FILE" 2>/dev/null || stat -c%s "$BUILD_DIR/$OUTPUT_FILE" 2>/dev/null)
FILE_SIZE_MB=$((FILE_SIZE_BYTES / 1024 / 1024))

if [ $FILE_SIZE_MB -gt 5 ]; then
    echo -e "${YELLOW}⚠️  Atenção: Arquivo maior que 5MB ($FILE_SIZE_MB MB)${NC}"
    echo -e "${YELLOW}   Considere otimizar imagens ou remover arquivos desnecessários${NC}"
    echo ""
fi

# File list
echo -e "${BLUE}📂 Conteúdo do ZIP:${NC}"
echo ""
unzip -l "$BUILD_DIR/$OUTPUT_FILE" | head -20
echo ""
if [ $(unzip -l "$BUILD_DIR/$OUTPUT_FILE" | wc -l) -gt 23 ]; then
    echo -e "  ${YELLOW}... e mais arquivos${NC}"
    echo ""
fi

# Next steps
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}🚀 Próximos Passos${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo "  1. Teste a instalação:"
echo -e "     ${YELLOW}Instale o ZIP em um WordPress limpo${NC}"
echo ""
echo "  2. Valide com Plugin Check:"
echo -e "     ${YELLOW}https://wordpress.org/plugins/plugin-check/${NC}"
echo ""
echo "  3. Submeta ao WordPress.org:"
echo -e "     ${YELLOW}https://wordpress.org/plugins/developers/add/${NC}"
echo ""
echo -e "${GREEN}✨ Build concluída com sucesso!${NC}"
echo ""
