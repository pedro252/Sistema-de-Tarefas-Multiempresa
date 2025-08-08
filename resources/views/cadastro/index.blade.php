@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
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
                        <a href="/" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
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
        registerForm: {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            empresa_nome: '',
            empresa_identificador: ''
        }
    },
    
    mounted() {
        this.checkAuth();
    },
    
    methods: {
        async register() {
            this.loading = true;
            try {
                const response = await axios.post('/register', this.registerForm);
                if (setAuthData(response.data)) {
                    showSuccess('Conta criada com sucesso!');
                    window.location.href = '/tarefas';
                }
            } catch (error) {
                showError('Erro no registro: ' + getErrorMessage(error));
            } finally {
                this.loading = false;
            }
        },
        
        checkAuth() {
            const user = checkAuth();
            if (user) {
                this.isAuthenticated = true;
                this.user = user;
                window.location.href = '/tarefas';
            }
        }
    }
});
</script>
@endsection
