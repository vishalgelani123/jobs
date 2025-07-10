<?php

namespace App\Notifications;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobApplication;

class StatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $job = Job::find($this->application->job_id);
        return (new MailMessage)
            ->subject('Application Status Updated')
            ->line('Your application for ' . $job->title . ' has been updated.')
            ->line('New Status: ' . $this->application->status)
            ->line('Thank you for using our platform!');
    }
}