@echo off
chcp 65001 > nul
echo 🚀 Iniciando setup do Sistema de Gestão de Pedidos de Viagem Corporativa
echo ==================================================================

:: Verificar se o Docker está instalado
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker não está instalado. Por favor, instale o Docker primeiro.
    echo    Visite: https://docs.docker.com/get-docker/
    pause
    exit /b 1
)

:: Verificar se o Docker Compose está instalado
docker-compose --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro.
    echo    Visite: https://docs.docker.com/compose/install/
    pause
    exit /b 1
)

:: Verificar se o Docker está rodando
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker não está rodando. Por favor, inicie o Docker primeiro.
    pause
    exit /b 1
)

echo ✅ Docker e Docker Compose encontrados e rodando

:: Copiar arquivos de ambiente se não existirem
if not exist "api\.env" (
    echo 📝 Copiando arquivo de ambiente da API...
    copy "api\env.example" "api\.env" >nul
    echo ✅ Arquivo .env da API criado
) else (
    echo ✅ Arquivo .env da API já existe
)

if not exist "frontend\.env" (
    echo 📝 Copiando arquivo de ambiente do frontend...
    copy "frontend\env.example" "frontend\.env" >nul
    echo ✅ Arquivo .env do frontend criado
) else (
    echo ✅ Arquivo .env do frontend já existe
)

:: Construir e iniciar os containers
echo 🐳 Construindo e iniciando containers...
docker-compose up -d --build

:: Aguardar o banco de dados inicializar
echo ⏳ Aguardando banco de dados inicializar...
timeout /t 30 /nobreak >nul

:: Executar migrações e seeders
echo 🗄️ Executando migrações e seeders...
docker-compose exec -T api php artisan migrate --seed
if %errorlevel% neq 0 (
    echo ❌ Erro ao executar migrações. Tentando novamente em 10 segundos...
    timeout /t 10 /nobreak >nul
    docker-compose exec -T api php artisan migrate --seed
)

:: Gerar chave da aplicação
echo 🔑 Gerando chave da aplicação...
docker-compose exec -T api php artisan key:generate

:: Configurar permissões (não necessário no Windows)
echo 🔧 Configurando aplicação...

:: Verificar se os serviços estão rodando
echo 🔍 Verificando serviços...
curl -f http://localhost:8000 >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ API rodando em http://localhost:8000
) else (
    echo ⚠️ API pode não estar respondendo ainda
)

curl -f http://localhost:8081 >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Frontend rodando em http://localhost:8081
) else (
    echo ⚠️ Frontend pode não estar respondendo ainda
)

echo.
echo 🎉 Setup concluído com sucesso!
echo.
echo 📋 Informações de acesso:
echo    Frontend: http://localhost:8081
echo    API: http://localhost:8000
echo    MailHog: http://localhost:8025
echo.
echo 👤 Usuários padrão:
echo    Admin: admin@example.com / password
echo    User: user@example.com / password
echo.
echo 📚 Comandos úteis:
echo    Ver logs: docker-compose logs -f
echo    Parar: docker-compose down
echo    Reiniciar: docker-compose restart
echo.
echo 🚀 Acesse http://localhost:8081 para começar!

pause
