# Guia de Submissão para WordPress.org

Este guia detalha o processo completo para publicar o **DesignMaster Ads** no repositório oficial de plugins do WordPress.

---

## 📋 Pré-requisitos

### 1. Checklist Técnico

Antes de submeter, certifique-se de que o plugin atende aos requisitos:

- [x] **Código original e GPL compatível** - Seu código deve ser 100% GPL v2 ou posterior
- [x] **Nenhum código ofuscado** - Todo código deve ser legível
- [x] **Sem links de afiliados** - Proibido links de afiliados no código
- [x] **Sem telemetria não autorizada** - Não pode coletar dados sem consentimento
- [x] **Prefix único** - Usamos `dm_ads_` e `DM_Ads_` (✓)
- [x] **Text domain correto** - `designmaster-ads` (✓)
- [x] **Sanitização e validação** - Todos os inputs são sanitizados (✓)
- [x] **Nonces para forms** - Todos os formulários têm nonces (✓)
- [x] **Prepared statements** - Queries usam `$wpdb->prepare()` (✓)
- [x] **Escaping de output** - Usamos `esc_html()`, `esc_attr()`, etc. (✓)

### 2. Documentação Necessária

- [x] README.md completo
- [x] CHANGELOG.md com histórico de versões
- [x] Licença GPL v2 ou posterior
- [x] Screenshots (mínimo 3, máximo 10)
- [x] Ícone do plugin (256x256px e 128x128px)
- [x] Banner promocional (772x250px e 1544x500px)

---

## 🚀 Passo a Passo para Submissão

### **Passo 1: Criar Conta WordPress.org**

1. Acesse: https://login.wordpress.org/register
2. Crie uma conta com seu email
3. Confirme o email
4. Faça login em: https://wordpress.org/support/users/

### **Passo 2: Preparar o Plugin**

#### 2.1. Criar README.txt (formato WordPress.org)

```bash
cd "/Users/alandebortolo/Wordpress/Plugins/DesignMaster Ads"
```

Criar arquivo `readme.txt` com formato específico do WordPress.org (veja template abaixo).

#### 2.2. Criar Assets para WordPress.org

Estrutura de pastas necessária:
```
assets/
├── icon-128x128.png       # Ícone pequeno
├── icon-256x256.png       # Ícone grande
├── banner-772x250.png     # Banner desktop
├── banner-1544x500.png    # Banner high-res
├── screenshot-1.png       # Screenshot do Dashboard
├── screenshot-2.png       # Screenshot do Analytics
├── screenshot-3.png       # Screenshot das Zones
├── screenshot-4.png       # Screenshot do Banner Edit
└── screenshot-5.png       # Screenshot do Frontend
```

#### 2.3. Validar Plugin com Plugin Check

```bash
# Instalar WP-CLI se ainda não tiver
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp

# Validar plugin
wp plugin verify-checksums designmaster-ads
```

Ou usar o plugin oficial: https://wordpress.org/plugins/plugin-check/

### **Passo 3: Submeter Plugin**

1. Acesse: https://wordpress.org/plugins/developers/add/
2. Faça login com sua conta WordPress.org
3. Preencha o formulário:
   - **Plugin Name**: DesignMaster Ads
   - **Plugin URL**: https://github.com/seu-usuario/designmaster-ads (ou seu repo)
   - **Description**: Sistema completo de gestão de banners com analytics avançado
   - **Upload ZIP**: Enviar arquivo .zip do plugin

4. Aceite os termos:
   - [ ] Leu as diretrizes de plugins
   - [ ] Código é GPL compatível
   - [ ] Não contém código ofuscado
   - [ ] Não rastreia usuários sem consentimento

5. Clique em **Submit Plugin**

### **Passo 4: Aguardar Revisão**

- **Tempo médio**: 7-14 dias
- **O que acontece**: 
  - WordPress Plugin Review Team irá revisar manualmente
  - Verificarão segurança, performance e compliance
  - Podem solicitar alterações

