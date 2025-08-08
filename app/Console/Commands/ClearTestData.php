<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Tarefa;

class ClearTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-test-data {--force : Forçar limpeza sem confirmação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpar todos os dados de teste do sistema (empresas, usuários e tarefas)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== Sistema de Tarefas Multiempresa ===');
        $this->info('Limpeza de Dados de Teste');
        $this->newLine();

        // Verificar se há dados para limpar
        $empresaCount = Empresa::count();
        $userCount = User::count();
        $tarefaCount = Tarefa::count();

        if ($empresaCount === 0 && $userCount === 0 && $tarefaCount === 0) {
            $this->warn('Não há dados para limpar.');
            return 0;
        }

        $this->warn('⚠️  ATENÇÃO: Esta operação irá remover TODOS os dados do sistema!');
        $this->newLine();
        $this->info('Dados que serão removidos:');
        $this->line("• {$empresaCount} empresa(s)");
        $this->line("• {$userCount} usuário(s)");
        $this->line("• {$tarefaCount} tarefa(s)");
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('Tem certeza que deseja continuar?')) {
                $this->info('Operação cancelada.');
                return 0;
            }

            if (!$this->confirm('Esta ação é irreversível. Confirma novamente?')) {
                $this->info('Operação cancelada.');
                return 0;
            }
        }

        try {
            $this->info('Iniciando limpeza dos dados...');

            // Limpar tarefas primeiro (devido às foreign keys)
            if ($tarefaCount > 0) {
                $this->info('Removendo tarefas...');
                Tarefa::truncate();
                $this->info("✅ {$tarefaCount} tarefa(s) removida(s)");
            }

            // Limpar usuários
            if ($userCount > 0) {
                $this->info('Removendo usuários...');
                User::truncate();
                $this->info("✅ {$userCount} usuário(s) removido(s)");
            }

            // Limpar empresas
            if ($empresaCount > 0) {
                $this->info('Removendo empresas...');
                Empresa::truncate();
                $this->info("✅ {$empresaCount} empresa(s) removida(s)");
            }

            $this->newLine();
            $this->info('🎉 LIMPEZA CONCLUÍDA!');
            $this->info('===================');
            $this->line('Todos os dados foram removidos com sucesso.');
            $this->newLine();
            $this->info('Para criar novos dados, execute:');
            $this->line('php artisan app:create-first-user');

            return 0;

        } catch (\Exception $e) {
            $this->error('Erro ao limpar dados: ' . $e->getMessage());
            return 1;
        }
    }
}
