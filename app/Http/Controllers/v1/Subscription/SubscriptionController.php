<?php

namespace App\Http\Controllers\v1\Subscription;


use App\Helpers\SubscriptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreAbogado;
use App\Http\Requests\Subscription\Update;
use App\Mail\SubscriptionResult;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->middleware('can:subscription.store')->only('store');
        $this->middleware('can:subscription.update')->only('update');
        $this->middleware('can:subscription.renewal_cancel')->only('renewal_cancel');
        $this->middleware('can:subscription.current_subscription')->only('current_subscription');
    }


    public function store(StoreAbogado $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);
        $subscription = Subscription::create($request->input()); //Guardamos los datos

        $this->processing_payment($subscription->fresh());
        return $this->showMessage("Datos registrados correctamente", $subscription->fresh()->withData(), 201);
    }

    public function update(StoreAbogado $request)
    {
        //cancelamos todos las suscripciones
        $subscription = Subscription::where('user_id', auth()->user()->id)->whereIn('status', ['active', 'procesing'])->first();
        if ($subscription) {
            $subscription->update(['status' => 'canceled', 'end_at' => now()]);
        }

        //creamos la nueva suscripcion
        return $this->store($request);
    }
    public function renewal_cancel()
    {
        $subscription = Subscription::where('user_id', auth()->user()->id)->latest()->first();
        if ($subscription && $subscription->status == 'active') {
            $subscription->update(['renewal_cancel_at' => now(), 'renewal' => 0]);
            return $this->showMessage("Suscripción cancelada correctamente. Puede disfrutar de nuestros servicios hasta " . $subscription->end_date, $subscription->fresh()->withData(), 201);
        } else {
            return $this->errorResponse("No cuenta con una suscripción en curso", null, 400);
        }
    }
    private function processing_payment(Subscription $subscription)
    {
        if ($subscription->status != 'procesing')
            return $this->errorResponse("El estado de suscripción es " . $subscription->status . ". Solo se procesan los pagos de una suscripción con estado 'en Proceso'.", null, 400);

        $result = SubscriptionHelper::payment_process(); //Procesamos el pago
        if ($result) {
            SubscriptionHelper::period($subscription); //Calculamos el perido de la suscripción, según la frecuencia y actualizamos los datos
            $subscription->update([
                'status' => 'active',
                'end_at' => null
            ]); //Si el pago fue exitoso, actualizamos el estado de la suscripción
        } else {
            $failed_subscription_attempts = (int) $subscription->failed_subscription_attempts + 1;
            $subscription->update([
                'status' => $failed_subscription_attempts > 3 ? 'canceled' : 'procesing', //Si el pago no fue exitoso, actualizamos el estado de la suscripción
                'failed_subscription_attempts' => $failed_subscription_attempts, //Aumentamos el contador de intentos fallidos
                'end_at' => $failed_subscription_attempts > 3 ? date('Y-m-d H:i:s') : null // Si el pago no fue exitoso, actualizamos la fecha de finalización de la suscripción al cuarto intento
            ]);
        }
        Mail::to(auth()->user()->email)->send(new SubscriptionResult($subscription->fresh()->withData()));
        return $subscription->fresh();
    }
    public function current_subscription()
    {
        $subscription = Subscription::where('user_id', auth()->user()->id)->latest()->firstOrFail();
        return $this->showOne($subscription->withData());
    }
}
