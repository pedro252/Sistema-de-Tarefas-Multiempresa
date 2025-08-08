<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateFirstUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-first-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar a primeira empresa e usuário do sistema interativamente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== Sistema de Tarefas Multiempresa ===');
        $this->info('Configuração inicial do sistema');
        $this->newLine();

        if (Empresa::count() > 0) {
            $this->warn('Já existem empresas cadastradas no sistema!');
            if (!$this->confirm('Deseja continuar mesmo assim?')) {
                $this->info('Operação cancelada.');
                return 0;
            }
        }

        if (User::count() > 0) {
            $this->warn('Já existem usuários cadastrados no sistema!');
            if (!$this->confirm('Deseja continuar mesmo assim?')) {
                $this->info('Operação cancelada.');
                return 0;
            }
        }

        $this->info('Vamos criar a primeira empresa e usuário administrador.');
        $this->newLine();

        $this->info('📋 DADOS DA EMPRESA');
        $this->info('-------------------');

        $empresaNome = $this->ask('Nome da empresa');
        while (empty($empresaNome)) {
            $this->error('O nome da empresa é obrigatório!');
            $empresaNome = $this->ask('Nome da empresa');
        }

        $empresaIdentificador = $this->ask('Identificador da empresa (código único)');
        while (empty($empresaIdentificador)) {
            $this->error('O identificador da empresa é obrigatório!');
            $empresaIdentificador = $this->ask('Identificador da empresa (código único)');
        }

        if (Empresa::where('identificador', $empresaIdentificador)->exists()) {
            $this->error('Já existe uma empresa com este identificador!');
            return 1;
        }

        $this->newLine();
        $this->info('👤 DADOS DO USUÁRIO ADMINISTRADOR');
        $this->info('----------------------------------');

        $userName = $this->ask('Nome completo do usuário');
        while (empty($userName)) {
            $this->error('O nome do usuário é obrigatório!');
            $userName = $this->ask('Nome completo do usuário');
        }

        $userEmail = $this->ask('E-mail do usuário');
        while (empty($userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $this->error('E-mail inválido!');
            $userEmail = $this->ask('E-mail do usuário');
        }

        if (User::where('email', $userEmail)->exists()) {
            $this->error('Já existe um usuário com este e-mail!');
            return 1;
        }

        $userPassword = $this->secret('Senha do usuário (mínimo 6 caracteres)');
        while (strlen($userPassword) < 6) {
            $this->error('A senha deve ter pelo menos 6 caracteres!');
            $userPassword = $this->secret('Senha do usuário (mínimo 6 caracteres)');
        }

        $userPasswordConfirm = $this->secret('Confirme a senha');
        while ($userPassword !== $userPasswordConfirm) {
            $this->error('As senhas não coincidem!');
            $userPassword = $this->secret('Senha do usuário (mínimo 6 caracteres)');
            $userPasswordConfirm = $this->secret('Confirme a senha');
        }

        $this->newLine();
        $this->info('📝 RESUMO DOS DADOS');
        $this->info('-------------------');
        $this->line("Empresa: {$empresaNome} ({$empresaIdentificador})");
        $this->line("Usuário: {$userName} ({$userEmail})");
        $this->newLine();

        if (!$this->confirm('Confirma a criação da empresa e usuário?')) {
            $this->info('Operação cancelada.');
            return 0;
        }

        try {
            $this->info('Criando empresa...');
            $empresa = Empresa::create([
                'nome' => $empresaNome,
                'identificador' => $empresaIdentificador,
            ]);
            $this->info('✅ Empresa criada com sucesso!');

            $this->info('Criando usuário...');
            $user = User::create([
                'name' => $userName,
                'email' => $userEmail,
                'password' => Hash::make($userPassword),
                'empresa_id' => $empresa->id,
            ]);
            $this->info('✅ Usuário criado com sucesso!');

            $this->newLine();
            $this->info('🎉 CONFIGURAÇÃO CONCLUÍDA!');
            $this->info('========================');
            $this->line("Empresa ID: {$empresa->id}");
            $this->line("Usuário ID: {$user->id}");
            $this->newLine();
            $this->info('Agora você pode fazer login no sistema com:');
            $this->line("E-mail: {$userEmail}");
            $this->line("Senha: [a senha que você digitou]");
            $this->newLine();
            $this->info('Para acessar o sistema, execute:');
            $this->line('php artisan serve');
            $this->line('E acesse: http://localhost:8000');

            return 0;

        } catch (\Exception $e) {
            $this->error('Erro ao criar empresa/usuário: ' . $e->getMessage());
            return 1;
        }
    }
}
