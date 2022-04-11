<?php

namespace App\Http\Controllers\v1\Subscription;


use App\Helpers\SubscriptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreAbogado;
use App\Http\Requests\Subscription\Update;
use App\Mail\SubscriptionResult;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;

class SubscriptionPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->middleware('can:subscription.index')->only('index');
        $this->middleware('can:subscription.store')->only('store');
        $this->middleware('can:subscription.show')->only('show');
        $this->middleware('can:subscription.cancel')->only(['renewal_cancel', 'cancel']);
        $this->middleware('can:subscription.current_subscription')->only('current_subscription');
        $this->middleware('can:subscription.processing_payment')->only('processing_payment');
    }

    public function index()
    {
        $data = Subscription::filters()->withDataAll()->get();
        return $this->showAll($data);
    }
    public function store(StoreAbogado $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);
        $data = Subscription::create($request->input()); //Guardamos los datos

        $this->processing_payment($data);
        return $this->showMessage("Datos registrados correctamente", $data->fresh()->withData(), 201);
    }

    public function show(Subscription $subscription)
    {
        return $this->showOne($subscription->withData());
    }

    public function renewal_cancel(Subscription $subscription)
    {
        if ($subscription && $subscription->status == 'active') {
            $subscription->update(['renewal_cancel_at' => now()]);
            return $this->showMessage("Suscripción cancelada correctamente. Puede disfrutar de nuestros servicios hasta " . $subscription->end_date, $subscription->fresh()->withData(), 201);
        } else {
            return $this->errorResponse("No cuenta con una suscripción en curso", null, 400);
        }
    }
    public function cancel(Subscription $subscription)
    {
        $subscription->update(['renewal_cancel_at' => now(), 'end_at' => now(), 'status' => 'canceled', 'renewal' => 0]);
        return $this->showMessage("Suscripción cancelada correctamente.", $subscription->fresh()->withData(), 201);
    }
    public function processing_payment(Subscription $subscription)
    {
        if ($subscription->status != 'procesing')
            return $this->errorResponse("El estado de la suscripción es " . $subscription->status . ". Solo se procesan los pagos de una suscripción con estado 'en Proceso'.", null, 400);

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
        Mail::to($subscription->user->email)->send(new SubscriptionResult($subscription->fresh()->withData()));
        return $subscription->fresh();
    }
}
