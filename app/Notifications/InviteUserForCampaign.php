<?php

namespace App\Notifications;

use App\Models\Campaign\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteUserForCampaign extends Notification
{
    use Queueable;

    private string $campaign;
    private string $inviter;
    private string $link;
    private bool $newUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $campaignId, string $inviter, string $link, bool $newUser)
    {
        $this->campaign = Campaign::find($campaignId)->name;
        $this->inviter = $inviter;
        $this->link = $link;
        $this->newUser = $newUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("You've been invited for a D&D Campaign!")
            ->greeting('Hi there!')
            ->line("You've been invited to the campaign $this->campaign by $this->inviter")
            ->action($this->newUser ? 'Register' : 'Go to campaign', $this->link)
            ->line('Have fun!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
