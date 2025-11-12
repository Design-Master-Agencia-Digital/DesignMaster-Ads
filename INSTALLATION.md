# 🚀 Guia de Instalação e Uso - DesignMaster Ads

## 📦 Instalação

### Método 1: Upload Manual
1. Faça upload da pasta `DesignMaster Ads` para `/wp-content/plugins/`
2. Renomeie para `designmaster-ads` (sem espaços)
3. No painel do WordPress, vá em **Plugins > Plugins Instalados**
4. Ative o plugin **DesignMaster Ads**

### Método 2: Via FTP
1. Conecte-se via FTP ao seu servidor
2. Navegue até `/wp-content/plugins/`
3. Faça upload da pasta completa
4. Ative pelo painel do WordPress

## ⚙️ Configuração Inicial

### 1. Criar Áreas de Banner (Zones)
1. Vá em **DM Ads > Zones**
2. Clique em **Add New Zone**
3. Preencha:
   - **Nome**: Ex: "Banner do Cabeçalho"
   - **Slug**: Ex: "header-banner" (usado no shortcode)
   - **Tipo**: Fixo, Rotativo por Reload ou Rotativo por Tempo
   - **Dimensões**: Largura x Altura em pixels
4. Clique em **Create Zone**

### 2. Adicionar Banners
1. Vá em **DM Ads > Add New Banner**
2. Defina um **Título** para o banner
3. Adicione a **Imagem Destacada** (Featured Image)
4. Configure:
   - **Target URL**: Link de destino do banner
   - **Banner Zone**: Selecione a zona onde será exibido
   - **Priority**: 1-100 (maior = maior chance de exibição)
   - **Status**: Ativo ou Inativo
5. **Agendamento** (opcional):
   - Data/Hora de Início
   - Data/Hora de Fim
6. Clique em **Publicar**

## 🎨 Exibindo Banners no Site

### Método 1: Shortcode (Mais Fácil)
Cole em qualquer post, página ou widget:
```
[dm_ads zone="header-banner"]
```

### Método 2: Código PHP (Em Temas)
Adicione no arquivo do tema (`header.php`, `sidebar.php`, etc):
```php
<?php
if (function_exists('dm_ads_display')) {
    dm_ads_display('header-banner');
}
?>
```

### Método 3: Widget
1. Vá em **Aparência > Widgets**
2. Arraste o widget **DesignMaster Ads**
3. Selecione a zona desejada
4. Salve

## 📊 Analytics e Estatísticas

### Dashboard Principal
Acesse **DM Ads > Dashboard** para ver:
- Total de visualizações (30 dias)
- Total de cliques (30 dias)
- CTR médio
- Top 5 banners

### Analytics Detalhado
Acesse **DM Ads > Analytics** para:
- Gráficos de tendência (Views x Clicks)
- Distribuição por dispositivo (Desktop/Mobile/Tablet)
- Performance por hora do dia
- Ranking de banners
- Filtros por período
- Exportação para CSV

### Estatísticas por Banner
Ao editar um banner, veja no painel lateral:
- Total de Views
- Total de Clicks
- CTR (%)
- Link para analytics detalhado

## 🎯 Tipos de Rotação

### Fixo
- Sempre exibe o **mesmo banner** (maior prioridade)
- Ideal para: Promoções específicas, parceiros fixos

### Rotativo por Reload
- Exibe um **banner diferente** a cada carregamento da página
- Seleção baseada em **peso/prioridade**
- Ideal para: Múltiplos anunciantes, variedade

### Rotativo por Tempo
- Banners **mudam automaticamente** a cada X segundos
- Transições suaves com fade
- Navegação por dots
- Ideal para: Carrosséis, destacar múltiplas ofertas

## 🛠️ Configurações

Acesse **DM Ads > Settings** para:

### Tracking
- ☑️ **Track Banner Views**: Registrar visualizações
- ☑️ **Track Banner Clicks**: Registrar cliques
- ☑️ **Anonymize IP**: Compliance GDPR (recomendado)

### Performance
- **Cache Time**: Tempo de cache das queries (segundos)
- **Default Rotation Time**: Intervalo padrão para rotação (segundos)

## 📝 Exemplos Práticos

### Exemplo 1: Banner de Topo 728x90
```
Nome: "Leaderboard Header"
Slug: "header-leaderboard"
Tipo: Rotativo por Reload
Dimensões: 728 x 90
```
Uso: `[dm_ads zone="header-leaderboard"]`

### Exemplo 2: Sidebar Banner 300x250
```
Nome: "Sidebar Medium Rectangle"
Slug: "sidebar-banner"
Tipo: Fixo
Dimensões: 300 x 250
```
Uso: `[dm_ads zone="sidebar-banner"]`

### Exemplo 3: Footer Rotativo 970x90
```
Nome: "Footer Banner Carousel"
Slug: "footer-carousel"
Tipo: Rotativo por Tempo (5 segundos)
Dimensões: 970 x 90
```
Uso: `[dm_ads zone="footer-carousel"]`

## 🔧 Troubleshooting

### Banner não aparece?
1. ✅ Verifique se o banner está **Publicado**
2. ✅ Confirme que o **Status** está **Ativo**
3. ✅ Certifique-se que a **Zona** está correta
4. ✅ Verifique se há uma **Imagem Destacada**
5. ✅ Confira as datas de **Agendamento**

### Estatísticas não aparecem?
1. ✅ Ative tracking em **Settings**
2. ✅ Aguarde algumas visualizações/cliques
3. ✅ Limpe o cache do WordPress

### Rotação por tempo não funciona?
1. ✅ Verifique se há **múltiplos banners** na zona
2. ✅ Confirme que todos estão **ativos**
3. ✅ Desative plugins de cache agressivos

## 🎨 Personalização CSS

Adicione em **Aparência > Personalizar > CSS Adicional**:

```css
/* Customizar container */
.dm-ads-container {
    margin: 30px auto;
    border: 2px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

/* Efeito hover */
.dm-ads-banner a:hover img {
    opacity: 0.9;
    transform: scale(1.02);
    transition: all 0.3s ease;
}

/* Customizar dots de navegação */
.dm-ads-dot {
    width: 12px;
    height: 12px;
    background: #333;
}

.dm-ads-dot.active {
    background: #0073aa;
}
```

## 📱 Responsividade

Os banners são responsivos por padrão. Para ajustes específicos:

```css
@media (max-width: 768px) {
    .dm-ads-container {
        max-width: 100% !important;
    }
    
    .dm-ads-banner img {
        width: 100%;
        height: auto;
    }
}
```

## 🔐 Segurança & GDPR

- ✅ IPs podem ser anonimizados
- ✅ Dados armazenados localmente (seu servidor)
- ✅ Nenhum script de terceiros
- ✅ Compatível com LGPD/GDPR

## 📞 Suporte

- 📧 Email: suporte@seudominio.com
- 📚 Documentação: Veja README.md
- 🐛 Issues: GitHub Issues

## 🚀 Próximos Passos

1. ✅ Crie suas primeiras zonas
2. ✅ Adicione banners
3. ✅ Insira shortcodes nas páginas
4. ✅ Monitore analytics
5. ✅ Otimize com base nos dados

---

**Desenvolvido com ❤️ por Alan de Bortolo**
