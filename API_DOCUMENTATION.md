# Documentação da API - Sistema de Tarefas Multiempresa

## Base URL
```
http://localhost:8000/api
```

## Autenticação
A API utiliza JWT (JSON Web Tokens) para autenticação. Todos os endpoints protegidos requerem o header:
```
Authorization: Bearer {token}
```

## Endpoints

### 🔐 Autenticação

#### POST /register
Registra um novo usuário e empresa.

**Body:**
```json
{
    "name": "João Silva",
    "email": "joao@empresa.com",
    "password": "senha123",
    "password_confirmation": "senha123",
    "empresa_nome": "Minha Empresa Ltda",
    "empresa_identificador": "EMP001"
}
```

**Response (201):**
```json
{
    "message": "Usuário registrado com sucesso",
    "user": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@empresa.com",
        "empresa": {
            "id": 1,
            "nome": "Minha Empresa Ltda",
            "identificador": "EMP001"
        }
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

#### POST /login
Faz login do usuário.

**Body:**
```json
{
    "email": "joao@empresa.com",
    "password": "senha123"
}
```

**Response (200):**
```json
{
    "message": "Login realizado com sucesso",
    "user": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@empresa.com",
        "empresa": {
            "id": 1,
            "nome": "Minha Empresa Ltda",
            "identificador": "EMP001"
        }
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

#### POST /logout
Faz logout do usuário (requer autenticação).

**Response (200):**
```json
{
    "message": "Logout realizado com sucesso"
}
```

#### GET /user
Obtém dados do usuário autenticado (requer autenticação).

**Response (200):**
```json
{
    "user": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@empresa.com",
        "empresa": {
            "id": 1,
            "nome": "Minha Empresa Ltda",
            "identificador": "EMP001"
        }
    }
}
```

### 📋 Tarefas

#### GET /tarefas
Lista todas as tarefas do usuário autenticado (requer autenticação).

**Response (200):**
```json
[
    {
        "id": 1,
        "titulo": "Implementar autenticação JWT",
        "descricao": "Configurar sistema de autenticação JWT para a API",
        "status": "concluida",
        "prioridade": "alta",
        "data_limite": "2024-01-15",
        "empresa_id": 1,
        "user_id": 1,
        "created_at": "2024-01-10T10:00:00.000000Z",
        "updated_at": "2024-01-10T10:00:00.000000Z",
        "user": {
            "id": 1,
            "name": "João Silva",
            "email": "joao@empresa.com"
        }
    }
]
```

#### POST /tarefas
Cria uma nova tarefa (requer autenticação).

**Body:**
```json
{
    "titulo": "Nova tarefa",
    "descricao": "Descrição da tarefa",
    "status": "pendente",
    "prioridade": "media",
    "data_limite": "2024-01-20"
}
```

**Campos obrigatórios:**
- `titulo` (string, máximo 255 caracteres)
- `status` (enum: pendente, em_andamento, concluida)
- `prioridade` (enum: baixa, media, alta)

**Campos opcionais:**
- `descricao` (texto)
- `data_limite` (data no formato YYYY-MM-DD)

**Response (201):**
```json
{
    "id": 2,
    "titulo": "Nova tarefa",
    "descricao": "Descrição da tarefa",
    "status": "pendente",
    "prioridade": "media",
    "data_limite": "2024-01-20",
    "empresa_id": 1,
    "user_id": 1,
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
}
```

#### GET /tarefas/{id}
Obtém uma tarefa específica (requer autenticação).

**Response (200):**
```json
{
    "id": 1,
    "titulo": "Implementar autenticação JWT",
    "descricao": "Configurar sistema de autenticação JWT para a API",
    "status": "concluida",
    "prioridade": "alta",
    "data_limite": "2024-01-15",
    "empresa_id": 1,
    "user_id": 1,
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
}
```

#### PUT /tarefas/{id}
Atualiza uma tarefa (requer autenticação).

**Body:**
```json
{
    "titulo": "Tarefa atualizada",
    "descricao": "Nova descrição",
    "status": "em_andamento",
    "prioridade": "alta",
    "data_limite": "2024-01-25"
}
```

**Response (200):**
```json
{
    "id": 1,
    "titulo": "Tarefa atualizada",
    "descricao": "Nova descrição",
    "status": "em_andamento",
    "prioridade": "alta",
    "data_limite": "2024-01-25",
    "empresa_id": 1,
    "user_id": 1,
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T11:00:00.000000Z"
}
```

#### DELETE /tarefas/{id}
Exclui uma tarefa (requer autenticação).

**Response (200):**
```json
{
    "mensagem": "Tarefa excluída com sucesso."
}
```

### 🔍 Filtros

#### GET /tarefas/filtrar/status/{status}
Filtra tarefas por status (requer autenticação).

**Parâmetros:**
- `status`: pendente, em_andamento, concluida

**Response (200):**
```json
[
    {
        "id": 1,
        "titulo": "Implementar autenticação JWT",
        "status": "concluida",
        // ... outros campos
    }
]
```

#### GET /tarefas/filtrar/prioridade/{prioridade}
Filtra tarefas por prioridade (requer autenticação).

**Parâmetros:**
- `prioridade`: baixa, media, alta

**Response (200):**
```json
[
    {
        "id": 1,
        "titulo": "Implementar autenticação JWT",
        "prioridade": "alta",
        // ... outros campos
    }
]
```

### 📊 Exportação

#### GET /tarefas/exportar
Exporta tarefas em formato Excel (requer autenticação).

**Response:**
- Arquivo Excel para download
- Nome do arquivo: `tarefas_{empresa}_{data}.xlsx`

## Códigos de Status HTTP

- **200**: Sucesso
- **201**: Criado com sucesso
- **400**: Requisição inválida
- **401**: Não autorizado
- **403**: Proibido
- **404**: Não encontrado
- **422**: Erro de validação
- **500**: Erro interno do servidor

## Exemplos de Uso

### Exemplo com cURL

#### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@empresa.com",
    "password": "senha123"
  }'
