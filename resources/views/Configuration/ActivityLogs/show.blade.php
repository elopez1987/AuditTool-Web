@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle del evento
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{route('activitylog.index')}}">
                              @include('buttons.cancel')
                            </a>
                        </div>
                        <br>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for=""> <span class="font-bold font-12">Descripción :</span> {{$register->description}}</label>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for=""><span class="font-bold font-12">Modelo :</span> {{$register->subject_type}}</label>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for=""><span class="font-bold font-12">Modelo ID :</span> {{$register->subject_id}}</label>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for=""><span class="font-bold font-12">Usuario :</span> {{$register->user->name}}</label>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-4">
                            <span class="text-success">Datos Originales</span>
                            <table class="table table-bordered table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Valor</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($jsonObject->old))
                                    @foreach($jsonObject->old as $key=>$value )
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td>{{$value}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="col-4">
                            <span class="text-danger">Datos Modificados</span>
                            <table class="table table-bordered table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Valor</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($jsonObject->attributes))
                                    @foreach($jsonObject->attributes as $key=>$value )
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td>{{$value}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('css')

@endsection


@section('script')

@endsection
