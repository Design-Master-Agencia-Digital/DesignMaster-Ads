# Changelog - DesignMaster Ads

Todas as mudanças notáveis deste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [1.1.0] - 2025-01-12

### Added
- Portuguese (PT-BR) translation with complete .po/.mo files
- Custom image field with WordPress Media Library integration
- Translation template (.pot) for future translations

### Changed
- Replaced Featured Image with custom image meta field
- Improved image upload UX with WordPress Media Uploader
- Enhanced banner editing interface with inline image selection

### Technical
- Added `load_plugin_textdomain()` for i18n support
- Implemented `wp.media` JavaScript API for image uploads
- Added `_dm_banner_image_id` meta field
- Modified `class-dm-banner.php` to use custom image field
- Updated `class-dm-zone.php` to fetch from custom meta field
- Added `wp_enqueue_media()` for banner edit screens

## [1.0.0] - 2025-01-11

### Added
- Initial release
- Custom Post Type for banner management
- Zone management system with three rotation types (fixed, reload, timed)
- Comprehensive analytics with Chart.js graphs
- Device detection and IP anonymization
- Admin interface with Dashboard, Analytics, Zones, and Settings pages
- Frontend display templates for all rotation types
- Complete documentation (README, INSTALLATION, TECHNICAL, GIT_SETUP)
  
#### 📊 Estatísticas do Projeto
- **Total de arquivos**: 27
- **Linhas de código**: 2.630+
- **Classes PHP**: 7 principais
- **Templates**: 7 (4 admin + 3 public)
- **Assets JavaScript**: 3 arquivos
- **Assets CSS**: 2 arquivos
- **Documentação**: 4 arquivos markdown

#### 🎯 Casos de Uso Suportados
- ✅ Banners publicitários
- ✅ Promoções sazonais
- ✅ Patrocinadores rotativos
- ✅ Destaques de conteúdo
- ✅ Call-to-actions
- ✅ Carrosséis de ofertas
- ✅ Gestão multi-anunciante

#### 🔧 Compatibilidade
- WordPress 5.8+
- PHP 7.4+
- MySQL 5.7+
- Todos os browsers modernos
- Mobile-friendly

---

## [Futuro] - Roadmap

### 🚀 Planejado para v1.1.0
- [ ] Widget para sidebars
- [ ] Suporte a HTML5/Video banners
- [ ] Geolocalização de cliques
- [ ] A/B Testing
- [ ] Relatórios agendados por email
- [ ] Integração com Google Analytics
- [ ] API REST endpoints
- [ ] Importação/Exportação de banners

### 🎨 Planejado para v1.2.0
- [ ] Editor visual de banners
- [ ] Templates de banner pré-definidos
- [ ] Modo escuro no admin
- [ ] Temas personalizáveis
- [ ] Animações avançadas
- [ ] Efeitos de transição customizáveis

### 📈 Planejado para v1.3.0
- [ ] Machine Learning para otimização
- [ ] Recomendações automáticas
- [ ] Predição de CTR
- [ ] Segmentação de audiência
- [ ] Personalização por usuário

### 🔌 Planejado para v2.0.0
- [ ] Gutenberg blocks
- [ ] Elementor widgets
- [ ] WooCommerce integration
- [ ] Multi-site support
- [ ] White-label mode
- [ ] Developer API hooks

---

## Formato das Versões

### [X.Y.Z] - AAAA-MM-DD

Onde:
- **X** (Major): Mudanças incompatíveis com versões anteriores
- **Y** (Minor): Novas funcionalidades compatíveis
- **Z** (Patch): Correções de bugs e pequenas melhorias

### Tipos de Mudanças
- `✨ Adicionado` - Novas funcionalidades
- `🔄 Modificado` - Mudanças em funcionalidades existentes
- `🗑️ Removido` - Funcionalidades removidas
- `🐛 Corrigido` - Correções de bugs
- `🔒 Segurança` - Correções de vulnerabilidades
- `📚 Documentação` - Apenas mudanças em documentação
- `⚡ Performance` - Melhorias de desempenho

---

**Desenvolvido por Alan de Bortolo**  
**Primeira versão**: 12 de Novembro de 2025
