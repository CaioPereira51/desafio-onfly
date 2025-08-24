# API - Sistema de Gestão de Pedidos de Viagem Corporativa

## 📋 Descrição

API Laravel para gerenciamento de pedidos de viagem corporativa, com autenticação JWT, controle de permissões e notificações por email.

## 🚀 Funcionalidades

- ✅ Autenticação JWT
- ✅ Controle de permissões (admin/user)
- ✅ CRUD de pedidos de viagem
- ✅ Filtros avançados
- ✅ Notificações por email
- ✅ Rate limiting
- ✅ Cache implementado
- ✅ Logs abrangentes
- ✅ Validações robustas
- ✅ Testes automatizados
- ✅ Documentação completa

## 🔧 Tecnologias

- **Laravel 10.x**
- **PHP 8.1+**
- **MySQL/PostgreSQL**
- **JWT Authentication**
- **Spatie Laravel Permission**
- **MailHog (desenvolvimento)**

## 📦 Instalação

### Pré-requisitos

- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Docker (opcional)

### Passos

1. **Clone o repositório**
```bash
git clone <repository-url>
cd api
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. **Configure o banco de dados**
```bash
# Edite o arquivo .env com suas configurações de banco
php artisan migrate
php artisan db:seed
```

5. **Configure o email (opcional)**
```bash
# Para desenvolvimento, use MailHog
# Para produção, configure SMTP no .env
```

6. **Inicie o servidor**
```bash
php artisan serve
```

## 🔐 Configuração de Segurança

### Variáveis de Ambiente Importantes

```env
# JWT Configuration
JWT_SECRET=your_jwt_secret_here
JWT_TTL=60
JWT_REFRESH_TTL=20160

# User Passwords for Seeding
ADMIN_PASSWORD=Admin@123
USER_PASSWORD=User@123
TEST_USER_PASSWORD=Test@123

# App Configuration
APP_DEBUG=false
APP_ENV=production
```

### Senhas Padrão (Desenvolvimento)

- **Admin**: `admin@example.com` / `Admin@123`
- **Usuário**: `user@example.com` / `User@123`
- **Teste**: `joao@example.com` / `Test@123`

## 📚 Documentação da API

A documentação completa está disponível em: [docs/API.md](docs/API.md)

### Endpoints Principais

- `POST /api/auth/register` - Registro de usuário
- `POST /api/auth/login` - Login
- `GET /api/pedidos` - Listar pedidos
- `POST /api/pedidos` - Criar pedido
- `PATCH /api/pedidos/{id}/status` - Atualizar status (admin)

## 🧪 Testes

### Executar todos os testes
```bash
php artisan test
```

### Executar testes específicos
```bash
# Testes de autenticação
php artisan test --filter=AuthTest

# Testes de pedidos
php artisan test --filter=PedidoTest
```

## 🔄 Correções Implementadas

### ✅ Problemas Críticos Corrigidos

1. **Arquivos de configuração ausentes**
   - Criados: `config/mail.php`, `config/queue.php`, `config/cache.php`, `config/session.php`, `config/filesystems.php`

2. **Senhas hardcoded**
   - Substituídas por variáveis de ambiente
   - Senhas mais seguras por padrão

3. **Chave JWT hardcoded**
   - Removida do exemplo
   - Deve ser gerada individualmente

4. **Debug ativado em produção**
   - Configurado como `false` por padrão

### ✅ Problemas de Média Prioridade Corrigidos

1. **Validação de datas melhorada**
   - `after:today` → `after_or_equal:today`

2. **Cache implementado**
   - Cache para status options
   - Paginação configurável

3. **Logs abrangentes**
   - Logs para criação de pedidos
   - Logs para atualização de status
   - Tratamento de exceções melhorado

4. **Rate limiting personalizado**
   - Middleware customizado criado
   - Headers informativos

### ✅ Problemas de Baixa Prioridade Corrigidos

1. **Documentação completa**
   - Documentação da API criada
   - Exemplos de uso
   - Códigos de status

2. **Testes de integração**
   - Testes de autenticação
   - Cobertura de cenários principais

## 🚀 Deploy

### Produção

1. **Configure o ambiente**
```bash
APP_ENV=production
APP_DEBUG=false
```

2. **Otimize a aplicação**
```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Configure o servidor web**
```bash
# Apache/Nginx config
# SSL certificate
# Database backup
```

### Docker

```bash
docker-compose up -d
```

## 📊 Monitoramento

### Logs

Os logs são salvos em:
- `storage/logs/laravel.log`
- Logs de notificações
- Logs de operações críticas

### Métricas

- Rate limiting headers
- Cache hit/miss
- Response times

## 🔒 Segurança

### Implementado

- ✅ JWT Authentication
- ✅ Rate Limiting
- ✅ Input Validation
- ✅ SQL Injection Protection
- ✅ XSS Protection
- ✅ CSRF Protection
- ✅ Secure Headers

### Recomendações

- Use HTTPS em produção
- Configure firewall
- Monitore logs
- Atualize dependências regularmente

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 📞 Suporte

Para suporte, entre em contato através de:
- Email: suporte@example.com
- Issues: GitHub Issues
- Documentação: [docs/API.md](docs/API.md)
