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
                <a class="navbar-brand" href="/">
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
            @yield('content')
        </div>


    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Vue.js -->
    <script src="https://unpkg.com/vue@2/dist/vue.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script src="{{ asset('js/auth.js') }}"></script>
    @yield('scripts')
</body>
</html>
