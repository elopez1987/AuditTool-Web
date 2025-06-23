@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Registro
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('systemwhatsapps')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a >
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('systemwhatsapps.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Configuration.SystemWhatsApps.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

