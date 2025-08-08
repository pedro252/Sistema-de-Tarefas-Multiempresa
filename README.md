# Sistema de Tarefas Multiempresa

Sistema completo de gerenciamento de tarefas (todo list) com suporte a múltiplas empresas (multitenancy), autenticação JWT e comunicação via API REST.

## 🚀 Tecnologias

- **Backend**: Laravel 8 com JWT (tymon/jwt-auth)
- **Frontend**: Vue.js 2 com Bootstrap 5
- **Banco de Dados**: MySQL/PostgreSQL
- **Exportação**: Laravel Excel (maatwebsite/excel)
- **Filas**: Laravel Queues para processamento assíncrono

## ✨ Funcionalidades

### 🔐 Autenticação
- Registro e login com autenticação JWT
- Cada usuário pertence a uma empresa específica (tenant)
- Isolamento completo dos dados por empresa

### 🏢 Multitenancy
- Suporte a múltiplas empresas
- Isolamento automático dos dados de tarefas e usuários
- Dados da empresa: nome e identificador único

### 📋 Gerenciamento de Tarefas
- CRUD completo de tarefas
- Campos: título, descrição, status, prioridade, data limite
- Validação completa dos campos
- Filtros por status e prioridade
- Busca por título e descrição

### 📧 Notificações
- Envio automático de e-mails ao criar tarefa
- Envio automático de e-mails ao concluir tarefa
- Processamento assíncrono via filas

### 📊 Exportação
- Exportação de tarefas em Excel
- Filtros aplicados na exportação
- Download automático do arquivo

## 🛠️ Instalação

### Pré-requisitos
- PHP 8.0 ou superior
- Composer
- MySQL/PostgreSQL
- Node.js (opcional, para compilação de assets)

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd tarefas-multiempresa
```

### 2. Instale as dependências
```bash
composer install
```

### 3. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### 4. Configure o banco de dados
Edite o arquivo `.env` com suas configurações de banco:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tarefas_multiempresa
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Configure o e-mail (opcional)
Para as notificações funcionarem, configure o e-mail no `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. Execute as migrations
```bash
php artisan migrate
```

### 7. Execute os seeders (opcional)
```bash
php artisan db:seed
```

### 8. Configure as filas (opcional)
Para processamento assíncrono das notificações:
```bash
# Configure o driver de fila no .env
QUEUE_CONNECTION=database

# Execute a migration das filas
php artisan queue:table
php artisan migrate

# Inicie o worker das filas
php artisan queue:work
```

### 9. Inicie o servidor
```bash
php artisan serve
```

## 🎯 Primeiro Acesso

### Configuração Inicial

1. **Execute as migrations:**
   ```bash
   php artisan migrate
   ```

2. **Crie a primeira empresa e usuário:**
   ```bash
   php artisan app:create-first-user
   ```
   Siga as instruções interativas para criar a primeira empresa e usuário administrador.

3. **Inicie o servidor:**
   ```bash
   php artisan serve
   ```

4. **Acesse o sistema:**
   - URL: http://localhost:8000
   - Use as credenciais criadas no passo 2

### Opções Alternativas

#### Usar dados do seeder
Se você executou `php artisan db:seed`, pode usar:
- **Email**: joao@techsolutions.com
- **Senha**: password123

#### Verificar dados existentes
Para ver empresas e usuários já cadastrados:
```bash
php artisan app:list-users
```

## 📚 API Endpoints

### Autenticação
```
POST /api/register - Registrar novo usuário
POST /api/login - Fazer login
POST /api/logout - Fazer logout
GET /api/user - Obter dados do usuário
```

### Tarefas
```
GET /api/tarefas - Listar tarefas
POST /api/tarefas - Criar tarefa
GET /api/tarefas/{id} - Obter tarefa
PUT /api/tarefas/{id} - Atualizar tarefa
DELETE /api/tarefas/{id} - Excluir tarefa
```

### Filtros
```
GET /api/tarefas/filtrar/status/{status} - Filtrar por status
GET /api/tarefas/filtrar/prioridade/{prioridade} - Filtrar por prioridade
```

### Exportação
```
GET /api/tarefas/exportar - Exportar tarefas em Excel
```

## 🔧 Comandos Artisan

### Comandos do Sistema

#### Criar primeiro usuário
```bash
php artisan app:create-first-user
```
Cria interativamente a primeira empresa e usuário do sistema.

#### Listar empresas e usuários
```bash
# Listar todas as empresas e usuários
php artisan app:list-users

# Listar apenas usuários de uma empresa específica
php artisan app:list-users --empresa=1
```

#### Limpar dados de teste
```bash
# Com confirmação interativa
php artisan app:clear-test-data

# Forçar limpeza sem confirmação
php artisan app:clear-test-data --force
```
⚠️ **ATENÇÃO:** Este comando remove TODOS os dados do sistema!

### Comandos Úteis

#### Limpar cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

#### Processar filas
```bash
php artisan queue:work
```

#### Verificar status
```bash
php artisan migrate:status
php artisan queue:failed
```

Para mais detalhes sobre os comandos, consulte o arquivo `COMANDOS_ARTISAN.md`.

## 🎨 Frontend

O frontend está implementado em Vue.js 2 com:
- Interface responsiva com Bootstrap 5
- Autenticação JWT
- CRUD completo de tarefas
- Filtros e busca
- Exportação de dados
- Validações em tempo real

### Acesse a aplicação
Após iniciar o servidor, acesse: `http://localhost:8000`

## 🔒 Segurança

- Autenticação JWT com tokens seguros
- Isolamento completo de dados por empresa
- Validação de entrada em todos os endpoints
- Proteção CSRF
- Sanitização de dados

## 📧 Notificações

O sistema envia automaticamente e-mails para:
- Criação de nova tarefa
- Conclusão de tarefa

Para que as notificações funcionem:
1. Configure o e-mail no `.env`
2. Execute `php artisan queue:work` para processar as filas

## 🚀 Deploy

### Produção
1. Configure o ambiente de produção no `.env`
2. Execute `composer install --optimize-autoloader --no-dev`
3. Configure o servidor web (Apache/Nginx)
4. Configure as filas para produção

### Docker (opcional)
```bash
# Build da imagem
docker build -t tarefas-multiempresa .

# Executar container
docker run -p 8000:8000 tarefas-multiempresa
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 🆘 Suporte

Se você encontrar algum problema ou tiver dúvidas:
1. Verifique se todas as dependências estão instaladas
2. Confirme se o banco de dados está configurado corretamente
3. Verifique os logs em `storage/logs/laravel.log`
4. Abra uma issue no repositório

## 📝 Changelog

### v1.0.0
- Implementação inicial do sistema
- Autenticação JWT
- CRUD de tarefas
- Multitenancy
- Frontend Vue.js
- Exportação Excel
- Notificações por e-mail
