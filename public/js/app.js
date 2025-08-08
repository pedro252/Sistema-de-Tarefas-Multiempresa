
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

axios.interceptors.request.use(function (config) {
    const token = localStorage.getItem('jwt_token');
    if (token) {
        config.headers.Authorization = 'Bearer ' + token;
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response && error.response.status === 401) {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('user');
        window.location.reload();
    }
    return Promise.reject(error);
});

new Vue({
    el: '#app',
    data: {

        isAuthenticated: false,
        user: null,
        loading: false,
        
        loginForm: {
            email: '',
            password: ''
        },
        registerForm: {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            empresa_nome: '',
            empresa_identificador: ''
        },
        showRegister: false,
        
        tasks: [],
        taskForm: {
            titulo: '',
            descricao: '',
            status: 'pendente',
            prioridade: 'media',
            data_limite: ''
        },
        editingTask: null,
        isCreateFormVisible: false,
        
        filters: {
            status: '',
            prioridade: '',
            search: ''
        }
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
    },
    
    methods: {
        async login() {
            this.loading = true;
            try {
                const response = await axios.post('/login', this.loginForm);
                this.setAuthData(response.data);
                this.loadTasks();
            } catch (error) {
                this.showError('Erro no login: ' + this.getErrorMessage(error));
            } finally {
                this.loading = false;
            }
        },
        
        async register() {
            this.loading = true;
            try {
                const response = await axios.post('/register', this.registerForm);
                this.setAuthData(response.data);
                this.loadTasks();
            } catch (error) {
                this.showError('Erro no registro: ' + this.getErrorMessage(error));
            } finally {
                this.loading = false;
            }
        },
        
        async logout() {
            try {
                await axios.post('/logout');
            } catch (error) {
                console.error('Erro no logout:', error);
            } finally {
                this.clearAuthData();
            }
        },
        
        checkAuth() {
            const token = localStorage.getItem('jwt_token');
            const user = localStorage.getItem('user');
            
            if (token && user) {
                try {
                    const parsedUser = JSON.parse(user);
                    if (parsedUser && parsedUser.name) {
                        this.isAuthenticated = true;
                        this.user = parsedUser;
                        this.loadTasks();
                    } else {
                        this.clearAuthData();
                    }
                } catch (error) {
                    console.error('Erro ao parsear user do localStorage:', error);
                    this.clearAuthData();
                }
            }
        },
        
        setAuthData(data) {
            if (data && data.access_token && data.user && data.user.name) {
                localStorage.setItem('jwt_token', data.access_token);
                localStorage.setItem('user', JSON.stringify(data.user));
                this.isAuthenticated = true;
                this.user = data.user;
            } else {
                this.showError('Dados de autenticação inválidos');
            }
        },
        
        clearAuthData() {
            localStorage.removeItem('jwt_token');
            localStorage.removeItem('user');
            this.isAuthenticated = false;
            this.user = null;
            this.tasks = [];
        },
        
        async loadTasks() {
            this.loading = true;
            try {
                const response = await axios.get('/tarefas');
                this.tasks = response.data;
            } catch (error) {
                this.showError('Erro ao carregar tarefas: ' + this.getErrorMessage(error));
            } finally {
                this.loading = false;
            }
        },
        
        showCreateForm() {
            this.editingTask = null;
            this.resetTaskForm();
            this.isCreateFormVisible = true;
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
            this.isCreateFormVisible = true;
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
                    this.showSuccess('Tarefa atualizada com sucesso!');
                } else {
                    await axios.post('/tarefas', this.taskForm);
                    this.showSuccess('Tarefa criada com sucesso!');
                }
                
                this.loadTasks();
                this.closeModal();
            } catch (error) {
                this.showError('Erro ao salvar tarefa: ' + this.getErrorMessage(error));
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
                this.showSuccess('Tarefa excluída com sucesso!');
                this.loadTasks();
            } catch (error) {
                this.showError('Erro ao excluir tarefa: ' + this.getErrorMessage(error));
            }
        },
        
        exportTasks() {
            const link = document.createElement('a');
            link.href = 'http://127.0.0.1:8000/tarefas/exportar';
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            link.remove();
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
            this.isCreateFormVisible = false;
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
        
        getErrorMessage(error) {
            if (error.response && error.response.data) {
                if (typeof error.response.data === 'string') {
                    return error.response.data;
                }
                if (error.response.data.error) {
                    return error.response.data.error;
                }
                if (error.response.data.message) {
                    return error.response.data.message;
                }
                if (error.response.data.errors) {
                    return Object.values(error.response.data.errors).flat().join(', ');
                }
            }
            return error.message || 'Erro desconhecido';
        },
        
        showSuccess(message) {
            alert(message);
        },
        
        showError(message) {
            alert('Erro: ' + message);
        }
    }
});