<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class verifyEmail extends Notification
{
    use Queueable;

    public $user;
    public $credentials;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user,$credentials)
    {
        //
        $this->user=$user;
        $this->credentials=base64_encode($credentials);

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
                    ->greeting('Hello Customer!')
                    ->line(new HtmlString('You have just created your online store using Storola!<br>Please confirm your Email to activate Store.'))
                    ->line('You wont receive any credentials without activating store')
                    ->action('Click Here to verify',route('store.user.verification',[$this->user->remember_token,$this->credentials]))
                    ->level('verify')
                    ->line(new HtmlString("Please be notified that if you dont activate your store account within <b style='color:#ee6d31'>24 Hours</b> your store will be removed automatically.<br><br>"))
                    ->line('Wish you a wonderful experience with Storola!');
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