```

#### Criar tarefa
```bash
curl -X POST http://localhost:8000/api/tarefas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {seu_token}" \
  -d '{
    "titulo": "Nova tarefa",
    "descricao": "Descrição da tarefa",
    "status": "pendente",
    "prioridade": "media",
    "data_limite": "2024-01-20"
  }'
```

#### Listar tarefas
```bash
curl -X GET http://localhost:8000/api/tarefas \
  -H "Authorization: Bearer {seu_token}"
```

### Exemplo com JavaScript (Axios)

```javascript
// Login
const loginResponse = await axios.post('/api/login', {
    email: 'joao@empresa.com',
    password: 'senha123'
});

const token = loginResponse.data.access_token;

// Configurar token para requisições futuras
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

// Criar tarefa
const tarefaResponse = await axios.post('/api/tarefas', {
    titulo: 'Nova tarefa',
    descricao: 'Descrição da tarefa',
    status: 'pendente',
    prioridade: 'media',
    data_limite: '2024-01-20'
});

// Listar tarefas
const tarefasResponse = await axios.get('/api/tarefas');
```

## Validações

### Tarefas
- **titulo**: obrigatório, string, máximo 255 caracteres
- **status**: obrigatório, deve ser: pendente, em_andamento, concluida
- **prioridade**: obrigatório, deve ser: baixa, media, alta
- **data_limite**: opcional, formato de data válido (YYYY-MM-DD)

### Usuário
- **name**: obrigatório, string, máximo 255 caracteres
- **email**: obrigatório, email válido, único
- **password**: obrigatório, mínimo 6 caracteres, confirmação obrigatória

### Empresa
- **nome**: obrigatório, string, máximo 255 caracteres
- **identificador**: obrigatório, string, máximo 255 caracteres, único

## Notificações

O sistema envia automaticamente e-mails para:
- Criação de nova tarefa
- Conclusão de tarefa

As notificações são processadas de forma assíncrona via filas.

## Segurança

- Todos os endpoints de tarefas são isolados por empresa
- Usuários só podem acessar tarefas da sua própria empresa
- Tokens JWT têm tempo de expiração configurável
- Validação de entrada em todos os endpoints
- Proteção CSRF habilitada 