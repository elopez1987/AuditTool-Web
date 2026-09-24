@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Registro
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('gps-devices/' . $gps_device->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('gps-devices.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <x-text-input
                                    name="imei"
                                    label="Número de imei"
                                    required
                                    uppercase
                                    :value="$gps_device->imei"
                                    colClass="col-lg-2 col-md-12"
                            />
                        </div>
                        <div class="row">
                            <x-text-input
                                    name="model"
                                    label="Modelo"
                                    required
                                    uppercase
                                    :value="$gps_device->model"
                                    colClass="col-lg-2 col-md-12"
                            />
                            <x-text-input
                                    name="sim_number"
                                    label="SIM"
                                    required
                                    uppercase
                                    :value="$gps_device->sim_number"
                                    colClass="col-lg-2 col-md-12"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

