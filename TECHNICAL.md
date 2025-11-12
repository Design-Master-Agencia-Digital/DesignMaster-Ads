# 📊 DesignMaster Ads - Informações Técnicas

## 🗄️ Estrutura do Banco de Dados

### Tabela: `wp_dm_ads_stats`

```sql
CREATE TABLE wp_dm_ads_stats (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    banner_id bigint(20) NOT NULL,
    zone_id bigint(20) NOT NULL,
    event_type enum('view','click') NOT NULL,
    user_ip varchar(45) DEFAULT NULL,
    user_agent text DEFAULT NULL,
    device_type enum('desktop','mobile','tablet') DEFAULT 'desktop',
    country_code varchar(2) DEFAULT NULL,
    referer text DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY banner_id (banner_id),
    KEY zone_id (zone_id),
    KEY event_type (event_type),
    KEY created_at (created_at),
    KEY device_type (device_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Custom Post Type: `dm_banner`

**Meta Fields:**
- `_dm_banner_url` (string) - URL de destino do banner
- `_dm_banner_zone` (string) - Slug da zona onde será exibido
- `_dm_banner_priority` (int) - Prioridade/peso (1-100)
- `_dm_banner_status` (string) - 'active' ou 'inactive'
- `_dm_banner_start_date` (datetime) - Data/hora de início
- `_dm_banner_end_date` (datetime) - Data/hora de fim

### Options (Zones)

Armazenadas como: `dm_ads_zone_{slug}`

**Estrutura:**
```php
array(
    'name' => 'Zone Name',
    'slug' => 'zone-slug',
    'type' => 'fixed|reload|timed',
    'width' => 728,
    'height' => 90,
    'rotation_interval' => 5
)
```

### Plugin Options

- `dm_ads_version` - Versão do plugin
- `dm_ads_track_views` - Boolean: rastrear visualizações
- `dm_ads_track_clicks` - Boolean: rastrear cliques
- `dm_ads_anonymize_ip` - Boolean: anonimizar IPs
- `dm_ads_cache_time` - Int: tempo de cache em segundos
- `dm_ads_default_rotation_time` - Int: intervalo padrão de rotação

## 🔌 Hooks & Filters

### Actions

```php
// Admin
add_action('admin_enqueue_scripts', 'enqueue_admin_assets');
add_action('admin_menu', 'add_admin_menu');
add_action('save_post_dm_banner', 'save_banner_meta');

// Frontend
add_action('wp_enqueue_scripts', 'enqueue_public_assets');

// AJAX
add_action('wp_ajax_dm_ads_track_view', 'track_view');
add_action('wp_ajax_nopriv_dm_ads_track_view', 'track_view');
add_action('wp_ajax_dm_ads_track_click', 'track_click');
add_action('wp_ajax_nopriv_dm_ads_track_click', 'track_click');
```

### Filters (Planejados para futuro)

```php
// Filtrar banners antes da exibição
apply_filters('dm_ads_banners', $banners, $zone_slug);

// Modificar HTML do banner
apply_filters('dm_ads_banner_html', $html, $banner, $zone);

// Customizar query de banners
apply_filters('dm_ads_query_args', $args, $zone_slug);

// Modificar dados de tracking
apply_filters('dm_ads_tracking_data', $data, $banner_id);
```

## 🎯 AJAX Endpoints

### Track View
```javascript
POST /wp-admin/admin-ajax.php
{
    action: 'dm_ads_track_view',
    nonce: 'wp_nonce',
    banner_id: 123,
    zone_id: 'header-banner'
}
```

### Track Click
```javascript
POST /wp-admin/admin-ajax.php
{
    action: 'dm_ads_track_click',
    nonce: 'wp_nonce',
    banner_id: 123,
    zone_id: 'header-banner'
}
```

## 📁 Estrutura de Arquivos Detalhada

```
designmaster-ads/
│
├── designmaster-ads.php          # Arquivo principal do plugin
│   ├── Define constantes
│   ├── Registra hooks de ativação/desativação
│   └── Inicia a classe principal
│
├── includes/                      # Classes PHP principais
│   ├── class-dm-ads.php          # Orquestrador principal
│   ├── class-dm-ads-activator.php    # Lógica de ativação
│   ├── class-dm-ads-deactivator.php  # Lógica de desativação
│   │
│   ├── class-dm-banner.php       # Custom Post Type de Banners
│   │   ├── Registra CPT
│   │   ├── Adiciona meta boxes
│   │   └── Salva meta fields
│   │
│   ├── class-dm-zone.php         # Gestão de Zones
│   │   ├── CRUD de zones
│   │   └── Query de banners por zone
│   │
│   ├── class-dm-tracker.php      # Sistema de Tracking
│   │   ├── Handlers AJAX
│   │   ├── Detecção de dispositivo
│   │   └── Anonimização de IP
│   │
│   ├── class-dm-analytics.php    # Analytics & Relatórios
│   │   ├── Queries de estatísticas
│   │   ├── Cálculo de métricas
│   │   └── Exportação CSV
│   │
│   ├── class-dm-display.php      # Exibição Frontend
│   │   ├── Shortcode handler
│   │   ├── Seleção de banners
│   │   └── Renderização
│   │
│   └── class-dm-admin.php        # Interface Admin
│       ├── Enqueue scripts/styles
│       ├── Registro de páginas admin
│       └── Handlers de formulários
│
├── templates/                     # Templates PHP
│   ├── admin/
│   │   ├── dashboard.php         # Dashboard principal
│   │   ├── analytics.php         # Página de analytics
│   │   ├── zones-manager.php     # Gerenciador de zones
│   │   └── settings.php          # Configurações
│   │
│   └── public/
│       ├── banner-fixed.php      # Template banner fixo
│       ├── banner-reload.php     # Template rotativo reload
│       └── banner-timed.php      # Template rotativo tempo
│
├── assets/                        # Assets estáticos
│   ├── css/
│   │   ├── admin.css             # Estilos admin
│   │   └── public.css            # Estilos frontend
│   │
│   └── js/
│       ├── admin.js              # Scripts admin
│       ├── analytics.js          # Gráficos Chart.js
│       └── banner-rotator.js     # Rotação frontend + tracking
│
└── languages/                     # Traduções (futuro)
    └── designmaster-ads.pot
