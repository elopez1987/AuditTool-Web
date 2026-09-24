@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear nuevo GPS
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('gps-devices')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('gps-devices.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('gps-devices.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                       <div class="row">
                           <x-select2
                                   name="centerid"
                                   label="Centros de distribución"
                                   :options="$centers"
                                   valueField="centerid"
                                   textField="name"
                                   :required="true"
                           />
                           <x-text-input
                                   name="imei"
                                   label="Número de imei"
                                   required
                                   uppercase
                                   colClass="col-lg-2 col-md-12"
                           />
                       </div>
                        <div class="row">
                            <x-text-input
                                    name="model"
                                    label="Modelo"
                                    required
                                    uppercase
                                    colClass="col-lg-2 col-md-12"
                            />
                            <x-text-input
                                    name="sim_number"
                                    label="SIM"
                                    required
                                    uppercase
                                    colClass="col-lg-2 col-md-12"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

