<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class sendCredentials extends Notification
{
    use Queueable;

    public $admin_mail,$admin_pass,$website_url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($admin_mail,$admin_pass,$website_url)
    {
        //
        $this->admin_mail=$admin_mail;
        $this->admin_pass=$admin_pass;
        $this->website_url=$website_url;
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
                    ->line(new HtmlString('You have just created your online store using Storola!<br>
                    Please use the following credentials and links to login or access your store admin panel.'))
                    ->line(new HtmlString("<div style='padding:10px 10px;background:#f15d1e;color:white'>
                                             Store Credentials:<br>
                                             <strong>Admin Email: </strong>".$this->admin_mail."<br>
                                             <strong>Admin Password: </strong>".$this->admin_pass."
                                            </div>"                                     
                                        ))
                    ->line(new HtmlString("<div style='margin-top:10px;padding:10px 10px;background:white;'>
                                             Store Links:<br>
                                             <strong>Store url: </strong>".$this->website_url."<br>
                                             <strong>Admin panel: </strong>".$this->website_url."/admin
                                            </div><br>"                                     
                                        ))
                    ->line(new HtmlString('Your website is ready for the trial period under the subdomain of <b>storola.net</b> Your own domain can be used after like: <b>www.yourdomain.com.</b><br><br>'))
                    ->line(new HtmlString('Please be notified that you are using <b>Monthly Basic</b> package.If you do not complete payment within <b>One Month</b> your store will be removed automatically.<br><br>'))
                    ->line(new HtmlString("If you need further assistance you can contact us <a href='".route('page', 'contact')."' target='blank'>From here</a><br><br>"))
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
