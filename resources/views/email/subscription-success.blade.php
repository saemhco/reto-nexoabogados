@extends('email.layout')
@section('content')
<p style="margin:0;">
    Su pago fue exitoso.
    El proceso de suscripción al plan <span>{{$data["plan"]->name}}</span> Se ha realizado con éxito.
    Periodo de suscripción: {{date("d/m/Y",strtotime($data["start_date"]))}} - {{date("d/m/Y",strtotime($data["end_date"]))}}
</p>
<div align="center">
    <img src="https://cdn2.vectorstock.com/i/thumb-large/52/16/contactless-payment-male-hand-holding-credit-vector-14145216.jpg" class="img-small">
</div>
@endsection