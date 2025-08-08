@extends('layouts.app')

@section('content')
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
                        <button class="btn btn-success me-2" @click="exportTasks">
                            <i class="fas fa-download me-1"></i>
                            Exportar
                        </button>
                        <button class="btn btn-info" @click="showImportModal">
                            <i class="fas fa-upload me-1"></i>
                            Importar
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>
                    Importar Tarefas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="importTasks" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="import_file" class="form-label">Arquivo Excel/CSV</label>
                        <input 
                            type="file" 
                            class="form-control" 
                            id="import_file" 
                            accept=".xlsx,.xls,.csv"
                            @change="onFileSelected"
                            required
                        >
                        <div class="form-text">
                            Formatos aceitos: .xlsx, .xls, .csv<br>
                            O arquivo deve ter as colunas: Título, Descrição, Status, Prioridade, Data Limite<br>
                            <a href="/tarefas/template" class="text-decoration-none">
                                <i class="fas fa-download me-1"></i>Baixar template de exemplo
                            </a>
                        </div>
                    </div>
                    <div class="mb-3" v-if="selectedFile">
                        <div class="alert alert-info">
                            <strong>Arquivo selecionado:</strong> @{{ selectedFile.name }}
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="importTasks" :disabled="!selectedFile || importLoading">
                    <span v-if="importLoading" class="spinner-border spinner-border-sm me-2"></span>
                    Importar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
new Vue({
    el: '#app',
    data: {
        isAuthenticated: false,
        user: null,
        loading: false,
        tasks: [],
        taskForm: {
            titulo: '',
            descricao: '',
            status: 'pendente',
            prioridade: 'media',
            data_limite: ''
        },
        editingTask: null,
        filters: {
            status: '',
            prioridade: '',
            search: ''
        },
        selectedFile: null,
        importLoading: false
    },
    
    computed: {
        filteredTasks() {
            let filtered = this.tasks;
            
            if (this.filters.status) {
                filtered = filtered.filter(task => task.status === this.filters.status);
            }
            
            if (this.filters.prioridade) {
                filtered = filtered.filter(task => task.prioridade === this.filters.prioridade);
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                filtered = filtered.filter(task => 
                    task.titulo.toLowerCase().includes(search) ||
                    (task.descricao && task.descricao.toLowerCase().includes(search))
                );
            }
            
            return filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }
    },
    
    mounted() {
        this.checkAuth();
        this.loadTasks();
    },
    
    methods: {
        async logout() {
            try {
                await axios.post('/logout');
            } catch (error) {
                console.error('Erro no logout:', error);
            } finally {
                clearAuthData();
                window.location.href = '/';
            }
        },
        
        checkAuth() {
            const user = checkAuth();
            if (user) {
                this.isAuthenticated = true;
                this.user = user;
            } else {
                window.location.href = '/';
            }
        },
        
        async loadTasks() {
            this.loading = true;
            try {
                const response = await axios.get('/tarefas');
                this.tasks = response.data;
            } catch (error) {
                showError('Erro ao carregar tarefas: ' + getErrorMessage(error));
            } finally {
                this.loading = false;
            }
        },
        
        showCreateForm() {
            this.editingTask = null;
            this.resetTaskForm();
            this.$nextTick(() => {
                const modal = new bootstrap.Modal(document.getElementById('taskModal'));
                modal.show();
            });
        },
        
        editTask(task) {
            this.editingTask = task;
            this.taskForm = {
                titulo: task.titulo,
                descricao: task.descricao || '',
                status: task.status,
                prioridade: task.prioridade,
                data_limite: task.data_limite ? task.data_limite.split('T')[0] : ''
            };
            this.$nextTick(() => {
                const modal = new bootstrap.Modal(document.getElementById('taskModal'));
                modal.show();
            });
        },
        
        async saveTask() {
            this.loading = true;
            try {
                if (this.editingTask) {
                    await axios.put(`/tarefas/${this.editingTask.id}`, this.taskForm);
                    showSuccess('Tarefa atualizada com sucesso!');
                } else {
                    await axios.post('/tarefas', this.taskForm);
                    showSuccess('Tarefa criada com sucesso!');
                }
                
                this.loadTasks();
                this.closeModal();
            } catch (error) {
                showError('Erro ao salvar tarefa: ' + getErrorMessage(error));
            } finally {
                this.loading = false;
            }
        },
        
        async deleteTask(taskId) {
            if (!confirm('Tem certeza que deseja excluir esta tarefa?')) {
                return;
            }
            
            try {
                await axios.delete(`/tarefas/${taskId}`);
                showSuccess('Tarefa excluída com sucesso!');
                this.loadTasks();
            } catch (error) {
                showError('Erro ao excluir tarefa: ' + getErrorMessage(error));
            }
        },
        
        exportTasks() {
            const link = document.createElement('a');
            link.href = '/tarefas/exportar';
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            link.remove();
        },
        
        showImportModal() {
            this.selectedFile = null;
            this.$nextTick(() => {
                const modal = new bootstrap.Modal(document.getElementById('importModal'));
                modal.show();
            });
        },
        
        onFileSelected(event) {
            this.selectedFile = event.target.files[0];
        },
        
        async importTasks() {
            if (!this.selectedFile) {
                showError('Por favor, selecione um arquivo.');
                return;
            }
            
            this.importLoading = true;
            
            const formData = new FormData();
            formData.append('file', this.selectedFile);
            
            try {
                const response = await axios.post('/tarefas/importar', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                
                if (response.data.success) {
                    showSuccess(response.data.message);
                    this.closeImportModal();
                    this.loadTasks();
                } else {
                    showError(response.data.message);
                }
            } catch (error) {
                showError('Erro ao importar tarefas: ' + getErrorMessage(error));
            } finally {
                this.importLoading = false;
            }
        },
        
        closeImportModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
            if (modal) {
                modal.hide();
            }
            this.selectedFile = null;
            // Limpar o input de arquivo
            document.getElementById('import_file').value = '';
        },
        
        resetTaskForm() {
            this.taskForm = {
                titulo: '',
                descricao: '',
                status: 'pendente',
                prioridade: 'media',
                data_limite: ''
            };
        },
        
        closeModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('taskModal'));
            if (modal) {
                modal.hide();
            }
            this.editingTask = null;
        },
        
        clearFilters() {
            this.filters = {
                status: '',
                prioridade: '',
                search: ''
            };
        },
        
        getStatusLabel(status) {
            const labels = {
                'pendente': 'Pendente',
                'em_andamento': 'Em Andamento',
                'concluida': 'Concluída'
            };
            return labels[status] || status;
        },
        
        getPriorityLabel(priority) {
            const labels = {
                'baixa': 'Baixa',
                'media': 'Média',
                'alta': 'Alta'
            };
            return labels[priority] || priority;
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        },
        

    }
});
</script>
@endsection
