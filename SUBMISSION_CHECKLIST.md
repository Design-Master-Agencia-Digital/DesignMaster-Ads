# 📋 Checklist de Submissão WordPress.org

Use este checklist para garantir que tudo está pronto antes de submeter o plugin.

---

## ✅ PRÉ-REQUISITOS TÉCNICOS

### Código e Segurança
- [x] Código 100% GPL v2 ou posterior
- [x] Sem código ofuscado
- [x] Prefix único em todas as funções (`dm_ads_`, `DM_Ads_`)
- [x] Text domain correto (`designmaster-ads`)
- [x] Sanitização de todos os inputs
  - [x] `sanitize_text_field()` para textos
  - [x] `absint()` para IDs
  - [x] `esc_url()` para URLs
- [x] Escaping de todos os outputs
  - [x] `esc_html()` para textos
  - [x] `esc_attr()` para atributos
  - [x] `esc_url()` para links
- [x] Nonces em todos os formulários
  - [x] `wp_nonce_field()` nos forms
  - [x] `wp_verify_nonce()` ao processar
- [x] Prepared statements no banco de dados
  - [x] Uso de `$wpdb->prepare()` em todas as queries
- [x] Sem chamadas a APIs externas não autorizadas
- [x] Sem telemetria sem consentimento

### Estrutura e Organização
- [x] Estrutura de pastas organizada
- [x] Arquivo principal com header correto
- [x] Classes bem nomeadas e documentadas
- [x] Hooks de ativação/desativação
- [x] Uninstall hook ou arquivo uninstall.php

---

## 📄 DOCUMENTAÇÃO

### Arquivos Principais
- [x] **readme.txt** - Formato WordPress.org
  - [x] Metadados corretos (Contributors, Tags, Requires, etc.)
  - [x] Description completa
  - [x] Installation guide
  - [x] FAQ section (mínimo 5 perguntas)
  - [x] Changelog (todas as versões)
  - [x] Screenshots description
  - [x] Upgrade notices
  - [x] Privacy policy

- [x] **README.md** - Para GitHub
  - [x] Visão geral do plugin
  - [x] Features list
  - [x] Installation instructions
  - [x] Usage examples
  - [x] Screenshots

- [x] **CHANGELOG.md**
  - [x] Histórico completo de versões
  - [x] Formato semântico (Added, Changed, Fixed, etc.)

- [x] **LICENSE** ou **license.txt**
  - [x] Licença GPL v2 ou posterior

### Documentação Adicional
- [x] INSTALLATION.md - Guia detalhado de instalação
- [x] TECHNICAL.md - Documentação técnica
- [x] TROUBLESHOOTING.md - Solução de problemas
- [x] WORDPRESS_ORG_SUBMISSION.md - Guia de submissão

---

## 🎨 ASSETS VISUAIS

### Ícones
- [x] icon.svg (criado)
- [ ] icon-128x128.png (128x128px, max 50KB)
- [ ] icon-256x256.png (256x256px, max 50KB)

**Como gerar:**
```bash
cd .wordpress-org
./convert-assets.sh
```

### Banners
- [x] banner-772x250.svg (criado)
- [x] banner-1544x500.svg (criado)
- [ ] banner-772x250.png (772x250px, max 200KB)
- [ ] banner-1544x500.png (1544x500px, max 200KB)

**Como gerar:**
```bash
cd .wordpress-org
./convert-assets.sh
```

### Screenshots
- [ ] screenshot-1.png - Dashboard (1280x720px)
- [ ] screenshot-2.png - Analytics (1280x720px)
- [ ] screenshot-3.png - Zone Manager (1280x720px)
- [ ] screenshot-4.png - Banner Edit (1280x720px)
- [ ] screenshot-5.png - Banner Listing (1280x720px)
- [ ] screenshot-6.png - Frontend Display (1280x720px)

**Como capturar:**
1. Mockups (1-2): Abra `.wordpress-org/screenshot-generator.html` e capture
2. WordPress (3-6): Instale o plugin e capture as telas reais

---

