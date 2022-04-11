<?php

namespace App\Helpers;

use App\Http\Requests\Subscription\Store;
use App\Models\Subscription;

class SubscriptionHelper
{
    static function period(Subscription $subscription)
    {
        $data = ['start_date' => date('Y-m-d H:i:s')];
        if ($subscription->frecuency == 'Anual')
            $data['end_date'] = date('Y-m-d H:i:s', strtotime('+1 year'));
        else
            $data['end_date'] = date('Y-m-d H:i:s', strtotime('+1 month'));
        $subscription->update($data);
        return $subscription->fresh();
    }
    static function payment_process()
    {
        return  (bool) mt_rand(0, 1);
    }
}
