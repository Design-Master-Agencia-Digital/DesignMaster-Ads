# Troubleshooting - DesignMaster Ads

## Problema: Shortcode não exibe banner

### Sintomas
- Shortcode `[dm_ads zone="slug"]` adicionado à página
- Visualização é contada no analytics
- Banner não aparece na página

### Causas Possíveis

#### 1. Banner sem imagem
**Solução**: Certifique-se de que o banner tem uma imagem configurada
- Vá em DM Ads > Banners
- Edite o banner
- Na caixa "Banner Image", clique em "Upload Image"
- Selecione uma imagem da biblioteca de mídia
- Salve o banner

#### 2. Zona não configurada corretamente
**Solução**: Verifique se a zona existe
- Vá em DM Ads > Zones
- Verifique se existe uma zona com o slug usado no shortcode
- O slug deve corresponder exatamente (sem espaços ou caracteres especiais)

#### 3. Banner inativo ou fora do período
**Solução**: Verifique o status e agendamento
- Edite o banner
- Verifique se o Status está marcado como "Ativo"
- Verifique as datas de início e fim (se configuradas)

#### 4. Banner em zona errada
**Solução**: Verifique a zona do banner
- Edite o banner
- Na seção "Banner Settings"
- Verifique se a "Banner Zone" corresponde ao slug usado no shortcode

### Debug Mode

Para ativar mensagens de debug (visíveis apenas para administradores):

1. Edite o arquivo `wp-config.php`
2. Adicione ou altere:
```php
define('WP_DEBUG', true);
```
3. Recarregue a página
4. Inspecione o código HTML (Ctrl+U ou Cmd+U)
5. Procure por comentários HTML com `<!-- DM Ads Debug: ... -->`

As mensagens de debug irão informar:
- Se a zona não foi encontrada
- Se não há banners ativos para aquela zona

---

## Problema: Banner aparece como "Agendado" mas deveria estar ativo

### Sintomas
- Banner configurado como ativo
- Data de início está no passado (ou vazia)
- Na lista de banners, aparece como "Agendado" (azul)

### Causa
Problema de timezone - WordPress pode estar usando timezone diferente do esperado

### Solução

#### Opção 1: Verificar timezone do WordPress
1. Vá em **Configurações > Geral**
2. Verifique o **Fuso horário**
3. Defina para sua timezone local (ex: "São Paulo")

#### Opção 2: Limpar data de início
1. Edite o banner
2. Na caixa "Schedule"
3. Limpe o campo "Start Date/Time"
4. Deixe vazio para que o banner esteja sempre ativo
5. Salve

#### Opção 3: Ajustar data manualmente
1. Edite o banner
2. Configure a "Start Date/Time" para uma data no passado
3. Use formato: `YYYY-MM-DD HH:MM:SS` (ex: `2025-01-01 00:00:00`)

### Como funciona o status do banner

O sistema verifica 3 condições:

1. **Status Manual**: Campo "_dm_banner_status" = "active"
2. **Data de Início**: Se configurada, deve ser <= data/hora atual
3. **Data de Fim**: Se configurada, deve ser > data/hora atual

O banner será exibido apenas se TODAS as condições forem atendidas.

Na lista de banners, o status é exibido como:

- **● Ativo** (verde): Status ativo, dentro do período
- **◐ Agendado** (azul): Status ativo, mas data de início ainda não chegou
- **● Expirado** (vermelho): Status ativo, mas data de fim já passou
- **○ Inativo** (cinza): Status desativado manualmente

---

## Problema: Banner não está rastreando visualizações/cliques

### Sintomas
- Banner é exibido corretamente
- Visualizações e cliques não aparecem no Analytics

### Soluções

#### 1. Verificar configurações de rastreamento
- Vá em **DM Ads > Settings**
- Verifique se "Track Banner Views" está marcado
- Verifique se "Track Banner Clicks" está marcado
- Salve as configurações

#### 2. Verificar JavaScript
- Abra o console do navegador (F12)
- Procure por erros JavaScript
- Verifique se jQuery está carregado
- Verifique se `banner-rotator.js` está carregado

#### 3. Verificar AJAX
- Abra o console do navegador
- Na aba Network/Rede
- Recarregue a página
- Procure por chamadas para `admin-ajax.php`
- Verifique se há erros 404 ou 500

#### 4. Verificar tabela do banco de dados
Execute no phpMyAdmin ou WP-CLI:
```sql
SELECT * FROM wp_dm_ads_stats LIMIT 10;
```

Se a tabela não existir, desative e reative o plugin.

---

## Problema: Zona com rotação temporizada não está funcionando

### Sintomas
- Zona configurada como "Timed (Auto rotate)"
- Banner não muda automaticamente
- Intervalo configurado não é respeitado

### Soluções

#### 1. Verificar JavaScript
- Abra o console do navegador (F12)
- Procure por erros
- Verifique se `banner-rotator.js` está carregado

#### 2. Verificar se há múltiplos banners
- A rotação só funciona se houver 2+ banners ativos na mesma zona
- Verifique em **DM Ads > Banners**
- Filtre pela zona
- Certifique-se de que há pelo menos 2 banners ativos

#### 3. Verificar intervalo de rotação
- Vá em **DM Ads > Zones**
- Edite a zona
- Verifique o campo "Rotation Interval"
- O padrão é 5 segundos
- Salve a zona

---

## Comandos úteis para debug

### Ver dados de uma zona específica
```php
<?php
$zone = DM_Ads_Zone::get_zone('footer-banner');
var_dump($zone);
?>
```

### Ver banners de uma zona
```php
<?php
$banners = DM_Ads_Zone::get_zone_banners('footer-banner');
echo '<pre>';
print_r($banners);
echo '</pre>';
?>
```

### Ver meta de um banner específico
```php
<?php
$banner_id = 123; // ID do banner
$image_id = get_post_meta($banner_id, '_dm_banner_image_id', true);
$zone = get_post_meta($banner_id, '_dm_banner_zone', true);
$status = get_post_meta($banner_id, '_dm_banner_status', true);
$start = get_post_meta($banner_id, '_dm_banner_start_date', true);
$end = get_post_meta($banner_id, '_dm_banner_end_date', true);

echo "Image ID: $image_id<br>";
echo "Zone: $zone<br>";
echo "Status: $status<br>";
echo "Start: $start<br>";
echo "End: $end<br>";
echo "Current Time: " . current_time('mysql');
?>
```

### Verificar timezone do WordPress
```php
<?php
echo 'WordPress Timezone: ' . get_option('timezone_string') . '<br>';
echo 'GMT Offset: ' . get_option('gmt_offset') . '<br>';
echo 'Current Time (MySQL): ' . current_time('mysql') . '<br>';
echo 'Current Time (Timestamp): ' . current_time('timestamp') . '<br>';
echo 'PHP Time: ' . date('Y-m-d H:i:s') . '<br>';
?>
```

---

## Contato para Suporte

Se nenhuma das soluções acima resolver seu problema:

1. Ative o WP_DEBUG
2. Anote a mensagem de erro completa
3. Liste as etapas para reproduzir o problema
4. Envie para o suporte técnico
