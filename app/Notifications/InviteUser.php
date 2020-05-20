<?php

namespace App\Notifications;

use App\Models\Campaign\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteUser extends Notification
{
    use Queueable;

    /** @var string */
    private $campaign;
    /** @var string */
    private $inviter;
    /** @var string */
    private $link;
    /** @var bool */
    private $newUser;

    /**
     * Create a new notification instance.
     *
     * @param string $campaign
     * @param string $inviter
     * @param string $link
     * @param bool $newUser
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hi there!')
                    ->line("You've been invited to the campaign $this->campaign by $this->inviter")
                    ->action($this->newUser ? 'Register' : 'Go to campaign', $this->link)
                    ->line('Have fun!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
