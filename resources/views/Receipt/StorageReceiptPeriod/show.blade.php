@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Periodos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menureceipt')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Periodo</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus</label>
                                    <select name="statusid" id="statusid" class="form-control select2">
                                        <option value="" selected>Selecione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-10">
                            <table id="table-receiptperiods"
                                   class="table table-bordered table-condensed table-hover tablegeneral">
                                <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha de trabajo</th>
                                    <th>Secuencia</th>
                                    <th>Recibo</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($detalle as $item)
                                    <tr>
                                        <td>{{$item->period ? $item->period->name : ''}}</td>
                                        <td>{{$item->workday}}</td>
                                        <td>{{$item->statussecuence ? $item->statussecuence->name : ''}}</td>
                                        <td>{{$item->receipt ? $item->receipt->shipmentid : ''}}</td>
                                        <td>{{$item->status ? $item->status->name : ''}}</td>
                                        <td>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection
