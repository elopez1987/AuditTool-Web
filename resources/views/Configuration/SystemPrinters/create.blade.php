@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de impresoras - Crear
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('systemprinters')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('systemprinters.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('systemprinters.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-2">
                        @include('Configuration.SystemPrinters.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

