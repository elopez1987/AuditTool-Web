@extends('layouts.app')

@section('bread')

@stop

@section('content')
    <div class="error-box">
        <div class="error-body text-center">
            <h1 class="error-title text-danger">403</h1>
            <h3 class="text-uppercase error-subtitle text-danger">ERROR!</h3>
            <p class=" mt-4 mb-4 font-18 text-muted">TU NO TIENES PERMISO DE ENTRAR A ESTE MODULO.</p>
            <a href="{{route('home')}}" class="btn btn-danger btn-rounded waves-effect waves-light mb-5">REGRESAR AL INICIO</a> </div>
    </div>
@endsection
