<?php

namespace Pterodactyl\Notifications;

use Pterodactyl\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Newsletter extends Notification
{
    /**
     * @var \Pterodactyl\Models\User
     */
    private $user;

    public function __construct(User $user, String $subject, String $body, String $urltext, String $urllink)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->body = $body;
        $this->urltext = $urltext;
        $this->urllink = $urllink;
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail()
    {
        $message = (new MailMessage())
          ->subject($this->subject)
          ->greeting('Hello ' . $this->user->name . '!');
          
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $this->body) as $line){
            $message->line($line);
        } 
        if ($this->urltext != "none") {
            $message->action($this->urltext, $this->urllink);
        }
        return $message;
    }
}
