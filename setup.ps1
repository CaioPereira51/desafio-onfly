# Sistema de Gestão de Pedidos de Viagem Corporativa - Setup PowerShell
# Encoding: UTF-8

Write-Host "🚀 Iniciando setup do Sistema de Gestão de Pedidos de Viagem Corporativa" -ForegroundColor Cyan
Write-Host "==================================================================" -ForegroundColor Cyan

# Verificar se o Docker está instalado
try {
    $dockerVersion = docker --version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Docker encontrado: $dockerVersion" -ForegroundColor Green
    } else {
        throw "Docker não encontrado"
    }
} catch {
    Write-Host "❌ Docker não está instalado. Por favor, instale o Docker primeiro." -ForegroundColor Red
    Write-Host "   Visite: https://docs.docker.com/get-docker/" -ForegroundColor Yellow
    Read-Host "Pressione Enter para sair"
    exit 1
}

# Verificar se o Docker Compose está instalado
try {
    $composeVersion = docker-compose --version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Docker Compose encontrado: $composeVersion" -ForegroundColor Green
    } else {
        throw "Docker Compose não encontrado"
    }
} catch {
    Write-Host "❌ Docker Compose não está instalado." -ForegroundColor Red
    Write-Host "   Visite: https://docs.docker.com/compose/install/" -ForegroundColor Yellow
    Read-Host "Pressione Enter para sair"
    exit 1
}

# Verificar se o Docker está rodando
try {
    docker info 2>$null | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Docker está rodando" -ForegroundColor Green
    } else {
        throw "Docker não está rodando"
    }
} catch {
    Write-Host "❌ Docker não está rodando. Por favor, inicie o Docker primeiro." -ForegroundColor Red
    Read-Host "Pressione Enter para sair"
    exit 1
}

# Copiar arquivos de ambiente se não existirem
if (!(Test-Path "api\.env")) {
    Write-Host "📝 Copiando arquivo de ambiente da API..." -ForegroundColor Yellow
    Copy-Item "api\env.example" "api\.env"
    Write-Host "✅ Arquivo .env da API criado" -ForegroundColor Green
} else {
    Write-Host "✅ Arquivo .env da API já existe" -ForegroundColor Green
}

if (!(Test-Path "frontend\.env")) {
    Write-Host "📝 Copiando arquivo de ambiente do frontend..." -ForegroundColor Yellow
    Copy-Item "frontend\env.example" "frontend\.env"
    Write-Host "✅ Arquivo .env do frontend criado" -ForegroundColor Green
} else {
    Write-Host "✅ Arquivo .env do frontend já existe" -ForegroundColor Green
}

# Construir e iniciar os containers
Write-Host "🐳 Construindo e iniciando containers..." -ForegroundColor Cyan
docker-compose up -d --build

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Containers iniciados com sucesso" -ForegroundColor Green
} else {
    Write-Host "❌ Erro ao iniciar containers" -ForegroundColor Red
    Read-Host "Pressione Enter para sair"
    exit 1
}

# Aguardar o banco de dados inicializar
Write-Host "⏳ Aguardando banco de dados inicializar..." -ForegroundColor Yellow
Start-Sleep -Seconds 30

# Executar migrações e seeders
Write-Host "🗄️ Executando migrações e seeders..." -ForegroundColor Cyan
docker-compose exec -T api php artisan migrate --seed

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erro ao executar migrações. Tentando novamente em 10 segundos..." -ForegroundColor Red
    Start-Sleep -Seconds 10
    docker-compose exec -T api php artisan migrate --seed
}

# Gerar chave da aplicação
Write-Host "🔑 Gerando chave da aplicação..." -ForegroundColor Cyan
docker-compose exec -T api php artisan key:generate

# Verificar se os serviços estão rodando
Write-Host "🔍 Verificando serviços..." -ForegroundColor Cyan

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000" -TimeoutSec 5 -ErrorAction Stop
    Write-Host "✅ API rodando em http://localhost:8000" -ForegroundColor Green
} catch {
    Write-Host "⚠️ API pode não estar respondendo ainda" -ForegroundColor Yellow
}

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8080" -TimeoutSec 5 -ErrorAction Stop
    Write-Host "✅ Frontend rodando em http://localhost:8080" -ForegroundColor Green
} catch {
    Write-Host "⚠️ Frontend pode não estar respondendo ainda" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "🎉 Setup concluído com sucesso!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Informações de acesso:" -ForegroundColor Cyan
Write-Host "   Frontend: http://localhost:8080" -ForegroundColor White
Write-Host "   API: http://localhost:8000" -ForegroundColor White
Write-Host "   MailHog: http://localhost:8025" -ForegroundColor White
Write-Host ""
Write-Host "👤 Usuários padrão:" -ForegroundColor Cyan
Write-Host "   Admin: admin@example.com / password" -ForegroundColor White
Write-Host "   User: user@example.com / password" -ForegroundColor White
Write-Host ""
Write-Host "📚 Comandos úteis:" -ForegroundColor Cyan
Write-Host "   Ver logs: docker-compose logs -f" -ForegroundColor White
Write-Host "   Parar: docker-compose down" -ForegroundColor White
Write-Host "   Reiniciar: docker-compose restart" -ForegroundColor White
Write-Host ""
Write-Host "🚀 Acesse http://localhost:8080 para começar!" -ForegroundColor Green

Read-Host "Pressione Enter para continuar"
