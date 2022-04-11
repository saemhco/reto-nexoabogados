<?php

namespace App\Jobs;

use App\Helpers\SubscriptionHelper;
use App\Mail\SubscriptionResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessingPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscription;
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->subscription->status == 'active')
            return true;

        if ($this->subscription->status != 'procesing')
            return response()->json(['message' => "El estado de suscripción es " . $this->subscription->status . ". Solo se procesan los pagos de una suscripción con estado 'en Proceso'.", 'errors' => null, 'code' => 400], 400);

        $result = SubscriptionHelper::payment_process(); //Procesamos el pago
        if ($result) {
            SubscriptionHelper::period($this->subscription); //Calculamos el perido de la suscripción, según la frecuencia y actualizamos los datos
            $this->subscription->update([
                'status' => 'active',
                'end_at' => null
            ]); //Si el pago fue exitoso, actualizamos el estado de la suscripción
        } else {
            $failed_subscription_attempts = (int) $this->subscription->failed_subscription_attempts + 1;
            $this->subscription->update([
                'status' => $failed_subscription_attempts > 3 ? 'canceled' : 'procesing', //Si el pago no fue exitoso, actualizamos el estado de la suscripción
                'failed_subscription_attempts' => $failed_subscription_attempts, //Aumentamos el contador de intentos fallidos
                'end_at' => $failed_subscription_attempts > 3 ? date('Y-m-d H:i:s') : null // Si el pago no fue exitoso, actualizamos la fecha de finalización de la suscripción al cuarto intento
            ]);
        }
        Mail::to(auth()->user()->email)->send(new SubscriptionResult($this->subscription->fresh()->withData()));
        if ($result)
            return true;
        else
            return false;
    }
}
