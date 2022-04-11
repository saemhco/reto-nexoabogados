<?php

namespace App\Http\Requests\Subscription;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class StoreAbogado extends FormRequest
{
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend(
            'is_user_subscribed',
            function ($attribute, $value, $parameters, $validator) {
                return !Subscription::where('user_id', auth()->user()->id)->where('status', 'active')->exists();
            },
            "El usuario ya cuenta con una suscripción activa. Puede migrar a otro plan."
        );
        $validationFactory->extend(
            'is_user_processing',
            function ($attribute, $value, $parameters, $validator) {
                return !Subscription::where('user_id', auth()->user()->id)->whereIn('status', ['active', 'procesing'])->exists();
            },
            "El usuario ya cuenta con una suscripción en proceso. Pronto nos contactaremos con Ud."
        );
    }

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'plan_id'   => 'required|integer|exists:plans,id', //|is_user_subscribed|is_user_processing',
            'frecuency' => 'required|string|in:Mensual,Anual',
        ];
    }
}
