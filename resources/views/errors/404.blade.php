@extends('layouts.app')

@section('bread')

@stop

@section('content')
    <div class="error-box">
        <div class="error-body text-center">
            <h1 class="error-title text-danger">404</h1>
            <h3 class="text-uppercase error-subtitle">PAGINA NO ENCONTRADA !</h3>
            <p class="text-muted mt-4 mb-4">PARECES ESTAR TRATANDO DE ENCONTRAR SU CAMINO A CASA</p>
            <a href="{{route('home')}}" class="btn btn-danger btn-rounded waves-effect waves-light mb-5">REGRESAR AL INICIO</a> </div>
    </div>
@endsection
