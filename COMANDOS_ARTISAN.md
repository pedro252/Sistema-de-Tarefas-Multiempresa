# Comandos Artisan - Sistema de Tarefas Multiempresa

Este documento descreve os comandos Artisan disponíveis para administração do sistema.

## 📋 Comandos Disponíveis

### 1. `app:create-first-user`
**Descrição:** Criar a primeira empresa e usuário do sistema interativamente.

**Uso:**
```bash
php artisan app:create-first-user
```

**Funcionalidades:**
- Coleta dados da empresa (nome e identificador)
- Coleta dados do usuário (nome, e-mail e senha)
- Valida se já existem empresas/usuários cadastrados
- Cria a empresa e o usuário associado
- Fornece instruções para acesso ao sistema

**Exemplo de uso:**
```bash
$ php artisan app:create-first-user

=== Sistema de Tarefas Multiempresa ===
Configuração inicial do sistema

Vamos criar a primeira empresa e usuário administrador.

📋 DADOS DA EMPRESA
-------------------
Nome da empresa: Minha Empresa LTDA
Identificador da empresa (código único): EMP001

👤 DADOS DO USUÁRIO ADMINISTRADOR
----------------------------------
Nome completo do usuário: João Silva
E-mail do usuário: joao@empresa.com
Senha do usuário (mínimo 6 caracteres): ********
Confirme a senha: ********

📝 RESUMO DOS DADOS
-------------------
Empresa: Minha Empresa LTDA (EMP001)
Usuário: João Silva (joao@empresa.com)

Confirma a criação da empresa e usuário? (yes/no) [no]:
> yes

Criando empresa...
✅ Empresa criada com sucesso!
Criando usuário...
✅ Usuário criado com sucesso!

🎉 CONFIGURAÇÃO CONCLUÍDA!
========================
Empresa ID: 1
Usuário ID: 1

Agora você pode fazer login no sistema com:
E-mail: joao@empresa.com
Senha: [a senha que você digitou]

Para acessar o sistema, execute:
php artisan serve
E acesse: http://localhost:8000
```

---

### 2. `app:list-users`
**Descrição:** Listar empresas e usuários do sistema.

**Uso:**
```bash
# Listar todas as empresas e usuários
php artisan app:list-users

# Listar apenas usuários de uma empresa específica
php artisan app:list-users --empresa=1
```

**Funcionalidades:**
- Exibe tabela com todas as empresas cadastradas
- Exibe tabela com todos os usuários
- Mostra estatísticas gerais do sistema
- Permite filtrar usuários por empresa

**Exemplo de saída:**
```
=== Sistema de Tarefas Multiempresa ===
Listagem de Empresas e Usuários

📋 EMPRESAS CADASTRADAS
----------------------
+----+------------------+----------------+----------+---------------------+
| ID | Nome             | Identificador  | Usuários | Criada em           |
+----+------------------+----------------+----------+---------------------+
| 1  | Minha Empresa    | EMP001         | 2        | 15/01/2024 10:30:00 |
| 2  | Outra Empresa    | EMP002         | 1        | 15/01/2024 11:00:00 |
+----+------------------+----------------+----------+---------------------+

👤 USUÁRIOS CADASTRADOS
----------------------
+----+-------------+----------------------+-------------+---------------------+
| ID | Nome        | E-mail               | Empresa     | Criado em           |
+----+-------------+----------------------+-------------+---------------------+
| 1  | João Silva  | joao@empresa.com     | Minha Empresa| 15/01/2024 10:30:00 |
| 2  | Maria Santos| maria@empresa.com    | Minha Empresa| 15/01/2024 10:35:00 |
| 3  | Pedro Costa | pedro@outra.com      | Outra Empresa| 15/01/2024 11:00:00 |
+----+-------------+----------------------+-------------+---------------------+

📊 RESUMO
---------
Total de empresas: 2
Total de usuários: 3
```

---

### 3. `app:clear-test-data`
**Descrição:** Limpar todos os dados de teste do sistema (empresas, usuários e tarefas).

**Uso:**
```bash
# Com confirmação interativa
php artisan app:clear-test-data

# Forçar limpeza sem confirmação
php artisan app:clear-test-data --force
```

**Funcionalidades:**
- Remove todas as tarefas, usuários e empresas
- Requer confirmação dupla por segurança
- Opção `--force` para automação
- Fornece instruções para recriar dados

**⚠️ ATENÇÃO:** Este comando remove TODOS os dados do sistema de forma irreversível!

**Exemplo de uso:**
```bash
$ php artisan app:clear-test-data

=== Sistema de Tarefas Multiempresa ===
Limpeza de Dados de Teste

⚠️  ATENÇÃO: Esta operação irá remover TODOS os dados do sistema!

Dados que serão removidos:
• 2 empresa(s)
• 3 usuário(s)
• 15 tarefa(s)

Tem certeza que deseja continuar? (yes/no) [no]:
> yes

Esta ação é irreversível. Confirma novamente? (yes/no) [no]:
> yes

Iniciando limpeza dos dados...
Removendo tarefas...
✅ 15 tarefa(s) removida(s)
Removendo usuários...
✅ 3 usuário(s) removido(s)
Removendo empresas...
✅ 2 empresa(s) removida(s)

🎉 LIMPEZA CONCLUÍDA!
====================
Todos os dados foram removidos com sucesso.

Para criar novos dados, execute:
php artisan app:create-first-user
```

---

## 🚀 Fluxo de Configuração Inicial

Para configurar o sistema pela primeira vez:

1. **Execute as migrations:**
   ```bash
   php artisan migrate
   ```

2. **Crie a primeira empresa e usuário:**
   ```bash
   php artisan app:create-first-user
   ```

3. **Inicie o servidor:**
   ```bash
   php artisan serve
   ```

4. **Acesse o sistema:**
   - URL: http://localhost:8000
   - Use as credenciais criadas no passo 2

---

## 🔧 Comandos Úteis Adicionais

### Verificar comandos disponíveis:
```bash
php artisan list
```

### Verificar status das migrations:
```bash
php artisan migrate:status
```

### Limpar cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Gerar chave da aplicação:
```bash
php artisan key:generate
```

### Gerar chave JWT:
```bash
php artisan jwt:secret
```

---

## 📝 Notas Importantes

- Todos os comandos são seguros e incluem validações
- O comando `app:clear-test-data` deve ser usado apenas em ambiente de desenvolvimento
- Sempre faça backup dos dados antes de usar comandos de limpeza
- Os comandos incluem verificações para evitar duplicação de dados
- Mensagens de erro são claras e informativas
