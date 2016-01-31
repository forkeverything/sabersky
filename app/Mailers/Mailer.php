<?php
namespace App\Mailers;

use Illuminate\Mail\Mailer as Mail;
abstract class Mailer {

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }



    public function sendTo($email, $name, $subject, $view, $data)
    {
        $this->mail->queue($view, $data, function($message) use ($email, $name, $subject){
            $message->from('admin@pusakagroup.com', 'Pusaka Jaya Procurement')
                ->to($email, $name)
                ->subject($subject);
        });
    }

}