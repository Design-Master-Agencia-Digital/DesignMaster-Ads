# Configuração do Git para DesignMaster Ads

## Para adicionar seu repositório privado:

1. Crie um repositório privado no GitHub/GitLab/Bitbucket

2. Configure o remote:
```bash
cd "/Users/alandebortolo/Wordpress/Plugins/DesignMaster Ads"
git remote add origin SEU_URL_DO_REPOSITORIO
```

3. Push inicial:
```bash
git branch -M main
git push -u origin main
```

## Exemplo com GitHub:
```bash
# Substitua 'seuusuario' e 'designmaster-ads' pelos seus valores
git remote add origin https://github.com/seuusuario/designmaster-ads.git
git branch -M main
git push -u origin main
```

## Exemplo com GitLab:
```bash
git remote add origin https://gitlab.com/seuusuario/designmaster-ads.git
git branch -M main
git push -u origin main
```

## Comandos úteis:

### Ver status
```bash
git status
```

### Adicionar alterações
```bash
git add .
```

### Commitar
```bash
git commit -m "Sua mensagem de commit"
```

### Enviar para o repositório remoto
```bash
git push
```

### Puxar do repositório remoto
```bash
git pull
```

### Ver histórico
```bash
git log --oneline
```

### Criar nova branch
```bash
git checkout -b nome-da-branch
```

### Trocar de branch
```bash
git checkout nome-da-branch
```
