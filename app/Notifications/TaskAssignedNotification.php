<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Task $task;
    protected string $type; 

    public function __construct(Task $task, string $type = 'created')
    {
        $this->task = $task;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $line = $this->type === 'created' ? 'A new task has been assigned to you.' : 'A task assigned to you has been updated.';

        return (new MailMessage)
            ->subject("Task {$this->type}: {$this->task->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line($line)
            ->line("**Title:** {$this->task->title}")
            ->line("**Due Date:** {$this->task->due_date}")
            ->line("**Priority Score:** {$this->task->priority_score}")
            ->action('View Task', url('/tasks/' . $this->task->id)) // Replace with real URL
            ->line('Thank you for using our task manager!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'type' => $this->type,
        ];
    }
}
