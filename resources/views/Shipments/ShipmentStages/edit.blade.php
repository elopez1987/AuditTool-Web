@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body border-2">
                    <form method="POST"
                          action="{{url('shipmentstages/' . $orderdeliverie->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card border-warning">
                                    <div class="card-header ">
                                        Asignación de cortina para el embarque
                                    </div>
                                    <div class="card-body">
                                        <div class="card-input">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="oc">Embarque</label>
                                                        <input type="text" class="form-control" readonly value="{{$orderdeliverie->shipmentid}}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="oc">Cliente</label>
                                                        <input type="text" class="form-control" readonly value="{{$orderdeliverie->customer ? $orderdeliverie->customer->name : ''}}">
                                                    </div>
                                                </div>

                                            </div>
                                            <hr class="hr-line">
                                            <div class="row mt-3">
                                                <div class="col-lg-12">
                                                    <table class="table table-condensed table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="bg-thead-table-monitor " style="color: white !important;" >Opción 01</th>
                                                                <th class="bg-thead-table-monitor " style="color: white !important;">Opción 02</th>
                                                                <th class="bg-thead-table-monitor " style="color: white !important;">Opción 03</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($stages))
                                                                <tr>
                                                                    <td>{{$stages->cortina01}}</td>
                                                                    <td>{{$stages->cortina02}}</td>
                                                                    <td>{{$stages->cortina03}}</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-input">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <p class="card-text mt-3">Seleccione la cortina que sera la primera asignación desde el sistema como cortina preferida.</p>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="locationid01">Cortina Preferida
                                                                <span style="color: red">*</span>
                                                            </label>
                                                            <select name="locationid01" class="form-control select2 @error('locationid01') is-invalid @enderror" required>
                                                                <option value="" selected>Seleccione una opción</option>
                                                                @foreach($locations as $model)
                                                                    <option value="{{$model->id}}" {{ old('locationid01') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                                                                @endforeach
                                                            </select>
                                                            @error('locationid01')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <p>Seleccione la segunda opción de cortina para el enrampe de la unidad si la primera selección se encuentra ocupada.</p>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="locationid02">Segunda opción de cortina
                                                                <span style="color: red">*</span>
                                                            </label>
                                                            <select name="locationid02" class="form-control select2 @error('locationid02') is-invalid @enderror" required>
                                                                <option value="" selected>Seleccione una opción</option>
                                                                @foreach($locations as $model)
                                                                    <option value="{{$model->id}}" {{ old('locationid02') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                                                                @endforeach
                                                            </select>
                                                            @error('locationid02')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <p>Seleccione la tercera opción para el enrampe de la unidad, solo sera usado si las dos anteriores se encuentran ocupadas.</p>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="locationid03">Tercera opción de cortina
                                                                <span style="color: red">*</span>
                                                            </label>
                                                            <select name="locationid03" class="form-control select2 @error('locationid03') is-invalid @enderror" required>
                                                                <option value="" selected>Seleccione una opción</option>
                                                                @foreach($locations as $model)
                                                                    <option value="{{$model->id}}" {{ old('locationid03') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                                                                @endforeach
                                                            </select>
                                                            @error('locationid03')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <hr>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a href="">
                                                    @include('buttons.save')
                                                </a>
                                                <a href="{{route('monitoroperation.index')}}" class="ml-4">
                                                    @include('buttons.cancel')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

