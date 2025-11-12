#!/bin/bash

# DesignMaster Ads - Asset Conversion Script
# Converte SVG para PNG nas resoluções corretas

set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo "🎨 DesignMaster Ads - Asset Conversion"
echo "======================================"
echo ""

# Check for ImageMagick or Inkscape
HAS_IMAGEMAGICK=false
HAS_INKSCAPE=false

if command -v convert &> /dev/null; then
    HAS_IMAGEMAGICK=true
    echo "✅ ImageMagick encontrado"
fi

if command -v inkscape &> /dev/null; then
    HAS_INKSCAPE=true
    echo "✅ Inkscape encontrado"
fi

if [ "$HAS_IMAGEMAGICK" = false ] && [ "$HAS_INKSCAPE" = false ]; then
    echo "❌ Erro: Nenhuma ferramenta de conversão encontrada!"
    echo ""
    echo "Por favor, instale uma das seguintes:"
    echo ""
    echo "ImageMagick:"
    echo "  brew install imagemagick"
    echo ""
    echo "Inkscape:"
    echo "  brew install --cask inkscape"
    echo ""
    exit 1
fi

echo ""

# Function to convert using ImageMagick
convert_with_imagemagick() {
    local input=$1
    local output=$2
    local width=$3
    local height=$4
    
    echo "  Convertendo: $output"
    convert -background none -resize ${width}x${height} "$input" "$output"
}

# Function to convert using Inkscape
convert_with_inkscape() {
    local input=$1
    local output=$2
    local width=$3
    local height=$4
    
    echo "  Convertendo: $output"
    inkscape "$input" --export-filename="$output" -w $width -h $height
}

# Choose converter
if [ "$HAS_IMAGEMAGICK" = true ]; then
    CONVERT_FUNC=convert_with_imagemagick
    echo "🔧 Usando ImageMagick para conversão"
else
    CONVERT_FUNC=convert_with_inkscape
    echo "🔧 Usando Inkscape para conversão"
fi

echo ""
echo "📦 Convertendo ícones..."

# Convert icon to 128x128
if [ -f "icon.svg" ]; then
    $CONVERT_FUNC "icon.svg" "icon-128x128.png" 128 128
else
    echo "  ⚠️  icon.svg não encontrado"
fi

# Convert icon to 256x256
if [ -f "icon.svg" ]; then
    $CONVERT_FUNC "icon.svg" "icon-256x256.png" 256 256
else
    echo "  ⚠️  icon.svg não encontrado"
fi

echo ""
echo "🎨 Convertendo banners..."

# Convert banner 772x250
if [ -f "banner-772x250.svg" ]; then
    $CONVERT_FUNC "banner-772x250.svg" "banner-772x250.png" 772 250
else
    echo "  ⚠️  banner-772x250.svg não encontrado"
fi

# Convert banner 1544x500 (retina)
if [ -f "banner-1544x500.svg" ]; then
    $CONVERT_FUNC "banner-1544x500.svg" "banner-1544x500.png" 1544 500
else
    echo "  ⚠️  banner-1544x500.svg não encontrado"
fi

echo ""
echo "✅ Conversão concluída!"
echo ""
echo "📋 Arquivos gerados:"
ls -lh *.png 2>/dev/null || echo "  Nenhum arquivo PNG encontrado"

echo ""
echo "📸 Próximos passos:"
echo "  1. Abra screenshot-generator.html no navegador"
echo "  2. Capture os mockups de Dashboard e Analytics"
echo "  3. Instale o plugin no WordPress e capture screenshots 3-6"
echo "  4. Salve todos como screenshot-1.png até screenshot-6.png"
echo ""
echo "🚀 Depois de ter todos os assets:"
echo "  svn add assets/*.png"
echo "  svn ci -m 'Add plugin assets'"
echo ""
