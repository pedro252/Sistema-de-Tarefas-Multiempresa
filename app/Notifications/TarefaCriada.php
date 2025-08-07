<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;  // IMPORTANTE
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tarefa;

class TarefaCriada extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tarefa;

    public function __construct(Tarefa $tarefa)
    {
        $this->tarefa = $tarefa;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nova tarefa criada')
            ->greeting('Olá ' . $notifiable->name)
            ->line('Uma nova tarefa foi criada: "' . $this->tarefa->titulo . '".')
            ->action('Ver Tarefa', url('/tarefas/' . $this->tarefa->id))
            ->line('Obrigado por usar nossa aplicação!');
    }

    public function toArray($notifiable)
    {
        return [
            'tarefa_id' => $this->tarefa->id,
            'status' => $this->tarefa->status,
        ];
    }
}
