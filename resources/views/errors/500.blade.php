@extends('layouts.app')

@section('bread')

@stop

@section('content')
    <div class="error-box">
        <div class="error-body text-center">
            <h1 class="error-title text-info">500</h1>
            <h3 class="text-uppercase error-subtitle"> ERROR INTERNO DEL SERVIDOR!</h3>
            <p class="text-muted mt-4 mb-4" > POR FAVOR INTENTE MAS TARDE</p>
            <a href="" class="btn btn-danger btn-rounded waves-effect waves-light mb-5"> IR AL INICIO </a> </div>
    </div>
@endsection
