@extends('email.layout')
@section('content')
<p style="margin:0;">
    Se ha cancelado su suscripción. <br>
    @if($data["failed_subscription_attempts"]>3)
    PAGO RECHAZADO. El proceso de suscripción al plan <span>{{$data["plan"]->name}}</span> ha fallado. <br>
    @endif
</p>

<div align="center">
    <img src="https://image.shutterstock.com/image-vector/transaction-failed-terminal-contactless-payment-260nw-1543996874.jpg" class="img-small">
</div>
<small>
    Si cree que se trata de un error pongase en contacto con soporte técnico para resolver el problema.
</small>
@endsection