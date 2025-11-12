# DesignMaster Ads

Sistema completo de gestão de banners para WordPress com analytics avançado, rotação inteligente e estatísticas detalhadas.

## 🚀 Funcionalidades

### Recursos Principais

- **Gestão de Banners**
  - Custom Post Type dedicado
  - Upload de imagem com WordPress Media Library
  - Campo de imagem customizado com preview
  - URL de destino configurável
  - Sistema de agendamento (data/hora início e fim)
  - Status ativo/inativo
  - Sistema de prioridade (peso 1-100)

### Áreas de Banner (Zones)
- ✅ **Fixo**: Exibe sempre o mesmo banner
- ✅ **Rotativo por Reload**: Muda a cada carregamento da página
- ✅ **Rotativo por Tempo**: Muda automaticamente a cada X segundos
- ✅ Dimensões customizáveis
- ✅ Shortcodes e widgets

### Analytics e Estatísticas 📊
- ✅ **Visualizações (Views)**: Quantas vezes o banner foi exibido
- ✅ **Cliques (Clicks)**: Quantos cliques cada banner recebeu
- ✅ **CTR (Click-Through Rate)**: Taxa de cliques calculada automaticamente
- ✅ **Gráficos interativos**: Visualização com Chart.js
- ✅ **Relatórios por período**: Hoje, últimos 7 dias, 30 dias, personalizado
- ✅ **Comparação de performance**: Entre diferentes banners e zonas
- ✅ **Exportação de dados**: CSV e PDF
- ✅ **Heatmap de horários**: Melhores horários de performance
- ✅ **Analytics por dispositivo**: Desktop, Mobile, Tablet
- ✅ **Geolocalização**: Tracking por país/região (opcional)

### Dashboard Admin
- 📈 Gráficos de linha para tendências temporais
- 📊 Gráficos de barras para comparações
- 🥧 Gráficos de pizza para distribuição
- 🎯 KPIs em tempo real
- 📱 Interface responsiva

## 📦 Estrutura do Projeto

```
designmaster-ads/
├── designmaster-ads.php          # Arquivo principal do plugin
├── README.md                      # Este arquivo
├── .gitignore                     # Arquivos ignorados pelo Git
│
├── assets/                        # Assets do plugin
│   ├── css/
│   │   ├── admin.css             # Estilos do admin
│   │   └── public.css            # Estilos do frontend
│   └── js/
│       ├── admin.js              # Scripts do admin
│       ├── analytics.js          # Scripts de analytics
│       └── banner-rotator.js     # Rotação de banners
│
├── includes/                      # Classes PHP principais
│   ├── class-dm-ads.php          # Classe principal
│   ├── class-dm-ads-activator.php    # Ativação
│   ├── class-dm-ads-deactivator.php  # Desativação
│   ├── class-dm-banner.php       # Custom Post Type Banners
│   ├── class-dm-zone.php         # Gestão de Zones
│   ├── class-dm-display.php      # Display frontend
│   ├── class-dm-analytics.php    # Sistema de analytics
│   ├── class-dm-tracker.php      # Tracking de views/clicks
│   └── class-dm-admin.php        # Interface admin
│
├── templates/                     # Templates
│   ├── admin/
│   │   ├── dashboard.php         # Dashboard principal
│   │   ├── analytics.php         # Página de analytics
│   │   └── zones-manager.php     # Gestão de zones
│   └── public/
│       ├── banner-fixed.php      # Template banner fixo
│       ├── banner-reload.php     # Template rotativo reload
│       └── banner-timed.php      # Template rotativo tempo
│
└── languages/                     # Internacionalização
    └── designmaster-ads.pot
```

## 🔧 Instalação

1. Faça upload da pasta `designmaster-ads` para `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Configure as áreas de banner em 'DM Ads > Zones'
4. Adicione seus banners em 'DM Ads > Banners'

## 📝 Uso

### Shortcode
```php
[dm_ads zone="header-banner"]
```

### Código PHP
```php
<?php
if (function_exists('dm_ads_display')) {
    dm_ads_display('header-banner');
}
?>
```

### Widget
Adicione o widget "DesignMaster Ads" em qualquer área de widgets.

## 📊 Database Schema

### Tabela: `{prefix}_dm_ads_stats`
```sql
- id (bigint)
- banner_id (bigint)
- zone_id (bigint)
- event_type (enum: 'view', 'click')
- user_ip (varchar)
- user_agent (text)
- device_type (enum: 'desktop', 'mobile', 'tablet')
- country_code (varchar)
- referer (text)
- created_at (datetime)
```

## 🛠️ Tecnologias

- PHP 7.4+
- WordPress 5.8+
- Chart.js 4.x (Gráficos)
- JavaScript ES6+
- MySQL 5.7+

## 📈 Roadmap

- [ ] A/B Testing de banners
- [ ] Integração com Google Analytics
- [ ] Targeting por categoria/tag de posts
- [ ] API REST para integração externa
- [ ] Modo escuro no admin
- [ ] Relatórios agendados por email

## 👨‍💻 Autor

**Alan de Bortolo**

## 📄 Licença

GPL v2 or later
