@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar configuración
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('edi-configurations')}}">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('edi-configurations.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('edi-configurations.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Configuration.EdiConfig.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

