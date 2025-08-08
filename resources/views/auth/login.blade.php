@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
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
                    <a href="/cadastro" class="btn btn-outline-secondary btn-sm">
                        Criar nova conta
                    </a>
                </div>
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
        loginForm: {
            email: '',
            password: ''
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
                if (setAuthData(response.data)) {
                    window.location.href = '/tarefas';
                }
            } catch (error) {
                showError('Erro no login: ' + getErrorMessage(error));
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
