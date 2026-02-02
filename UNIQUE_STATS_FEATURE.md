# 📊 Estatísticas Únicas vs Totais - DesignMaster Ads

## 🎯 Funcionalidade Implementada

O sistema agora diferencia entre **visualizações/cliques totais** e **visualizações/cliques únicos**.

### Como Funciona

**Identificação de Usuário Único:**
- Combinação de IP do usuário + User Agent do navegador
- Exemplo: `192.168.1.1|Mozilla/5.0 Chrome/120...`

**Contadores:**
- **Total**: Conta TODAS as interações (incluindo repetições)
- **Único**: Conta apenas UMA vez por pessoa única (IP + navegador)

---

## 📈 Dashboard - Cards de Estatísticas

### Card 1: Total Views (30 dias)
```
Total: 1.234 visualizações
↓
847 visitantes únicos
```

### Card 2: Total Clicks (30 dias)
```
Total: 89 cliques
↓
52 usuários únicos
```

### Card 3: Average CTR
```
CTR Total: 7.2%
↓
CTR Único: 6.1%
```

---

## 🔧 Implementação Técnica

### Nova Função: `get_total_stats()`

**Localização:** `includes/class-dm-analytics.php`

```php
$stats = DM_Ads_Analytics::get_total_stats(30);

// Retorna:
array(
    'total_views' => 1234,      // Todas as views
    'unique_views' => 847,      // Views únicas por IP+UA
    'total_clicks' => 89,       // Todos os cliques
    'unique_clicks' => 52,      // Cliques únicos por IP+UA
    'total_ctr' => 7.2,         // CTR total
    'unique_ctr' => 6.1         // CTR único
)
```

### Query SQL para Únicos

```sql
SELECT COUNT(DISTINCT CONCAT(COALESCE(user_ip, ''), '|', COALESCE(user_agent, ''))) 
FROM wp_dm_ads_stats 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
AND event_type = 'view'
```

---

## 🎨 Visualização no Dashboard

**Antes:**
```
Total Views: 1.234
Banner impressions
```

**Depois:**
```
Total Views: 1.234
Banner impressions
  847 visitantes únicos
```

---

## 🌐 Traduções Adicionadas

```po
msgid "unique visitors"
msgstr "visitantes únicos"

msgid "unique users"
msgstr "usuários únicos"

msgid "unique CTR"
msgstr "CTR único"
```

---

## 📊 Casos de Uso

### Exemplo 1: Banner Promocional
- **1.000 views totais** = banner foi carregado 1.000 vezes
- **650 views únicos** = 650 pessoas diferentes viram o banner
- **Interpretação**: Em média, cada pessoa viu o banner 1,5 vezes

### Exemplo 2: Taxa de Cliques
- **50 cliques totais** de **1.000 views** = 5% CTR total
- **35 cliques únicos** de **650 views únicas** = 5,4% CTR único
- **Interpretação**: Pessoas únicas têm maior propensão a clicar

### Exemplo 3: Análise de Reengajamento
- **500 views totais** - **300 views únicos** = **200 views repetidas**
- **Insight**: 40% das visualizações são de pessoas retornando

---

## ✅ Benefícios

1. **Métricas Mais Precisas**
   - Saber quantas pessoas REALMENTE viram o banner
   - Não inflar números com recarregamentos de página

2. **Melhor ROI**
   - CTR único mostra conversão real de pessoas
   - Total mostra exposição da marca

3. **Análise de Comportamento**
   - Ver diferença entre total e único indica reengajamento
   - Alta diferença = usuários retornam múltiplas vezes

4. **Conformidade LGPD**
   - Usa apenas IP anonimizado + User Agent
   - Não usa cookies ou tracking invasivo

---

## 🚀 Próximas Melhorias Possíveis

- [ ] Gráfico de visualizações únicas vs totais ao longo do tempo
- [ ] Relatório de "taxa de retorno" (total/único - 1)
- [ ] Filtro para ver apenas métricas únicas
- [ ] Exportar CSV com ambas as métricas
- [ ] Dashboard widget com comparativo

---

**Versão:** 1.1.0  
**Status:** ✅ Implementado e Testado  
**Arquivos Modificados:**
- `includes/class-dm-analytics.php`
- `templates/admin/dashboard.php`
- `languages/designmaster-ads-pt_BR.po`
- `languages/designmaster-ads-pt_BR.mo`
