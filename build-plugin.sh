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

# Function to increment version
increment_version() {
    local version=$1
    local type=$2
    
    IFS='.' read -r -a parts <<< "$version"
    major="${parts[0]}"
    minor="${parts[1]}"
    patch="${parts[2]}"
    
    case $type in
        major)
            major=$((major + 1))
            minor=0
            patch=0
            ;;
        minor)
            minor=$((minor + 1))
            patch=0
            ;;
        patch)
            patch=$((patch + 1))
            ;;
    esac
    
    echo "${major}.${minor}.${patch}"
}

# Function to update version in files
update_version() {
    local old_version=$1
    local new_version=$2
    
    echo -e "${YELLOW}📝 Atualizando versão nos arquivos...${NC}"
    
    # Update main plugin file
    sed -i.bak "s/Version: *${old_version}/Version: ${new_version}/" designmaster-ads.php
    sed -i.bak "s/define( *'DM_ADS_VERSION', *'${old_version}' *)/define( 'DM_ADS_VERSION', '${new_version}' )/" designmaster-ads.php
    echo "  ✓ designmaster-ads.php"
    
    # Update readme.txt
    if [ -f readme.txt ]; then
        sed -i.bak "s/Stable tag: *${old_version}/Stable tag: ${new_version}/" readme.txt
        echo "  ✓ readme.txt"
    fi
    
    # Clean up backup files
    rm -f designmaster-ads.php.bak readme.txt.bak
    
    echo -e "${GREEN}✅ Versão atualizada de ${old_version} para ${new_version}${NC}"
    echo ""
}

echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}  DesignMaster Ads - Build Distribution${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# Get plugin directory
PLUGIN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$PLUGIN_DIR"

# Check for version increment flag
INCREMENT_TYPE=""
if [ "$1" == "--major" ]; then
    INCREMENT_TYPE="major"
elif [ "$1" == "--minor" ]; then
    INCREMENT_TYPE="minor"
elif [ "$1" == "--patch" ]; then
    INCREMENT_TYPE="patch"
fi

# Get current version from main plugin file
CURRENT_VERSION=$(grep "Version:" designmaster-ads.php | head -1 | awk '{print $3}')

if [ -z "$CURRENT_VERSION" ]; then
    echo -e "${RED}❌ Erro: Não foi possível detectar a versão do plugin${NC}"
    exit 1
fi

# Increment version if requested
if [ -n "$INCREMENT_TYPE" ]; then
    echo -e "${BLUE}🔢 Incrementando versão ($INCREMENT_TYPE)...${NC}"
    NEW_VERSION=$(increment_version "$CURRENT_VERSION" "$INCREMENT_TYPE")
    update_version "$CURRENT_VERSION" "$NEW_VERSION"
    VERSION="$NEW_VERSION"
else
    VERSION="$CURRENT_VERSION"
fi

echo -e "${GREEN}📦 Versão do build: ${VERSION}${NC}"
echo ""

# Define output directory and filename
BUILD_DIR="build"
PLUGIN_NAME="designmaster-ads"
OUTPUT_FILE="${PLUGIN_NAME}-${VERSION}.zip"
TEMP_DIR="${BUILD_DIR}/${PLUGIN_NAME}"

# Validate required files and directories
echo -e "${YELLOW}✅ Validando estrutura do plugin...${NC}"
REQUIRED_FILES=("designmaster-ads.php" "readme.txt" "README.md" "CHANGELOG.md")
REQUIRED_DIRS=("includes" "templates" "assets" "languages")

for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        echo -e "${RED}❌ Erro: Arquivo obrigatório não encontrado: $file${NC}"
        exit 1
    fi
    echo "  ✓ $file"
done

for dir in "${REQUIRED_DIRS[@]}"; do
    if [ ! -d "$dir" ]; then
        echo -e "${RED}❌ Erro: Diretório obrigatório não encontrado: $dir${NC}"
        exit 1
    fi
    echo "  ✓ $dir/"
done
echo ""

# Create build directory
echo -e "${YELLOW}🔨 Preparando diretório de build...${NC}"
rm -rf "$BUILD_DIR"
mkdir -p "$TEMP_DIR"

# Files and directories to EXCLUDE from the build
EXCLUDE_PATTERNS=(
    ".git"
    ".gitignore"
    ".gitattributes"
    ".DS_Store"
    "node_modules"
    "build"
    "*.log"
    "*.tmp"
    "*.bak"
    ".vscode"
    ".idea"
    "test-*.php"
    "BUILD_CHECKLIST.md"
    "GIT_SETUP.md"
    "SUBMISSION_CHECKLIST.md"
    "WORDPRESS_ORG_SUBMISSION.md"
    "UNIQUE_STATS_FEATURE.md"
    "IMPROVEMENTS_*.md"
    "build-plugin.sh"
    "wp-debug.php"
    "phpcs.xml"
    "composer.json"
    "composer.lock"
    "package.json"
    "package-lock.json"
    ".phpcs.xml.dist"
)

# Build rsync exclude arguments
RSYNC_EXCLUDES=""
for pattern in "${EXCLUDE_PATTERNS[@]}"; do
    RSYNC_EXCLUDES="$RSYNC_EXCLUDES --exclude=$pattern"
done

# Copy all files except excluded ones
echo -e "${YELLOW}📋 Copiando arquivos do plugin...${NC}"
rsync -av --quiet $RSYNC_EXCLUDES ./ "$TEMP_DIR/"
echo "  ✓ Todos os arquivos copiados (excluindo arquivos de desenvolvimento)"
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

if [ -n "$INCREMENT_TYPE" ]; then
    echo -e "${YELLOW}⚠️  Não esqueça de commitar a nova versão:${NC}"
    echo -e "     git add ."
    echo -e "     git commit -m \"Bump version to ${VERSION}\""
    echo -e "     git tag v${VERSION}"
    echo -e "     git push && git push --tags"
    echo ""
fi

echo -e "${GREEN}✨ Build concluída com sucesso!${NC}"
echo ""
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${YELLOW}💡 Uso do script:${NC}"
echo ""
echo -e "  ${GREEN}Build normal:${NC}"
echo -e "    ./build-plugin.sh"
echo ""
echo -e "  ${GREEN}Incrementar patch (1.0.0 → 1.0.1):${NC}"
echo -e "    ./build-plugin.sh --patch"
echo ""
echo -e "  ${GREEN}Incrementar minor (1.0.0 → 1.1.0):${NC}"
echo -e "    ./build-plugin.sh --minor"
echo ""
echo -e "  ${GREEN}Incrementar major (1.0.0 → 2.0.0):${NC}"
echo -e "    ./build-plugin.sh --major"
echo ""
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
