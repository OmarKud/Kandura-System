<?php

namespace App\Notifications;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class DesignCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $design;
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $now = now();

        return [
            'type' => 'design created',
            'title' => " New Design Created",
            'body' => "A new design has been created by a user. Tap to review it in the design list go to design list",
            'action' => [
                'type' => 'go_to_design_details',
                'params' => ['design_id' => $this->design->id]
            ],
            'date' => $now->toDateString(),
            'time' => $now->format('H:i'),

        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
