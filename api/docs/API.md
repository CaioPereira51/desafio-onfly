# Documentação da API - Sistema de Gestão de Pedidos de Viagem

## Visão Geral

Esta API fornece endpoints para gerenciamento de pedidos de viagem corporativa, incluindo autenticação, criação, listagem e atualização de status de pedidos.

## Base URL

```
http://localhost:8000/api
```

## Autenticação

A API utiliza JWT (JSON Web Tokens) para autenticação. Inclua o token no header das requisições:

```
Authorization: Bearer {seu_token}
```

## Endpoints

### Autenticação

#### POST /auth/register
Registra um novo usuário.

**Parâmetros:**
```json
{
    "name": "Nome do Usuário",
    "email": "usuario@example.com",
    "password": "senha123",
    "password_confirmation": "senha123"
}
```

**Resposta (201):**
```json
{
    "success": true,
    "message": "Usuário registrado com sucesso",
    "user": {
        "id": 1,
        "name": "Nome do Usuário",
        "email": "usuario@example.com"
    },
    "authorization": {
        "token": "jwt_token_aqui",
        "type": "bearer"
    }
}
```

#### POST /auth/login
Realiza login do usuário.

**Parâmetros:**
```json
{
    "email": "usuario@example.com",
    "password": "senha123"
}
```

**Resposta (200):**
```json
{
    "success": true,
    "message": "Login realizado com sucesso",
    "user": {
        "id": 1,
        "name": "Nome do Usuário",
        "email": "usuario@example.com"
    },
    "authorization": {
        "token": "jwt_token_aqui",
        "type": "bearer"
    }
}
```

#### POST /auth/logout
Realiza logout do usuário.

**Headers:** `Authorization: Bearer {token}`

**Resposta (200):**
```json
{
    "success": true,
    "message": "Logout realizado com sucesso"
}
```

#### GET /auth/me
Obtém informações do usuário autenticado.

**Headers:** `Authorization: Bearer {token}`

**Resposta (200):**
```json
{
    "success": true,
    "user": {
        "id": 1,
        "name": "Nome do Usuário",
        "email": "usuario@example.com",
        "roles": []
    }
}
```

#### POST /auth/refresh
Renova o token JWT.

**Headers:** `Authorization: Bearer {token}`

**Resposta (200):**
```json
{
    "success": true,
    "authorization": {
        "token": "novo_jwt_token",
        "type": "bearer"
    }
}
```

### Pedidos

#### GET /pedidos
Lista os pedidos do usuário (ou todos se for admin).

**Headers:** `Authorization: Bearer {token}`

**Parâmetros de Query:**
- `status` (opcional): Filtrar por status (solicitado, aprovado, cancelado)
- `destino` (opcional): Filtrar por destino
- `data_inicio` e `data_fim` (opcional): Filtrar por período da viagem
- `criacao_inicio` e `criacao_fim` (opcional): Filtrar por período de criação
- `per_page` (opcional): Itens por página (padrão: 15)

**Resposta (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "solicitante_id": 1,
                "nome_solicitante": "Nome do Usuário",
                "destino": "São Paulo, SP",
                "data_ida": "2024-06-01",
                "data_volta": "2024-06-05",
                "status": "solicitado",
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z",
                "solicitante": {
                    "id": 1,
                    "name": "Nome do Usuário",
                    "email": "usuario@example.com"
                }
            }
        ],
        "per_page": 15,
        "total": 1
    }
}
```

#### POST /pedidos
Cria um novo pedido de viagem.

**Headers:** `Authorization: Bearer {token}`

**Parâmetros:**
```json
{
    "destino": "São Paulo, SP",
    "data_ida": "2024-06-01",
    "data_volta": "2024-06-05"
}
```

**Resposta (201):**
```json
{
    "success": true,
    "message": "Pedido criado com sucesso",
    "data": {
        "id": 1,
        "solicitante_id": 1,
        "nome_solicitante": "Nome do Usuário",
        "destino": "São Paulo, SP",
        "data_ida": "2024-06-01",
        "data_volta": "2024-06-05",
        "status": "solicitado",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

#### GET /pedidos/{id}
Obtém detalhes de um pedido específico.

**Headers:** `Authorization: Bearer {token}`

**Resposta (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "solicitante_id": 1,
        "nome_solicitante": "Nome do Usuário",
        "destino": "São Paulo, SP",
        "data_ida": "2024-06-01",
        "data_volta": "2024-06-05",
        "status": "solicitado",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "solicitante": {
            "id": 1,
            "name": "Nome do Usuário",
            "email": "usuario@example.com"
        }
    }
}
```

#### PATCH /pedidos/{id}/status
Atualiza o status de um pedido (apenas admin).

**Headers:** `Authorization: Bearer {token}`

**Parâmetros:**
```json
{
    "status": "aprovado"
}
```

**Resposta (200):**
```json
{
    "success": true,
    "message": "Status do pedido atualizado com sucesso",
    "data": {
        "id": 1,
        "status": "aprovado",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

#### GET /pedidos/status/options
Obtém as opções de status disponíveis.

**Headers:** `Authorization: Bearer {token}`

**Resposta (200):**
```json
{
    "success": true,
    "data": {
        "solicitado": "Solicitado",
        "aprovado": "Aprovado",
        "cancelado": "Cancelado"
    }
}
```

## Códigos de Status

- `200` - Sucesso
- `201` - Criado com sucesso
- `401` - Não autorizado
- `403` - Acesso negado
- `404` - Não encontrado
- `422` - Erro de validação
- `429` - Muitas tentativas
- `500` - Erro interno do servidor

## Rate Limiting

A API implementa rate limiting para proteger contra abuso:
- 60 requisições por minuto por usuário/IP
- Headers de resposta incluem informações sobre limites

## Validações

### Pedidos
- `destino`: Obrigatório, máximo 255 caracteres
- `data_ida`: Obrigatório, data válida, deve ser hoje ou posterior
- `data_volta`: Obrigatório, data válida, deve ser posterior à data de ida

### Usuários
- `name`: Obrigatório, máximo 255 caracteres
- `email`: Obrigatório, email válido, único
- `password`: Obrigatório, mínimo 6 caracteres, confirmação obrigatória

## Permissões

- **Usuários comuns**: Podem criar e visualizar apenas seus próprios pedidos
- **Administradores**: Podem visualizar todos os pedidos e atualizar status

## Notificações

Quando o status de um pedido é atualizado, um email é enviado automaticamente para o solicitante com os detalhes da mudança.
