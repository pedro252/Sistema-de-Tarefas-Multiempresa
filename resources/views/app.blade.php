<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Tarefas Multiempresa</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .task-card {
            transition: all 0.3s ease;
        }
        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .priority-badge {
            font-size: 0.75rem;
        }
        .status-pendente { background-color: #ffc107; }
        .status-em_andamento { background-color: #17a2b8; }
        .status-concluida { background-color: #28a745; }
        .priority-baixa { background-color: #6c757d; }
        .priority-media { background-color: #fd7e14; }
        .priority-alta { background-color: #dc3545; }
    </style>
</head>
<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-tasks me-2"></i>
                    Sistema de Tarefas
                </a>
                
                <div class="navbar-nav ms-auto" v-if="isAuthenticated">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user me-1"></i>
                        @{{ user.name }} - @{{ user.empresa ? user.empresa.nome : 'N/A' }}
                    </span>
                    <button class="btn btn-outline-light btn-sm" @click="logout">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        Sair
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mt-4">
            <!-- Login Form -->
            <div v-if="!isAuthenticated" class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
                            </h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="login">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="email" 
                                        v-model="loginForm.email" 
                                        required
                                    >
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Senha</label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        id="password" 
                                        v-model="loginForm.password" 
                                        required
                                    >
                                </div>
                                <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                                    <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                                    Entrar
                                </button>
                            </form>
                            
                            <hr>
                            
                            <div class="text-center">
                                <button class="btn btn-outline-secondary btn-sm" @click="showRegister = true">
                                    Criar nova conta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Register Form -->
            <div v-if="!isAuthenticated && showRegister" class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user-plus me-2"></i>
                                Criar Nova Conta
                            </h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="register">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Dados da Empresa</h6>
                                        <div class="mb-3">
                                            <label for="empresa_nome" class="form-label">Nome da Empresa</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="empresa_nome" 
                                                v-model="registerForm.empresa_nome" 
                                                required
                                            >
                                        </div>
                                        <div class="mb-3">
                                            <label for="empresa_identificador" class="form-label">Identificador da Empresa</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="empresa_identificador" 
                                                v-model="registerForm.empresa_identificador" 
                                                required
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Dados do Usuário</h6>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nome</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="name" 
                                                v-model="registerForm.name" 
                                                required
                                            >
                                        </div>
                                        <div class="mb-3">
                                            <label for="register_email" class="form-label">Email</label>
                                            <input 
                                                type="email" 
                                                class="form-control" 
                                                id="register_email" 
                                                v-model="registerForm.email" 
                                                required
                                            >
                                        </div>
                                        <div class="mb-3">
                                            <label for="register_password" class="form-label">Senha</label>
                                            <input 
                                                type="password" 
                                                class="form-control" 
                                                id="register_password" 
                                                v-model="registerForm.password" 
                                                required
                                            >
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                                            <input 
                                                type="password" 
                                                class="form-control" 
                                                id="password_confirmation" 
                                                v-model="registerForm.password_confirmation" 
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" :disabled="loading">
                                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                                        Criar Conta
                                    </button>
                                    <button type="button" class="btn btn-secondary" @click="showRegister = false">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard -->
            <div v-if="isAuthenticated">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h2>
                            <i class="fas fa-tasks me-2"></i>
                            Minhas Tarefas
                        </h2>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-primary" @click="showCreateForm()">
                            <i class="fas fa-plus me-1"></i>
                            Nova Tarefa
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" v-model="filters.status">
                                            <option value="">Todos</option>
                                            <option value="pendente">Pendente</option>
                                            <option value="em_andamento">Em Andamento</option>
                                            <option value="concluida">Concluída</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Prioridade</label>
                                        <select class="form-select" v-model="filters.prioridade">
                                            <option value="">Todas</option>
                                            <option value="baixa">Baixa</option>
                                            <option value="media">Média</option>
                                            <option value="alta">Alta</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Buscar</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            placeholder="Título ou descrição..."
                                            v-model="filters.search"
                                        >
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button class="btn btn-outline-secondary me-2" @click="clearFilters">
                                            <i class="fas fa-times me-1"></i>
                                            Limpar
                                        </button>
                                        <button class="btn btn-success" @click="exportTasks">
                                            <i class="fas fa-download me-1"></i>
                                            Exportar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks List -->
                <div class="row">
                    <div class="col-md-12">
                        <div v-if="loading" class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>
                        
                        <div v-else-if="filteredTasks.length === 0" class="text-center">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhuma tarefa encontrada</h5>
                            <p class="text-muted">Crie sua primeira tarefa para começar!</p>
                        </div>
                        
                        <div v-else class="row">
                            <div 
                                v-for="task in filteredTasks" 
                                :key="task.id" 
                                class="col-md-6 col-lg-4 mb-3"
                            >
                                <div class="card task-card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="card-title mb-0">@{{ task.titulo }}</h6>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" @click="editTask(task)">
                                                    <i class="fas fa-edit me-2"></i>Editar
                                                </a></li>
                                                <li><a class="dropdown-item text-danger" href="#" @click="deleteTask(task.id)">
                                                    <i class="fas fa-trash me-2"></i>Excluir
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">@{{ task.descricao || 'Sem descrição' }}</p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span :class="['badge', 'status-' + task.status, 'priority-badge']">
                                                @{{ getStatusLabel(task.status) }}
                                            </span>
                                            <span :class="['badge', 'priority-' + task.prioridade, 'priority-badge']">
                                                @{{ getPriorityLabel(task.prioridade) }}
                                            </span>
                                        </div>
                                        
                                        <div v-if="task.data_limite" class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Prazo: @{{ formatDate(task.data_limite) }}
                                            </small>
                                        </div>
                                        
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Criada em @{{ formatDate(task.created_at) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Task Modal -->
        <div class="modal fade" id="taskModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-edit me-2"></i>
                            @{{ editingTask ? 'Editar Tarefa' : 'Nova Tarefa' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveTask">
                            <div class="mb-3">
                                <label for="task_title" class="form-label">Título *</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="task_title" 
                                    v-model="taskForm.titulo" 
                                    required
                                >
                            </div>
                            <div class="mb-3">
                                <label for="task_description" class="form-label">Descrição</label>
                                <textarea 
                                    class="form-control" 
                                    id="task_description" 
                                    rows="3"
                                    v-model="taskForm.descricao"
                                ></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="task_status" class="form-label">Status *</label>
                                        <select class="form-select" id="task_status" v-model="taskForm.status" required>
                                            <option value="pendente">Pendente</option>
                                            <option value="em_andamento">Em Andamento</option>
                                            <option value="concluida">Concluída</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="task_priority" class="form-label">Prioridade *</label>
                                        <select class="form-select" id="task_priority" v-model="taskForm.prioridade" required>
                                            <option value="baixa">Baixa</option>
                                            <option value="media">Média</option>
                                            <option value="alta">Alta</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="task_deadline" class="form-label">Data Limite</label>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="task_deadline" 
                                    v-model="taskForm.data_limite"
                                >
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="saveTask" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                            @{{ editingTask ? 'Atualizar' : 'Criar' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Vue.js -->
    <script src="https://unpkg.com/vue@2/dist/vue.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html> 