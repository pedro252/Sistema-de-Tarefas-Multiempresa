<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tarefa;
use Illuminate\Queue\SerializesModels;

class TarefaConcluida extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

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
            ->subject('Tarefa concluída')
            ->greeting('Olá ' . $notifiable->name)
            ->line('A tarefa "' . $this->tarefa->titulo . '" foi concluída.')
            ->action('Ver Tarefa', url('/tarefas/' . $this->tarefa->id))
            ->line('Obrigado por usar nossa aplicação!');
    }

    public function toArray($notifiable)
    {
        return [
            'tarefa_id' => $this->tarefa->id,
            'status' => 'concluída',
        ];
    }
}