## 🧪 TESTES

### Compatibilidade
- [ ] Testado no WordPress 5.8 (versão mínima)
- [ ] Testado no WordPress 6.4 (versão mais recente)
- [ ] Testado no PHP 7.4 (versão mínima)
- [ ] Testado no PHP 8.0+
- [ ] Testado no MySQL 5.7+

### Funcionalidades
- [ ] Criação de zonas funciona
- [ ] Criação de banners funciona
- [ ] Upload de imagem funciona
- [ ] Shortcode exibe banners
- [ ] Rotação fixed funciona
- [ ] Rotação reload funciona
- [ ] Rotação timed funciona
- [ ] Analytics rastreia views
- [ ] Analytics rastreia clicks
- [ ] Gráficos aparecem corretamente
- [ ] Export CSV funciona
- [ ] Período personalizado funciona
- [ ] Tradução PT-BR funciona

### Themes e Plugins
- [ ] Testado com tema padrão (Twenty Twenty-Four)
- [ ] Testado com tema popular (Astra/GeneratePress)
- [ ] Sem conflitos com plugins populares
- [ ] Funciona com page builders (Elementor/Gutenberg)

### Navegadores
- [ ] Chrome/Edge (últimas versões)
- [ ] Firefox (última versão)
- [ ] Safari (última versão)

---

## 🔍 VALIDAÇÃO

### Plugin Check
- [ ] Instalar Plugin Check: https://wordpress.org/plugins/plugin-check/
- [ ] Executar verificação completa
- [ ] Resolver todos os erros críticos
- [ ] Resolver avisos de segurança

### README Validator
- [ ] Validar readme.txt: https://wordpress.org/plugins/developers/readme-validator/
- [ ] Corrigir todos os erros
- [ ] Verificar formatação de tags

### Code Review
- [ ] Revisar todo o código
- [ ] Remover código de debug
- [ ] Remover console.log()
- [ ] Verificar comentários adequados
- [ ] PHPDoc em todas as funções públicas

---

## 📦 PREPARAÇÃO FINAL

### Limpeza
- [ ] Remover arquivos desnecessários
  - [ ] .git (se enviar ZIP)
  - [ ] .DS_Store
  - [ ] node_modules
  - [ ] *.log
  - [ ] .env
  - [ ] Arquivos de teste

### Versionamento
- [x] Versão atualizada no arquivo principal (1.1.0)
- [x] Versão no readme.txt (Stable tag: 1.1.0)
- [x] Changelog atualizado
- [x] Commit no Git
- [ ] Tag de versão no Git

```bash
git tag -a v1.1.0 -m "Release version 1.1.0"
git push origin v1.1.0
```

### Arquivo ZIP
- [ ] Criar arquivo .zip do plugin
- [ ] Testar instalação do .zip em WordPress limpo
- [ ] Verificar que todos os assets estão incluídos
- [ ] Verificar tamanho total (< 5MB recomendado)

```bash
cd /Users/alandebortolo/Wordpress/Plugins
zip -r designmaster-ads-1.1.0.zip "DesignMaster Ads" -x "*.git*" "*.DS_Store" "*node_modules*"
```

---

## 🚀 SUBMISSÃO

### Conta WordPress.org
- [ ] Criar conta: https://login.wordpress.org/register
- [ ] Confirmar email
- [ ] Login verificado

### Formulário de Submissão
- [ ] Acessar: https://wordpress.org/plugins/developers/add/
- [ ] Preencher formulário:
  - [ ] Plugin Name: DesignMaster Ads
  - [ ] Plugin URL: (GitHub ou repositório)
  - [ ] Description: Breve descrição
  - [ ] Upload ZIP do plugin
- [ ] Aceitar todos os termos
- [ ] Ler diretrizes de plugins
- [ ] Confirmar GPL compatibility
- [ ] Confirmar sem código ofuscado
- [ ] Confirmar sem rastreamento não autorizado
- [ ] Submeter plugin

