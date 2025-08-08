# 🚀 Instalação Rápida - Sistema de Tarefas Multiempresa

## ⚡ Instalação em 5 minutos

### 1. Pré-requisitos
- PHP 8.0+
- Composer
- MySQL
- `php artisan serve`

### 2. Clone e configure
```bash
# Clone o repositório
git clone <url-do-repositorio>
cd tarefas-multiempresa

# Instale as dependências
composer install

# Configure o ambiente
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### 3. Configure o banco de dados
Edite o arquivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tarefas_multiempresa
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 4. Execute as migrations
```bash
php artisan migrate
```

### 5. Crie o primeiro usuário
```bash
php artisan app:create-first-user
```

### 6. Inicie o servidor
```bash
php artisan serve
```

### 7. Acesse a aplicação
Abra o navegador e acesse: `http://localhost:8000`

## 🎯 Funcionalidades Implementadas

✅ **Autenticação JWT**
- Registro e login
- Tokens seguros
- Logout

✅ **Multitenancy**
- Múltiplas empresas
- Isolamento de dados
- Usuários por empresa

✅ **CRUD de Tarefas**
- Criar, ler, atualizar, excluir
- Validações completas
- Filtros por status e prioridade

✅ **Frontend Vue.js**
- Interface responsiva
- Bootstrap 5
- Validações em tempo real

✅ **Exportação Excel**
- Download de tarefas
- Filtros aplicados
- Formato profissional

✅ **Notificações por E-mail**
- Criação de tarefas
- Conclusão de tarefas
- Processamento assíncrono

✅ **Comando Artisan**
- Criação interativa de usuários
- Validações automáticas

## 🔧 Configurações Opcionais

### E-mail (para notificações)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
```

### Filas (para processamento assíncrono)
```bash
# Configure no .env
QUEUE_CONNECTION=database

# Execute migration das filas
php artisan queue:table
php artisan migrate

# Inicie o worker
php artisan queue:work
```

## 📚 Dados de Teste

Se preferir usar dados de teste:
```bash
php artisan db:seed
```

**Credenciais:**
- Email: `joao@techsolutions.com`
- Senha: `password123`

## 🚨 Solução de Problemas

### Erro de permissão
```bash
chmod -R 755 storage bootstrap/cache
```

### Erro de banco de dados
```bash
php artisan config:clear
php artisan cache:clear
```

### Erro de JWT
```bash
php artisan jwt:secret
```

### Erro de dependências
```bash
composer install --no-dev
```

## 📱 Testando a API

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"joao@techsolutions.com","password":"password123"}'
```

### Criar tarefa
```bash
curl -X POST http://localhost:8000/api/tarefas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {seu_token}" \
  -d '{"titulo":"Teste","status":"pendente","prioridade":"media"}'
```

## 🎉 Pronto!

Seu sistema está funcionando! Acesse `http://localhost:8000` e comece a usar.

Para mais detalhes, consulte o `README.md` completo. 