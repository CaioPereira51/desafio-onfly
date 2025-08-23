#!/bin/bash

set -e  # Exit on any error

echo "🚀 Iniciando setup do Sistema de Gestão de Pedidos de Viagem Corporativa"
echo "=================================================================="

# Verificar se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    echo "   Visite: https://docs.docker.com/get-docker/"
    exit 1
fi

# Verificar se o Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    echo "   Visite: https://docs.docker.com/compose/install/"
    exit 1
fi

# Verificar se o Docker está rodando
if ! docker info &> /dev/null; then
    echo "❌ Docker não está rodando. Por favor, inicie o Docker primeiro."
    exit 1
fi

echo "✅ Docker e Docker Compose encontrados e rodando"

# Copiar arquivos de ambiente se não existirem
if [ ! -f "api/.env" ]; then
    echo "📝 Copiando arquivo de ambiente da API..."
    cp api/env.example api/.env
    echo "✅ Arquivo .env da API criado"
else
    echo "✅ Arquivo .env da API já existe"
fi

if [ ! -f "frontend/.env" ]; then
    echo "📝 Copiando arquivo de ambiente do frontend..."
    cp frontend/env.example frontend/.env
    echo "✅ Arquivo .env do frontend criado"
else
    echo "✅ Arquivo .env do frontend já existe"
fi

# Construir e iniciar os containers
echo "🐳 Construindo e iniciando containers..."
docker-compose up -d --build

# Aguardar o banco de dados inicializar
echo "⏳ Aguardando banco de dados inicializar..."
sleep 30

# Executar migrações e seeders
echo "🗄️ Executando migrações e seeders..."
if docker-compose exec -T api php artisan migrate --seed; then
    echo "✅ Migrações e seeders executados com sucesso"
else
    echo "❌ Erro ao executar migrações. Tentando novamente em 10 segundos..."
    sleep 10
    docker-compose exec -T api php artisan migrate --seed
fi

# Gerar chave da aplicação
echo "🔑 Gerando chave da aplicação..."
docker-compose exec -T api php artisan key:generate

# Configurar permissões
echo "🔧 Configurando permissões..."
docker-compose exec -T api chmod -R 777 storage bootstrap/cache

# Verificar se os serviços estão rodando
echo "🔍 Verificando serviços..."
if curl -f http://localhost:8000 &> /dev/null; then
    echo "✅ API rodando em http://localhost:8000"
else
    echo "⚠️ API pode não estar respondendo ainda"
fi

if curl -f http://localhost:8080 &> /dev/null; then
    echo "✅ Frontend rodando em http://localhost:8080"
else
    echo "⚠️ Frontend pode não estar respondendo ainda"
fi

echo ""
echo "🎉 Setup concluído com sucesso!"
echo ""
echo "📋 Informações de acesso:"
echo "   Frontend: http://localhost:8080"
echo "   API: http://localhost:8000"
echo "   MailHog: http://localhost:8025"
echo ""
echo "👤 Usuários padrão:"
echo "   Admin: admin@example.com / password"
echo "   User: user@example.com / password"
echo ""
echo "📚 Comandos úteis:"
echo "   Ver logs: docker-compose logs -f"
echo "   Parar: docker-compose down"
echo "   Reiniciar: docker-compose restart"
echo ""
echo "🚀 Acesse http://localhost:8080 para começar!"
