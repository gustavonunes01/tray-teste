# Documentação da API TRAY

## Autenticação

Todas as rotas da API (exceto login) requerem autenticação via token JWT.

### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "super@admin.teste",
  "password": "superadmin123"
}
```

**Resposta:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

## Perfil do Usuário

### Obter Perfil
```http
GET /api/me
Authorization: Bearer {token}
```

**Resposta:**
```json
{
  "id": 1,
  "name": "superadmin",
  "email": "super@admin.teste",
  "email_verified_at": "2025-05-20T02:52:02.000000Z",
  "profile_id": 1,
  "created_at": "2025-05-20T02:52:03.000000Z",
  "updated_at": "2025-05-20T02:52:03.000000Z",
  "profile": {
    "id": 1,
    "name": "Admin",
    "created_at": "2025-05-20T02:50:50.000000Z",
    "updated_at": "2025-05-20T02:50:50.000000Z"
  }
}
```

## Vendas

### Listar Vendas
```http
GET /api/v1/sales
Authorization: Bearer {token}
```

**Parâmetros de Query:**
- `page`: Número da página (padrão: 1)
- `per_page`: Itens por página (padrão: 10)

**Resposta:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "external_id": "30cc8fa7-3662-4869-8de8-cb7aaae652bd",
      "name": "Venda teste 123",
      "price": "325.77000",
      "commission_value": "27.69000",
      "seller_id": 1,
      "created_at": "2025-05-21T00:15:44.000000Z",
      "updated_at": "2025-05-21T00:15:44.000000Z",
      "deleted_at": null
    }
  ],
  "first_page_url": "http://localhost:8001/api/v1/sales?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://localhost:8001/api/v1/sales?page=1",
  "links": [...],
  "next_page_url": null,
  "path": "http://localhost:8001/api/v1/sales",
  "per_page": 10,
  "prev_page_url": null,
  "to": 4,
  "total": 4
}
```

### Criar Venda
```http
POST /api/v1/sales
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Venda boa",
  "price": 625.77,
  "seller_id": 1
}
```

**Resposta:**
```json
{
  "message": "Venda criada com sucesso!",
  "sale": {
    "name": "Venda boa",
    "price": "625.77000",
    "commission_value": "53.19045",
    "seller_id": 1,
    "external_id": "d9106aaa-cac7-46f6-a9c1-70da14e3d568",
    "updated_at": "2025-05-21T00:19:48.000000Z",
    "created_at": "2025-05-21T00:19:48.000000Z",
    "id": 5
  }
}
```

### Obter Venda por ID Externo
```http
GET /api/v1/sales/{external_id}
Authorization: Bearer {token}
```

**Resposta:**
```json
{
  "data": {
    "id": 1,
    "external_id": "30cc8fa7-3662-4869-8de8-cb7aaae652bd",
    "name": "Venda teste 123",
    "price": "325.77000",
    "commission_value": "27.69000",
    "seller_id": 1,
    "created_at": "2025-05-21T00:15:44.000000Z",
    "updated_at": "2025-05-21T00:15:44.000000Z",
    "deleted_at": null
  }
}
```

## Vendedores

### Listar Vendedores
```http
GET /api/v1/sellers
Authorization: Bearer {token}
```

**Resposta:**
```json
{
  "current_page": 1,
  "data": [],
  "first_page_url": "http://localhost:8001/api/v1/sellers?page=1",
  "from": null,
  "last_page": 1,
  "last_page_url": "http://localhost:8001/api/v1/sellers?page=1",
  "links": [...],
  "next_page_url": null,
  "path": "http://localhost:8001/api/v1/sellers",
  "per_page": 50,
  "prev_page_url": null,
  "to": null,
  "total": 0
}
```

### Listar Vendas do Vendedor
```http
GET /api/v1/sellers/my-sales
Authorization: Bearer {token}
```

**Resposta:**
```json
[
  {
    "id": 1,
    "external_id": "30cc8fa7-3662-4869-8de8-cb7aaae652bd",
    "name": "Venda teste 123",
    "price": "325.77000",
    "commission_value": "27.69000",
    "seller_id": 1,
    "created_at": "2025-05-21T00:15:44.000000Z",
    "updated_at": "2025-05-21T00:15:44.000000Z",
    "deleted_at": null
  }
]
```

### Enviar Notificação por Email
```http
GET /api/v1/sellers/email/notify/{seller_id}
Authorization: Bearer {token}
```

**Resposta:**
```json
{
  "message": "Notificação encaminhada para o vendedor com sucesso"
}
```

## Notas Importantes

1. Todas as rotas (exceto login) requerem o header `Authorization: Bearer {token}`
2. O token expira em 1 hora (3600 segundos)
3. As vendas incluem cálculo automático de comissão
4. As notificações por email são processadas em fila
5. Todas as datas são retornadas no formato ISO 8601
6. Valores monetários são retornados com 5 casas decimais 