- **Você receberá email com**:
  - Aprovação ✅ + URL do SVN
  - OU Lista de problemas a corrigir ⚠️

### **Passo 5: Após Aprovação - Configurar SVN**

Quando aprovado, você receberá acesso a um repositório SVN:

```bash
# 1. Fazer checkout do repositório SVN
svn co https://plugins.svn.wordpress.org/designmaster-ads designmaster-ads-svn
cd designmaster-ads-svn

# 2. Estrutura do SVN
# trunk/     - Versão de desenvolvimento
# tags/      - Versões lançadas (1.0, 1.1, etc)
# assets/    - Ícones, banners, screenshots

# 3. Copiar arquivos do plugin para trunk/
cp -r /Users/alandebortolo/Wordpress/Plugins/DesignMaster\ Ads/* trunk/

# 4. Copiar assets
mkdir assets
cp icon-*.png assets/
cp banner-*.png assets/
cp screenshot-*.png assets/

# 5. Adicionar arquivos ao SVN
svn add trunk/*
svn add assets/*

# 6. Commit inicial
svn ci -m "Initial commit of DesignMaster Ads v1.1.0"

# 7. Criar tag da versão
svn cp trunk tags/1.1.0
svn ci -m "Tagging version 1.1.0"
```

### **Passo 6: Verificar Publicação**

Após commit no SVN, em até 15 minutos:
- Plugin aparece em: https://wordpress.org/plugins/designmaster-ads/
- Listado nos resultados de busca
- Disponível para instalação via admin do WordPress

---

## 📝 Template README.txt para WordPress.org

Criar arquivo `readme.txt` na raiz do plugin:

