<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Job;

class JobCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Job Posted: ' . $this->job->title)
            ->line('A new job has been posted: ' . $this->job->title)
            ->line('Thank you for using our platform!');
    }
}
