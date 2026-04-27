@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Total general
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed tablegeneral">
                                <thead>
                                    <tr>
                                        <th>Total de registros</th>
                                        <th>Mesa de control</th>
                                        <th>Enrampe de unidad</th>
                                        <th>Inicio de proceso</th>
                                        <th>Fin de proceso</th>
                                        <th>Autorización de salida</th>
                                        <th>Salida de unidad</th>
                                    </tr>
                                </thead>
                                <tbody>
{{--                                    @foreach($generales as $item)--}}
                                        <tr>
                                            <td>{{$general->total_registros}}</td>
                                            <td>{{$general->total_llegada}}</td>
                                            <td>{{$general->total_enrampe}}</td>
                                            <td>{{$general->total_startdate}}</td>
                                            <td>{{$general->total_finishdate}}</td>
                                            <td>{{$general->total_autorizaoutputdate}}</td>
                                            <td>{{$general->total_outputdate}}</td>
                                        </tr>
{{--                                    @endforeach--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 ">
            <div class="card">
                <div class="card-header">Detalle por dia</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed tablegeneral">
                                <thead>
                                    <tr>
                                        <th>Fecha de Ingreso</th>
                                        <th>Total de registros</th>
                                        <th>Llegada a mesa de control</th>
                                        <th>Enrampe de unidad</th>
                                        <th>Inicio de proceso</th>
                                        <th>Fin de proceso</th>
                                        <th>Autorización de salida</th>
                                        <th>Salida de unidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datos as $item)
                                        <tr>
                                            <td>{{$item->fecha}}</td>
                                            <td>{{$item->total_registros}}</td>
                                            <td>{{$item->total_deliverdate}}</td>
                                            <td>{{$item->total_ensnare}}</td>
                                            <td>{{$item->total_startdate}}</td>
                                            <td>{{$item->total_finishdate}}</td>
                                            <td>{{$item->total_autorizaoutputdate}}</td>
                                            <td>{{$item->total_outputdate}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Control de patios / Cajas en patio / Existencia
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection


@section('script')
    @include('layouts.Partials.DataTable')
@endsection
