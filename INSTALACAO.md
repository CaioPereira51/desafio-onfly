# 🚀 Guia de Instalação Rápida

## Pré-requisitos
- Docker
- Docker Compose
- Git

## Instalação em 3 Passos

### 1. Clone e acesse o projeto
```bash
git clone <url-do-repositorio>
cd desafio
```

### 2. Execute o script de setup

#### No Windows:
```cmd
# Opção 1: Arquivo .bat (recomendado)
setup.bat

# Opção 2: PowerShell
powershell -ExecutionPolicy Bypass -File setup.ps1

# Opção 3: Git Bash (se instalado)
bash setup.sh
```

#### No Linux/Mac:
```bash
chmod +x setup.sh
./setup.sh
```

### 3. Acesse a aplicação
- **Frontend**: http://localhost:8080
- **API**: http://localhost:8000
- **MailHog**: http://localhost:8025

## Usuários de Teste

### Administrador
- **Email**: admin@example.com
- **Senha**: password

### Usuário Comum
- **Email**: user@example.com
- **Senha**: password

## Comandos Úteis

```bash
# Ver logs
docker-compose logs -f

# Parar aplicação
docker-compose down

# Reiniciar aplicação
docker-compose restart

# Executar testes
docker-compose exec api php artisan test
```

## Solução de Problemas

### Erro de permissão no setup.sh
```bash
chmod +x setup.sh
```

### Containers não sobem
```bash
docker-compose down
docker system prune -f
docker-compose up -d --build
```

### Banco não conecta
```bash
docker-compose restart mysql
sleep 30
docker-compose exec api php artisan migrate --seed
```