```txt
=== DesignMaster Ads ===
Contributors: alandebortolo
Donate link: https://seusite.com/donate
Tags: banner, ads, advertising, statistics, analytics, rotation
Requires at least: 5.8
Tested up to: 6.4
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sistema completo de gestão de banners com analytics avançado, rotação inteligente e estatísticas detalhadas.

== Description ==

DesignMaster Ads é um plugin completo para gestão de banners publicitários no WordPress, com sistema avançado de analytics e múltiplos tipos de rotação.

= Recursos Principais =

* **Gestão Completa de Banners**
  * Upload de imagens via WordPress Media Library
  * URLs de destino configuráveis
  * Sistema de agendamento (data/hora início e fim)
  * Status ativo/inativo
  * Sistema de prioridade (peso 1-100)

* **Áreas de Banner (Zones)**
  * Banner Fixo (sempre o mesmo)
  * Rotação por Reload (aleatório a cada carregamento)
  * Rotação Temporizada (muda automaticamente)
  * Shortcode simples: `[dm_ads zone="slug"]`

* **Analytics Poderoso**
  * Gráficos interativos com Chart.js
  * Visualizações e cliques
  * Taxa de cliques (CTR)
  * Distribuição por dispositivo (desktop/mobile/tablet)
  * Performance por hora do dia
  * Top banners por desempenho
  * Exportação para CSV
  * Período personalizado de datas

* **Privacidade**
  * LGPD/GDPR compliant
  * Anonimização de IP (opcional)
  * Sem rastreamento externo
  * Dados armazenados localmente

* **Traduções**
  * Português (Brasil) - 100%
  * Inglês - 100%
  * Translation-ready (.pot incluído)

= Como Usar =

1. Instale e ative o plugin
2. Vá em **DM Ads > Zones** e crie uma zona de banner
3. Vá em **DM Ads > Banners** e crie um novo banner
4. Configure a imagem, URL e zona do banner
5. Adicione o shortcode na página: `[dm_ads zone="nome-da-zona"]`
6. Acompanhe estatísticas em **DM Ads > Analytics**

= Shortcodes =

`[dm_ads zone="header"]` - Exibe banner da zona "header"
`[dm_ads zone="sidebar"]` - Exibe banner da zona "sidebar"
`[dm_ads zone="footer"]` - Exibe banner da zona "footer"

= Função PHP para Temas =

`<?php dm_ads_display('header'); ?>`

= Suporte =

* Documentação: https://github.com/seu-usuario/designmaster-ads
* Issues: https://github.com/seu-usuario/designmaster-ads/issues
* Fórum: https://wordpress.org/support/plugin/designmaster-ads

== Installation ==

= Instalação Automática =

1. Faça login no seu painel WordPress
2. Vá em **Plugins > Adicionar Novo**
3. Busque por "DesignMaster Ads"
4. Clique em **Instalar Agora**
5. Ative o plugin

= Instalação Manual =

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

Sim, 100% gratuito e open source.

= Funciona com qualquer tema? =

Sim, funciona com qualquer tema que siga os padrões do WordPress.

= Os dados são enviados para servidores externos? =

Não. Todos os dados são armazenados no seu próprio banco de dados.

= É compatível com LGPD/GDPR? =

Sim. O plugin inclui opção de anonimizar IPs e não faz rastreamento externo.

= Posso usar em sites comerciais? =

Sim, a licença GPL permite uso comercial.

= Como adicionar banners rotativos? =

Crie uma zona do tipo "Reload" ou "Timed", adicione múltiplos banners à zona e use o shortcode.

= Suporta múltiplas imagens por banner? =

Atualmente cada banner suporta uma imagem. Para rotação, crie múltiplos banners na mesma zona.

= Os gráficos funcionam em tempo real? =

Os dados são atualizados a cada visualização/clique via AJAX.

== Screenshots ==

1. Dashboard com visão geral de estatísticas
2. Analytics com gráficos detalhados e período personalizado
3. Gerenciador de zonas de banner
4. Edição de banner com upload de imagem
5. Lista de banners com colunas detalhadas
6. Exemplo de banner no frontend do site

== Changelog ==

= 1.1.0 - 2025-01-12 =
* Added: Portuguese (PT-BR) translation with .po/.mo files
* Added: Custom image field with WordPress Media Library integration
* Added: Detailed columns in banner listing (thumbnail, zone, status, priority, schedule, stats)
* Added: Custom date range picker in Analytics
* Added: Debug mode for administrators
* Added: Troubleshooting documentation
* Changed: Replaced Featured Image with custom image meta field
* Changed: Improved image upload UX with wp.media
* Fixed: Timezone consistency across all date comparisons
* Fixed: Skip banners without image in frontend display
* Technical: Added load_plugin_textdomain() for i18n support
* Technical: Implemented wp.media JavaScript API
* Technical: Added _dm_banner_image_id meta field

= 1.0.0 - 2025-01-11 =
* Initial release
* Custom Post Type for banner management
* Zone management system with three rotation types
* Comprehensive analytics with Chart.js graphs
* Device detection and IP anonymization
* Admin interface with Dashboard, Analytics, Zones, and Settings
* Frontend display templates for all rotation types
* Tracking system for views and clicks
* AJAX-based view/click tracking
* Export to CSV functionality
* Complete documentation

== Upgrade Notice ==

= 1.1.0 =
Adds Portuguese translation, custom date ranges in Analytics, and improved image upload with Media Library. Recommended update for all users.

= 1.0.0 =
Initial release of DesignMaster Ads.

== Additional Info ==

= Minimum Requirements =

* WordPress 5.8 or higher
* PHP 7.4 or higher
* MySQL 5.7 or higher

= Recommended =

* WordPress 6.0+
* PHP 8.0+
* MySQL 8.0+

= Credits =

* Chart.js for beautiful interactive charts
* WordPress community for guidelines and support

== Privacy Policy ==

DesignMaster Ads does not:
* Track users across websites
* Send data to external servers
* Use cookies for tracking
* Collect personal information

DesignMaster Ads stores:
* Banner view/click counts (locally in your database)
* Device type (desktop/mobile/tablet)
* Anonymized IP addresses (if enabled)
* Timestamps of interactions

All data remains on your server and is never transmitted elsewhere.
```

---

## 🎨 Criar Assets Visuais

### 1. Ícone do Plugin (icon-256x256.png)

