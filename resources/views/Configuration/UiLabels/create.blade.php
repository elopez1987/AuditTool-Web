@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar diccionario
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('label-dictionary')}}">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('label-dictionary.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('label-dictionary.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Configuration.UiLabels.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.Partials.Errors')

@endsection

@section('script')

@endsection

