=== DesignMaster Ads ===
Contributors: alandebortolo
Tags: banner, ads, advertising, statistics, analytics, rotation, shortcode, charts, lazy-loading, performance
Requires at least: 5.8
Tested up to: 6.8
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Complete banner management system with advanced analytics, visual charts, lazy loading, smart rotation, and detailed statistics.

== Description ==

DesignMaster Ads is a complete plugin for managing advertising banners in WordPress, featuring an advanced analytics system, interactive visual charts, and performance optimizations with lazy loading.

= Key Features =

* **Complete Banner Management**
  * Image upload via WordPress Media Library
  * Configurable target URLs
  * Scheduling system (start and end date/time)
  * Active/Inactive status
  * Priority system (weight 1-100)

* **Banner Zones**
  * Fixed Banner (always the same)
  * Rotation on Reload (random on each load)
  * Timed Rotation (changes automatically)
  * Simple shortcode: `[dm_ads zone="slug"]`

* **Powerful Analytics**
  * 📊 Interactive charts with Chart.js
  * 📈 Performance trend chart (7 days)
  * 🥧 Device distribution chart
  * 👁️ Real-time views and clicks
  * 📊 Detailed Click-Through Rate (CTR)
  * 📱 Device distribution (desktop/mobile/tablet)
  * ⏰ Performance by hour of day
  * 🏆 Top banners by performance
  * 📥 Export to CSV
  * 📅 Custom date range

* **Performance and Optimization**
  * 🚀 Native image lazy loading
  * ⚡ JavaScript fallback with IntersectionObserver
  * 💫 Smooth and modern animations
  * 🎨 Enhanced UI/UX with gradients and hover effects
  * 📦 Smart query caching

* **Privacy**
  * 🔒 GDPR/LGPD compliant
  * 🛡️ IP Anonymization (optional)
  * 🏠 No external tracking
  * 💾 Data stored locally

* **Translations**
  * 🇧🇷 Portuguese (Brazil) - 100%
  * 🇺🇸 English - 100%
  * 🌐 Translation-ready (.pot included)

= How to Use =

1. Install and activate the plugin
2. Go to **DM Ads > Zones** and create a banner zone
3. Go to **DM Ads > Banners** and create a new banner
4. Configure the image, URL, and banner zone
5. Add the shortcode to the page: `[dm_ads zone="zone-name"]`
6. Track statistics in **DM Ads > Analytics**

= Shortcodes =

`[dm_ads zone="header"]` - Displays banner from "header" zone
`[dm_ads zone="sidebar"]` - Displays banner from "sidebar" zone
`[dm_ads zone="footer"]` - Displays banner from "footer" zone

= PHP Function for Themes =

`<?php dm_ads_display('header'); ?>`

== Installation ==

= Automatic Installation =

1. Log in to your WordPress dashboard
2. Go to **Plugins > Add New**
3. Search for "DesignMaster Ads"
4. Click **Install Now**
5. Activate the plugin

= Manual Installation =

1. Faça download do arquivo .zip
2. Vá em **Plugins > Adicionar Novo > Enviar Plugin**
3. Escolha o arquivo .zip e clique em **Instalar Agora**
4. Ative o plugin

= Via FTP =

1. Extraia o arquivo .zip
2. Envie a pasta `designmaster-ads` para `/wp-content/plugins/`
3. Ative o plugin no painel WordPress

= Após Ativação =

1. Vá em **DM Ads** no menu lateral
2. Crie suas primeiras zonas de banner
3. Adicione banners às zonas
4. Use os shortcodes nas páginas

== Frequently Asked Questions ==

= O plugin é gratuito? =

Sim, 100% gratuito e open source sob licença GPL v2.

= Funciona com qualquer tema? =

Sim, funciona com qualquer tema que siga os padrões do WordPress.

= Os dados são enviados para servidores externos? =

Não. Todos os dados são armazenados no seu próprio banco de dados. Não há comunicação com servidores externos.

= É compatível com LGPD/GDPR? =

Sim. O plugin inclui opção de anonimizar IPs e não faz rastreamento externo ou uso de cookies.

= Posso usar em sites comerciais? =

Sim, a licença GPL permite uso comercial sem restrições.

= Como adicionar banners rotativos? =

Crie uma zona do tipo "Reload" (aleatório) ou "Timed" (temporizado), adicione múltiplos banners à mesma zona e use o shortcode da zona.

= Suporta múltiplas imagens por banner? =

Atualmente cada banner suporta uma imagem. Para rotação, crie múltiplos banners na mesma zona.

= Os gráficos funcionam em tempo real? =

Os dados são atualizados via AJAX a cada visualização e clique. Os gráficos refletem os dados mais recentes ao carregar a página de Analytics.

= Como rastrear banners em um período específico? =

Na página Analytics, selecione "Período Personalizado" e escolha as datas de início e fim desejadas.

= O plugin afeta a performance do site? =

O plugin é otimizado para performance mínima. Usa cache, queries otimizadas e AJAX assíncrono para rastreamento.

= Posso desabilitar o rastreamento? =

