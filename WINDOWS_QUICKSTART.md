# 🚀 Guia Rápido - Windows

## ⚡ Instalação Super Rápida

### 1. Instale o Docker Desktop
- Baixe: [Docker Desktop para Windows](https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe)
- Execute o instalador
- Reinicie quando solicitado
- Inicie o Docker Desktop

### 2. Clone o projeto
```cmd
git clone <url-do-repositorio>
cd desafio
```

### 3. Execute o setup
```cmd
setup.bat
```

### 4. Acesse a aplicação
- **Frontend**: http://localhost:8080
- **API**: http://localhost:8000

## 👤 Login de Teste
- **Admin**: admin@example.com / password
- **User**: user@example.com / password

## ❌ Problemas Comuns

### "Docker não está instalado"
1. Instale o Docker Desktop
2. Certifique-se de que está rodando (ícone na bandeja)
3. Execute novamente: `setup.bat`

### "WSL 2 installation is incomplete"
1. Abra PowerShell como Admin
2. Execute: `wsl --install`
3. Reinicie o computador
4. Execute novamente: `setup.bat`

### Script não executa
```cmd
# Tente estas alternativas:

# Opção 1: PowerShell
powershell -ExecutionPolicy Bypass -File setup.ps1

# Opção 2: Git Bash (se instalado)
bash setup.sh

# Opção 3: Manual
docker-compose up -d --build
timeout /t 30
docker-compose exec api php artisan migrate --seed
```

### Containers não sobem
```cmd
docker-compose down
docker system prune -f
docker-compose up -d --build
```

## 📱 Comandos Úteis Windows

```cmd
# Ver logs
docker-compose logs -f

# Parar aplicação
docker-compose down

# Reiniciar
docker-compose restart

# Status dos containers
docker-compose ps

# Acessar container da API
docker-compose exec api bash

# Executar testes
docker-compose exec api php artisan test
```

## 🆘 Precisa de Ajuda?

1. **Docker não funciona**: Veja `INSTALACAO_DOCKER_WINDOWS.md`
2. **Guia completo**: Veja `README.md`
3. **Instalação detalhada**: Veja `INSTALACAO.md`

## ✅ Tudo Funcionando?

Se você conseguiu acessar http://localhost:8080 e fazer login, parabéns! 🎉

O sistema está rodando perfeitamente!
