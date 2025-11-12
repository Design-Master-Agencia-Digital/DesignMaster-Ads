# WordPress.org Assets

Esta pasta contém os assets necessários para publicar o plugin no WordPress.org.

## 📁 Estrutura de Arquivos

```
.wordpress-org/
├── icon.svg                      # Ícone vetorial (gerar PNG depois)
├── banner-772x250.svg            # Banner normal vetorial
├── banner-1544x500.svg           # Banner retina vetorial
├── screenshot-generator.html     # Ferramenta para gerar screenshots
├── screenshot-1.png              # Dashboard (a capturar)
├── screenshot-2.png              # Analytics (a capturar)
├── screenshot-3.png              # Zone Manager (a capturar)
├── screenshot-4.png              # Banner Edit (a capturar)
├── screenshot-5.png              # Banner Listing (a capturar)
└── screenshot-6.png              # Frontend Display (a capturar)
```

## 🎨 Assets Criados

### ✅ Ícones (SVG)
- **icon.svg** - Ícone vetorial 256x256
  - Design: Megafone azul com ondas sonoras amarelas
  - Badge "DM" em amarelo
  - Fundo azul (#2271b1) com cantos arredondados

### ✅ Banners (SVG)
- **banner-772x250.svg** - Banner normal
- **banner-1544x500.svg** - Banner high-resolution (2x)
  - Design moderno com gradiente azul
  - Título: "DesignMaster Ads"
  - Tagline: "Complete Banner Management with Advanced Analytics"
  - Badges de features
  - Elementos decorativos (gráficos)

### ✅ Screenshot Generator
- **screenshot-generator.html** - Ferramenta HTML para gerar mockups
  - Screenshot 1: Dashboard com KPIs
  - Screenshot 2: Analytics com gráficos
  - Instruções de uso incluídas

## 🔄 Converter SVG para PNG

Para converter os SVGs em PNGs nas resoluções corretas:

### Opção 1: Usar ImageMagick (linha de comando)

```bash
# Instalar ImageMagick
brew install imagemagick

# Converter ícones
convert -background none -resize 128x128 icon.svg icon-128x128.png
convert -background none -resize 256x256 icon.svg icon-256x256.png

# Converter banners
convert -background none banner-772x250.svg banner-772x250.png
convert -background none banner-1544x500.svg banner-1544x500.png
```

### Opção 2: Usar Inkscape

```bash
# Instalar Inkscape
brew install --cask inkscape

# Converter ícones
inkscape icon.svg --export-filename=icon-128x128.png -w 128 -h 128
inkscape icon.svg --export-filename=icon-256x256.png -w 256 -h 256

# Converter banners
inkscape banner-772x250.svg --export-filename=banner-772x250.png -w 772 -h 250
inkscape banner-1544x500.svg --export-filename=banner-1544x500.png -w 1544 -h 500
```

### Opção 3: Usar Editor Online

1. Acesse: https://www.photopea.com/ ou https://vectr.com/
2. Abra cada arquivo SVG
3. Exporte como PNG nas resoluções especificadas
4. Salve na pasta `.wordpress-org/`

### Opção 4: Usar Figma/Adobe XD/Sketch

1. Importe os SVGs
2. Exporte como PNG @1x e @2x
3. Renomeie conforme necessário

## 📸 Capturar Screenshots

### Screenshots do WordPress (3-5)

1. **Instale o plugin** em um WordPress local ou staging
2. Configure alguns banners e zonas de exemplo
3. Use as seguintes telas:

   **Screenshot 3 - Zone Manager:**
   - Navegue para: DM Ads > Zones
   - Mostre a lista de zonas com botão "Add New Zone"
   - Capture em 1280x720px

   **Screenshot 4 - Banner Edit:**
   - Navegue para: DM Ads > Banners > Add New
   - Mostre o editor com todos os campos
   - Capture com a imagem do banner visível
   - 1280x720px

   **Screenshot 5 - Banner Listing:**
   - Navegue para: DM Ads > Banners
   - Mostre a lista com colunas (thumbnail, zona, status, stats)
   - Capture com pelo menos 3-4 banners
   - 1280x720px

### Screenshot do Frontend (6)

**Screenshot 6 - Frontend Display:**
- Adicione um shortcode `[dm_ads zone="header"]` em uma página
- Capture o banner sendo exibido no site
- Mostre o contexto (parte do site ao redor)
- 1280x720px

### Mockups (1-2)

Use o arquivo `screenshot-generator.html`:
1. Abra no navegador
2. Use DevTools (F12) para definir viewport 1280x720
3. Capture cada screenshot individualmente
4. Ou use extensão de captura de tela

## 📋 Checklist de Assets

Antes de submeter ao WordPress.org:

- [ ] icon-128x128.png (128x128px, PNG)
- [ ] icon-256x256.png (256x256px, PNG)
- [ ] banner-772x250.png (772x250px, PNG/JPG)
- [ ] banner-1544x500.png (1544x500px, PNG/JPG)
- [ ] screenshot-1.png (Dashboard, 1280x720px)
- [ ] screenshot-2.png (Analytics, 1280x720px)
- [ ] screenshot-3.png (Zone Manager, 1280x720px)
- [ ] screenshot-4.png (Banner Edit, 1280x720px)
- [ ] screenshot-5.png (Banner Listing, 1280x720px)
- [ ] screenshot-6.png (Frontend, 1280x720px)

## 📦 Upload para SVN

Após gerar todos os assets:

```bash
# Navegar para o repo SVN
cd designmaster-ads-svn

# Criar pasta assets se não existir
mkdir -p assets

# Copiar todos os assets PNG
cp /path/to/.wordpress-org/icon-*.png assets/
cp /path/to/.wordpress-org/banner-*.png assets/
cp /path/to/.wordpress-org/screenshot-*.png assets/

# Adicionar ao SVN
svn add assets/*

# Commit
svn ci -m "Add plugin assets (icons, banners, screenshots)"
```

## 🎯 Especificações Técnicas

### Ícones
- **Formato:** PNG com transparência
- **Tamanhos:** 128x128px e 256x256px
- **Peso máximo:** 50KB cada
- **Uso:** Listagens de plugins, detalhes

### Banners
- **Formato:** PNG ou JPG
- **Tamanhos:** 772x250px e 1544x500px (retina)
- **Peso máximo:** 200KB cada
- **Uso:** Header da página do plugin

### Screenshots
- **Formato:** PNG ou JPG
- **Tamanho recomendado:** 1280x720px (16:9)
- **Peso máximo:** 500KB cada
- **Quantidade:** Mínimo 3, máximo 10
- **Uso:** Galeria na página do plugin

## 💡 Dicas de Design

1. **Ícones:**
   - Use cores da marca (#2271b1, #ffd93d)
   - Mantenha simples e reconhecível
   - Teste em tamanho pequeno (32x32)

2. **Banners:**
   - Texto grande e legível
   - Não use textos muito pequenos
   - Destaque o nome do plugin
   - Use contraste adequado

3. **Screenshots:**
   - Mostre features reais do plugin
   - Use dados de exemplo realistas
   - Capture em boa resolução
   - Evite informações sensíveis

## 🔗 Referências

- [Plugin Assets Guidelines](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/)
- [Header Images](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/#plugin-headers)
- [Plugin Icons](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/#plugin-icons)

---

**Status:** ✅ SVGs criados | ⏳ Aguardando conversão PNG e screenshots reais
