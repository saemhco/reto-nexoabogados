<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionResult extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $subject = "Subscription Result";
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        switch ($this->data['status']) {
            case 'active':
                $this->subject = 'PAGO EXITOSO. SUSCRIPCIÓN ACTIVADA';
                $view = 'email.subscription-success';
                break;
            case 'canceled':
                $this->subject = 'SUSCRIPCIÓN CANCELADA';
                $view = 'email.subscription-canceled';
                break;
            default:
                $this->subject  = 'SUSCRIPCIÓN FALLIDA';
                $view           = 'email.subscription-failed';
                break;
        }
        return $this->from('noreply@nexoabogados.net')
            ->view($view, ['data' => $this->data]);
    }
}
