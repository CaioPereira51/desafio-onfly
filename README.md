# Sistema de Gestão de Pedidos de Viagem Corporativa

Aplicação Full Stack para gestão de pedidos de viagem corporativa desenvolvida com Laravel (API REST), Vue.js (Front-end) e MySQL.

## 🚀 Tecnologias Utilizadas

- **Back-end**: Laravel 10 (API REST com autenticação JWT)
- **Front-end**: Vue.js 3 (com Composition API)
- **Banco de Dados**: MySQL 8.0
- **Containerização**: Docker & Docker Compose
- **Autenticação**: JWT (JSON Web Tokens)

## 📋 Funcionalidades

### Para Usuários Comuns
- Cadastro e login de usuários
- Criar pedidos de viagem
- Visualizar lista de pedidos próprios com filtros
- Consultar detalhes de pedidos
- Receber notificações de aprovação/cancelamento

### Para Administradores
- Todas as funcionalidades de usuários comuns
- Aprovar/cancelar pedidos de outros usuários
- Visualizar todos os pedidos do sistema

## 🛠️ Instalação e Execução

### Pré-requisitos
- Docker
- Docker Compose
- Git

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd desafio
```

### 2. Configure as variáveis de ambiente
```bash
# Copie o arquivo de exemplo
cp .env.example .env
```

### 3. Execute o setup automatizado

#### Windows:
```cmd
# Execute o script de setup
setup.bat
```

#### Linux/Mac:
```bash
# Torne o script executável e execute
chmod +x setup.sh
./setup.sh
```

#### Instalação manual (qualquer SO):
```bash
# Construa e inicie os containers
docker-compose up -d --build

# Aguarde alguns segundos para o banco inicializar
sleep 30

# Execute as migrações e seeders
docker-compose exec api php artisan migrate --seed
```

### 4. Acesse a aplicação
- **Front-end**: http://localhost:8080
- **API**: http://localhost:8000
- **Banco de Dados**: localhost:3306

## 🔧 Configuração de Ambiente

## 🧪 Executando Testes

### Back-end (Laravel)
```bash
# Execute todos os testes
docker-compose exec api php artisan test

# Execute testes específicos
docker-compose exec api php artisan test --filter PedidoTest
```

### Front-end (Vue.js)
```bash
# Execute testes unitários
docker-compose exec frontend npm run test:unit

# Execute testes e2e
docker-compose exec frontend npm run test:e2e
```

## 📁 Estrutura do Projeto

```
desafio/
├── api/                    # Back-end Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Middleware/
│   │   ├── Models/
│   │   └── Services/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── tests/
│   └── routes/
├── frontend/              # Front-end Vue.js
│   ├── src/
│   │   ├── components/
│   │   ├── views/
│   │   ├── router/
│   │   ├── store/
│   │   └── services/
│   └── public/
├── docker-compose.yml
└── README.md
```

## 🔐 Usuários Padrão

Após executar os seeders, os seguintes usuários estarão disponíveis:

### Usuário Comum
- **Email**: user@example.com
- **Senha**: password

### Administrador
- **Email**: admin@example.com
- **Senha**: password

## 📊 Status dos Pedidos

- **Solicitado**: Pedido criado, aguardando aprovação
- **Aprovado**: Pedido aprovado por administrador
- **Cancelado**: Pedido cancelado (apenas se não estiver aprovado)

## 🔄 Comandos Úteis

```bash
# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f api
docker-compose logs -f frontend

# Acessar container da API
docker-compose exec api bash

# Acessar container do frontend
docker-compose exec frontend bash

# Limpar cache do Laravel
docker-compose exec api php artisan cache:clear
docker-compose exec api php artisan config:clear
```

## 🐛 Solução de Problemas

### Problema: Erro de conexão com banco
```bash
# Verifique se o container MySQL está rodando
docker-compose ps

# Reinicie os containers
docker-compose down
docker-compose up -d
```

### Problema: Erro de permissões
```bash
# Ajuste permissões no container da API
docker-compose exec api chmod -R 777 storage bootstrap/cache
```

## 📝 API Endpoints

### Autenticação
- `POST /api/auth/register` - Cadastro de usuário
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout

### Pedidos
- `GET /api/pedidos` - Listar pedidos (com filtros)
- `POST /api/pedidos` - Criar pedido
- `GET /api/pedidos/{id}` - Consultar pedido
- `PATCH /api/pedidos/{id}/status` - Atualizar status (admin)

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.
