<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Tarefa;
use App\Notifications\TarefaCriada;
use App\Notifications\TarefaConcluida;

class SendTaskNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tarefa;

    public function __construct(Tarefa $tarefa)
    {
        $this->tarefa = $tarefa;
    }

    public function handle()
    {
        $user = $this->tarefa->user;

        // Se não achar usuário, aborta para evitar erro
        if (!$user) {
            return;
        }

        if ($this->tarefa->status === 'concluida') {
            $user->notify(new TarefaConcluida($this->tarefa));
        } else {
            $user->notify(new TarefaCriada($this->tarefa));
        }
    }
}