```

## 🔄 Fluxo de Dados

### 1. Exibição de Banner
```
Usuário acessa página
    ↓
Shortcode [dm_ads zone="slug"] ou dm_ads_display()
    ↓
DM_Ads_Display::display_zone($slug)
    ↓
DM_Ads_Zone::get_zone_banners($slug)
    ↓
Filtra por: status, agendamento, prioridade
    ↓
Seleciona banner(s) baseado no tipo de zona
    ↓
Renderiza template apropriado
    ↓
JavaScript envia tracking de view via AJAX
    ↓
DM_Ads_Tracker::track_view() salva no BD
```

### 2. Clique em Banner
```
Usuário clica no banner
    ↓
Event listener JavaScript captura clique
    ↓
Envia AJAX para track_click
    ↓
DM_Ads_Tracker::track_click() salva no BD
    ↓
Redireciona para URL de destino
```

### 3. Geração de Analytics
```
Admin acessa Analytics
    ↓
DM_Ads_Analytics::get_stats_by_date()
    ↓
Query na tabela wp_dm_ads_stats
    ↓
Agrupa por data, tipo de evento, dispositivo
    ↓
Calcula métricas (CTR, totais, médias)
    ↓
Formata dados para Chart.js
    ↓
Renderiza gráficos interativos
```

## 🛡️ Segurança

### Sanitização
- `sanitize_text_field()` - Campos de texto
- `esc_url_raw()` / `esc_url()` - URLs
- `absint()` - Números inteiros
- `wp_kses_post()` - HTML (se necessário)

### Validação
- `wp_verify_nonce()` - Verificação de nonce
- `current_user_can()` - Capacidades de usuário
- `check_admin_referer()` - Referrer admin

### Escapamento
- `esc_html()` - Texto HTML
- `esc_attr()` - Atributos HTML
- `esc_url()` - URLs
- `esc_js()` - JavaScript

## ⚡ Performance

### Otimizações Implementadas
1. ✅ Índices no banco de dados
2. ✅ Queries otimizadas com meta_query
3. ✅ Lazy loading de assets
4. ✅ Caching de zones e banners (opcional)
5. ✅ Minimização de AJAX calls

### Recomendações
- Use cache de objeto (Redis/Memcached)
- Ative cache de página (WP Super Cache, W3 Total Cache)
- Otimize imagens de banners (WebP, compressão)
- Use CDN para assets estáticos

## 🔧 Requisitos

### Mínimos
- **WordPress**: 5.8+
- **PHP**: 7.4+
- **MySQL**: 5.7+
- **Espaço em disco**: ~5MB + dados de analytics

### Recomendados
- **WordPress**: 6.0+
- **PHP**: 8.0+
- **MySQL**: 8.0+
- **Memória PHP**: 256MB+

## 📊 Métricas Calculadas

### CTR (Click-Through Rate)
```php
$ctr = ($clicks / $views) * 100;
```

### Taxa de Conversão (futura)
```php
$conversion_rate = ($conversions / $clicks) * 100;
```

### Engagement Score (futura)
```php
$engagement = (
    ($views * 1) + 
    ($clicks * 10) + 
    ($conversions * 100)
) / $total_impressions;
```

## 🚀 Extensibilidade

### Adicionar Novo Tipo de Rotação

1. Criar novo template em `templates/public/`
2. Adicionar opção em `class-dm-zone.php`
3. Implementar lógica em `class-dm-display.php`
4. Adicionar JavaScript se necessário

### Integrar com Analytics Externo

```php
// Em class-dm-tracker.php
add_action('dm_ads_after_track_click', function($banner_id, $data) {
    // Enviar para Google Analytics
    // Enviar para Facebook Pixel
    // etc.
}, 10, 2);
```

## 📞 Suporte ao Desenvolvedor

Para dúvidas técnicas:
- 📧 dev@seudominio.com
- 💻 GitHub Issues
- 📚 Wiki do projeto

---

**Última atualização**: Novembro 2025