### Aguardar Revisão
- [ ] Email de confirmação recebido
- [ ] Aguardar 7-14 dias
- [ ] Monitorar email para feedback
- [ ] Responder rapidamente se houver questões

---

## 📥 APÓS APROVAÇÃO

### Configurar SVN
- [ ] Receber email com URL do SVN
- [ ] Fazer checkout do repositório

```bash
svn co https://plugins.svn.wordpress.org/designmaster-ads designmaster-ads-svn
cd designmaster-ads-svn
```

### Estrutura SVN
- [ ] Criar estrutura de pastas:
  - [ ] trunk/ - Versão de desenvolvimento
  - [ ] tags/ - Versões lançadas
  - [ ] assets/ - Ícones, banners, screenshots

### Upload Inicial
- [ ] Copiar arquivos do plugin para trunk/
- [ ] Copiar assets PNG para assets/
- [ ] Adicionar ao SVN

```bash
# Copiar plugin
cp -r /Users/alandebortolo/Wordpress/Plugins/DesignMaster\ Ads/* trunk/

# Copiar assets
mkdir assets
cp /Users/alandebortolo/Wordpress/Plugins/DesignMaster\ Ads/.wordpress-org/*.png assets/

# Adicionar
svn add trunk/*
svn add assets/*

# Commit inicial
svn ci -m "Initial commit of DesignMaster Ads v1.1.0"

# Criar tag da versão
svn cp trunk tags/1.1.0
svn ci -m "Tagging version 1.1.0"
```

### Verificar Publicação
- [ ] Plugin aparece em: https://wordpress.org/plugins/designmaster-ads/
- [ ] Ícones e banners aparecem corretamente
- [ ] Screenshots funcionam
- [ ] Download funciona
- [ ] Instalação via admin funciona

---

## 📊 PÓS-PUBLICAÇÃO

### Monitoramento
- [ ] Configurar notificações do fórum de suporte
- [ ] Verificar estatísticas diárias
- [ ] Monitorar avaliações
- [ ] Responder perguntas em 24-48h

### Marketing
- [ ] Anunciar no seu site/blog
- [ ] Compartilhar nas redes sociais
- [ ] Criar post no WPTavern (opcional)
- [ ] Adicionar badge WordPress.org no README

### Manutenção
- [ ] Planejar próxima versão
- [ ] Manter changelog atualizado
- [ ] Testar com novas versões do WordPress
- [ ] Responder issues no GitHub
- [ ] Atualizar documentação conforme necessário

---

## ⚠️ PROBLEMAS COMUNS

### Plugin Rejeitado
- **Security Issues**: Revisar sanitização/escaping
- **GPL Violation**: Garantir que todo código é GPL
- **Trademarked Terms**: Não usar "WordPress" no nome
- **External Calls**: Remover APIs externas ou pedir consentimento

### SVN Commit Failed
- Verificar credenciais: `svn info`
- Verificar estrutura de pastas
- Tentar: `svn cleanup` e depois `svn ci` novamente

### Assets não aparecem
- Verificar nomes dos arquivos (case-sensitive)
- Aguardar 15 minutos após commit
- Limpar cache do navegador
- Verificar tamanho dos arquivos (dentro dos limites)

---

## 📞 SUPORTE

- **Plugin Review Team**: plugins@wordpress.org
- **Fórum de Desenvolvedores**: https://wordpress.org/support/forum/plugins-and-hacks/
- **Slack**: https://make.wordpress.org/chat/ (#pluginreview)
- **Documentação**: https://developer.wordpress.org/plugins/

---

**Data de criação:** 12 de novembro de 2025  
**Versão do plugin:** 1.1.0  
**Status:** ⏳ Preparação em andamento

---

## 🎯 PROGRESSO ATUAL

```
████████████████░░░░ 80% completo

✅ Código pronto
✅ Documentação completa
✅ SVGs criados
✅ Script de conversão pronto
⏳ Converter SVG → PNG
⏳ Capturar screenshots
⏳ Testes finais
⏳ Submissão
```

**Próximo passo:** Executar `./convert-assets.sh` e capturar screenshots