Especificações:
- Tamanho: 256x256px (e versão 128x128px)
- Formato: PNG com fundo transparente
- Conteúdo: Logo ou ícone representativo
- Estilo: Simples, reconhecível em tamanho pequeno

### 2. Banner (banner-772x250.png)

Especificações:
- Tamanho: 772x250px (e versão 1544x500px para retina)
- Formato: PNG ou JPG
- Conteúdo: Nome do plugin + tagline + visual atrativo
- Texto: Legível, não muito pequeno

### 3. Screenshots

Tirar screenshots das telas principais:
1. Dashboard - Visão geral
2. Analytics - Gráficos
3. Zone Manager - Gerenciamento de áreas
4. Banner Edit - Edição de banner
5. Frontend - Banner exibido no site

Dica: Use resolução 1280x720px ou maior

---

## ⚠️ Problemas Comuns e Soluções

### "Plugin Rejected - Security Issues"

**Causa**: Falha em sanitizar inputs ou escapar outputs

**Solução**:
- Verifique todos os `$_GET`, `$_POST`, `$_REQUEST`
- Use `sanitize_text_field()`, `absint()`, etc.
- Escape outputs com `esc_html()`, `esc_attr()`, `esc_url()`

### "Plugin Rejected - Trademarked Terms"

**Causa**: Uso de nomes de marca (WordPress, WP, etc.)

**Solução**:
- Não use "WordPress" ou "WP" no nome do plugin
- Pode usar na descrição: "Plugin for WordPress"

### "Plugin Rejected - External Resources"

**Causa**: Chamadas a APIs externas sem consentimento

**Solução**:
- Remova chamadas a serviços externos
- Se necessário, peça consentimento explícito do usuário

### "SVN Commit Failed"

**Causa**: Credenciais incorretas ou estrutura de pastas errada

**Solução**:
```bash
# Verificar credenciais
svn info

# Estrutura correta
trunk/
  designmaster-ads.php
  readme.txt
  ... (arquivos do plugin)
tags/
  1.0.0/
  1.1.0/
assets/
  icon-256x256.png
  banner-772x250.png
  screenshot-1.png
```

---

## 📊 Após Publicação

### Monitorar

1. **Estatísticas**: https://wordpress.org/plugins/designmaster-ads/advanced/
2. **Downloads**: Acompanhe crescimento
3. **Ratings**: Responda avaliações
4. **Support Forum**: https://wordpress.org/support/plugin/designmaster-ads/

### Atualizar Plugin

```bash
# 1. Atualizar código no trunk
cd designmaster-ads-svn/trunk
# ... fazer alterações ...

# 2. Atualizar readme.txt com nova versão
# 3. Atualizar CHANGELOG

# 4. Commit no trunk
svn ci -m "Update to version 1.2.0"

# 5. Criar tag da nova versão
svn cp trunk tags/1.2.0
svn ci -m "Tagging version 1.2.0"

# 6. WordPress detecta automaticamente e oferece update aos usuários
```

### Boas Práticas

- ✅ Responda perguntas no fórum em 24-48h
- ✅ Atualize regularmente (compatibilidade com novas versões WP)
- ✅ Mantenha CHANGELOG atualizado
- ✅ Teste em múltiplos ambientes antes de lançar
- ✅ Use versionamento semântico (MAJOR.MINOR.PATCH)
- ✅ Nunca quebre backward compatibility em updates MINOR/PATCH

---

## 🔗 Links Úteis

- **Plugin Review Guidelines**: https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/
- **SVN Guide**: https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/
- **README.txt Validator**: https://wordpress.org/plugins/developers/readme-validator/
- **Plugin Check Tool**: https://wordpress.org/plugins/plugin-check/
- **Developer Handbook**: https://developer.wordpress.org/plugins/

---

## 📞 Contato WordPress.org

- **Email**: plugins@wordpress.org
- **Slack**: https://make.wordpress.org/chat/ (#pluginreview)
- **Forum**: https://wordpress.org/support/forum/plugins-and-hacks/

---

**Boa sorte com a submissão! 🚀**