Sim, vá em **DM Ads > Settings** e desmarque as opções de rastreamento de views e/ou clicks.

== Screenshots ==

1. Dashboard com visão geral de estatísticas e KPIs
2. Analytics com gráficos detalhados e período personalizado
3. Gerenciador de zonas de banner com tipos de rotação
4. Edição de banner com upload de imagem via Media Library
5. Lista de banners com colunas detalhadas (thumbnail, zona, status, prioridade, estatísticas)
6. Exemplo de banner exibido no frontend do site

== Changelog ==

= 1.1.0 - 2025-01-12 =
* Added: Portuguese (PT-BR) translation with .po/.mo files
* Added: Custom image field with WordPress Media Library integration
* Added: Detailed columns in banner listing (thumbnail, zone, status, priority, schedule, stats)
* Added: Custom date range picker in Analytics
* Added: Debug mode for administrators (visible with WP_DEBUG)
* Added: Comprehensive troubleshooting documentation
* Changed: Replaced Featured Image with custom image meta field
* Changed: Improved image upload UX with WordPress Media Uploader
* Changed: Enhanced banner editing interface with inline image selection
* Fixed: Timezone consistency across all date comparisons
* Fixed: Skip banners without image in frontend display
* Fixed: Shortcode display issues with proper validation
* Technical: Added load_plugin_textdomain() for internationalization
* Technical: Implemented wp.media JavaScript API for uploads
* Technical: Added _dm_banner_image_id meta field
* Technical: Modified banner and zone classes for custom image handling
* Technical: Updated admin class with wp_enqueue_media()

= 1.0.0 - 2025-01-11 =
* Initial release
* Custom Post Type for banner management
* Zone management system with three rotation types (fixed, reload, timed)
* Comprehensive analytics dashboard with Chart.js graphs
* Device detection (desktop/mobile/tablet)
* IP anonymization for privacy compliance
* Admin interface with Dashboard, Analytics, Zones, and Settings pages
* Frontend display templates for all rotation types
* AJAX-based tracking system for views and clicks
* Export analytics data to CSV
* Shortcode support: [dm_ads zone="slug"]
* PHP function for theme integration: dm_ads_display()
* Scheduling system with start/end dates
* Priority/weight system for banner rotation
* Complete documentation (README, INSTALLATION, TECHNICAL, TROUBLESHOOTING)
* Git version control with structured commits

== Upgrade Notice ==

= 1.1.0 =
Major update! Adds Portuguese translation, custom date ranges in Analytics, improved image upload with Media Library, and detailed banner listing. Recommended update for all users.

= 1.0.0 =
Initial release of DesignMaster Ads. Welcome!

== Additional Info ==

= Minimum Requirements =

* WordPress 5.8 or higher
* PHP 7.4 or higher
* MySQL 5.7 or higher

= Recommended =

* WordPress 6.0 or higher
* PHP 8.0 or higher
* MySQL 8.0 or higher

= Browser Support =

* Chrome (latest)
* Firefox (latest)
* Safari (latest)
* Edge (latest)

= Third-Party Libraries =

* Chart.js 4.4.0 - For interactive charts (MIT License)
* jQuery - Included with WordPress

= Credits =

* Chart.js for beautiful interactive charts
* WordPress community for guidelines and support
* Contributors and testers

== Privacy Policy ==

DesignMaster Ads is designed with privacy in mind and complies with LGPD and GDPR regulations.

**What DesignMaster Ads does NOT do:**
* Track users across websites
* Send data to external servers
* Use cookies for tracking purposes
* Collect personal information
* Share data with third parties

**What DesignMaster Ads stores locally:**
* Banner view counts (in your database)
* Banner click counts (in your database)
* Device type (desktop/mobile/tablet)
* Anonymized IP addresses (optional, last octet removed)
* Timestamps of interactions
* Zone and banner associations

**Data Storage:**
All data remains on your server in your WordPress database. Nothing is transmitted to external servers or services.

**Data Deletion:**
When you uninstall the plugin, you can choose to delete all data including statistics and custom tables.

**User Rights:**
Site administrators have full control over all data and can delete or export it at any time.

== Support ==

= Documentation =

Complete documentation is available in the plugin folder:
* README.md - Overview and quick start
* INSTALLATION.md - Detailed installation guide
* TECHNICAL.md - Technical documentation for developers
* TROUBLESHOOTING.md - Common issues and solutions
* WORDPRESS_ORG_SUBMISSION.md - Publishing guide

= Getting Help =

* [Support Forum](https://wordpress.org/support/plugin/designmaster-ads/)
* [GitHub Issues](https://github.com/seu-usuario/designmaster-ads/issues)
* [Documentation](https://github.com/seu-usuario/designmaster-ads)

= Contributing =

Contributions are welcome! Please visit the [GitHub repository](https://github.com/seu-usuario/designmaster-ads) to:
* Report bugs
* Suggest features
* Submit pull requests
* Translate the plugin

= Translations =

The plugin is translation-ready with complete .pot template included. Current translations:
* English (en_US) - Default
* Portuguese Brazil (pt_BR) - 100% complete

Help us translate to your language!
