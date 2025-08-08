<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\User;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-users {--empresa= : Filtrar por ID da empresa}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar empresas e usuários do sistema';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== Sistema de Tarefas Multiempresa ===');
        $this->info('Listagem de Empresas e Usuários');
        $this->newLine();

        // Listar empresas
        $this->info('📋 EMPRESAS CADASTRADAS');
        $this->info('----------------------');

        $empresas = Empresa::all();
        
        if ($empresas->isEmpty()) {
            $this->warn('Nenhuma empresa cadastrada.');
        } else {
            $headers = ['ID', 'Nome', 'Identificador', 'Usuários', 'Criada em'];
            $rows = [];

            foreach ($empresas as $empresa) {
                $userCount = $empresa->users()->count();
                $rows[] = [
                    $empresa->id,
                    $empresa->nome,
                    $empresa->identificador,
                    $userCount,
                    $empresa->created_at->format('d/m/Y H:i')
                ];
            }

            $this->table($headers, $rows);
        }

        $this->newLine();

        // Listar usuários
        $this->info('👤 USUÁRIOS CADASTRADOS');
        $this->info('----------------------');

        $empresaId = $this->option('empresa');
        
        if ($empresaId) {
            $empresa = Empresa::find($empresaId);
            if (!$empresa) {
                $this->error("Empresa com ID {$empresaId} não encontrada!");
                return 1;
            }
            $users = $empresa->users;
            $this->info("Filtrando usuários da empresa: {$empresa->nome}");
        } else {
            $users = User::with('empresa')->get();
        }

        if ($users->isEmpty()) {
            $this->warn('Nenhum usuário cadastrado.');
        } else {
            $headers = ['ID', 'Nome', 'E-mail', 'Empresa', 'Criado em'];
            $rows = [];

            foreach ($users as $user) {
                $empresaNome = $user->empresa ? $user->empresa->nome : 'N/A';
                $rows[] = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $empresaNome,
                    $user->created_at->format('d/m/Y H:i')
                ];
            }

            $this->table($headers, $rows);
        }

        $this->newLine();
        $this->info('📊 RESUMO');
        $this->info('---------');
        $this->line("Total de empresas: " . Empresa::count());
        $this->line("Total de usuários: " . User::count());

        return 0;
    }
}
