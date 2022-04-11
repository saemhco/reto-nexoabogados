@extends('email.layout')
@section('content')
<p>Su pago fue rechazado.</p>
<p style="margin:0;">
    @if($data["failed_subscription_attempts"]==1)
    El proceso de suscripción al plan <span>{{$data["plan"]->name}}</span> ha fallado. Volveremos a intertar mañana.
    @elseif($data["failed_subscription_attempts"]==2)
    El proceso de suscripción al plan <span>{{$data["plan"]->name}}</span> ha fallado por segunda vez. Volveremos a intertar mañana.
    @else
    El proceso de suscripción al plan <span>{{$data["plan"]->name}}</span> ha vuelto ha fallar. Volveremos a intertar mañana.
    @endif
</p>
<div align="center">
    <img src="https://image.shutterstock.com/image-vector/transaction-failed-terminal-contactless-payment-260nw-1543996874.jpg" class="img-small">
</div>

@endsection