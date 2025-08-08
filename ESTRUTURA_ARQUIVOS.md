# Estrutura de Arquivos - Sistema de Tarefas Multiempresa

## Visão Geral
O sistema foi reorganizado para ter uma estrutura mais limpa e organizada, separando cada funcionalidade em suas próprias pastas e arquivos.

## Estrutura de Pastas

### 📁 `resources/views/`
```
views/
├── layouts/
│   └── app.blade.php          # Layout base da aplicação
├── auth/
│   └── login.blade.php        # Página de login
├── cadastro/
│   └── index.blade.php        # Página de cadastro
└── tarefas/
    └── index.blade.php        # Página de listagem de tarefas
```

### 📁 `public/js/`
```
js/
├── auth.js                    # Funções de autenticação compartilhadas
└── app.js                     # Arquivo principal (legado)
```

## Descrição dos Arquivos

### Layout Base (`layouts/app.blade.php`)
- **Função**: Layout base usado por todas as páginas
- **Contém**: 
  - Meta tags e CSS
  - Navbar com informações do usuário
  - Modal para criação/edição de tarefas
  - Scripts base (Bootstrap, Vue.js, Axios)

### Página de Login (`auth/login.blade.php`)
- **Função**: Autenticação de usuários
- **Características**:
  - Formulário de login
  - Link para cadastro
  - Redirecionamento automático se já autenticado

### Página de Cadastro (`cadastro/index.blade.php`)
- **Função**: Criação de novas contas
- **Características**:
  - Formulário de cadastro com dados da empresa e usuário
  - Validação de senhas
  - Redirecionamento automático se já autenticado

### Página de Tarefas (`tarefas/index.blade.php`)
- **Função**: Gerenciamento de tarefas
- **Características**:
  - Listagem de tarefas com filtros
  - Criação, edição e exclusão de tarefas
  - Exportação de dados (apenas tarefas do usuário logado)
  - Importação de dados (com validação e template)
  - Proteção de rota (requer autenticação)

### JavaScript de Autenticação (`js/auth.js`)
- **Função**: Funções compartilhadas de autenticação
- **Contém**:
  - Configuração do Axios
  - Interceptors para tokens JWT
  - Funções utilitárias (setAuthData, clearAuthData, checkAuth)
  - Funções de erro e sucesso

### Funcionalidades de Importação/Exportação

#### Exportação (`app/Exports/TarefasExport.php`)
- **Função**: Exporta apenas as tarefas do usuário logado
- **Formato**: Excel (.xlsx)
- **Colunas**: ID, Título, Descrição, Status, Prioridade, Data Limite, Criado em

#### Importação (`app/Imports/TarefasImport.php`)
- **Função**: Importa tarefas de arquivo Excel/CSV
- **Validação**: Verifica formato e dados obrigatórios
- **Segurança**: Associa automaticamente ao usuário logado
- **Formatos suportados**: .xlsx, .xls, .csv

#### Template (`app/Exports/TarefasTemplateExport.php`)
- **Função**: Gera template de exemplo para importação
- **Inclui**: Exemplos de dados e estrutura correta

## Rotas

### Web Routes (`routes/web.php`)
```php
// Página principal (login)
Route::get('/', function () {
    return view('auth.login');
});

// Página de cadastro
Route::get('/cadastro', function () {
    return view('cadastro.index');
});

// Página de tarefas
Route::get('/tarefas', function () {
    return view('tarefas.index');
});

// Exportação de tarefas
Route::get('/tarefas/exportar', [TarefaExportController::class, 'export']);
```

## Vantagens da Nova Estrutura

1. **Organização**: Cada funcionalidade tem sua própria pasta
2. **Manutenibilidade**: Código mais fácil de encontrar e modificar
3. **Reutilização**: Layout base compartilhado entre páginas
4. **Separação de Responsabilidades**: JavaScript de autenticação separado
5. **URLs Limpas**: Cada página tem sua própria URL
6. **Navegação Nativa**: Usar links HTML em vez de JavaScript para navegação

## Como Usar

1. **Acesse a página inicial**: `http://127.0.0.1:8000/`
2. **Faça login** ou **crie uma conta** em `/cadastro`
3. **Gerencie tarefas** em `/tarefas`
4. **Exporte dados** clicando no botão "Exportar"

## URLs Disponíveis

- `/` - Página de login
- `/cadastro` - Página de cadastro
- `/tarefas` - Página de tarefas (requer autenticação)
- `/tarefas/exportar` - Exportação de tarefas (requer autenticação)
- `/tarefas/importar` - Importação de tarefas (requer autenticação)
- `/tarefas/template` - Download do template de exemplo

## Benefícios da Mudança

✅ **URLs diferentes para cada página**  
✅ **Estrutura organizada por funcionalidade**  
✅ **Código mais limpo e manutenível**  
✅ **Navegação nativa do navegador**  
✅ **Histórico de navegação funcionando**  
✅ **Fácil de expandir com novas páginas**
