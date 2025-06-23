@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de cita
                </div>
                <div class="card-body">
{{--                    <form method="GET" id="search" role="form">--}}
{{--                        {{csrf_field()}}--}}
                        <div class="row">
                            <div class="col-md-12">
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carriername">Folio de cita </label>
                                    <input type="text" class="form-control" id="carriername" name="carriername"
                                           maxlength="100" readonly
                                           aria-describedby="carriername"
                                           value="{{$reserveschedule->id}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carriername">Linea de transporte </label>
                                    <input type="text" class="form-control" id="carriername" name="carriername"
                                           maxlength="100" readonly
                                           aria-describedby="carriername"
                                           value="{{$reserveschedule->carrier ? $reserveschedule->carrier->name :''}}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <table class="table table-condensed table-bordered table-striped tablegeneral">
                                    <thead>
                                    <tr>
                                        <th>Fecha de entrega</th>
                                        <th>Bloque de entrega</th>
                                        <th>Cantidad</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($details as $detail)
                                        <tr>
                                            <td>{{$detail->workday}}</td>
                                            <td>{{$detail->bloque}}</td>
                                            <td>{{$detail->quantity}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-condensed table-bordered table-striped tablegeneral">
                                    <thead>
                                    <tr>
                                        <th>Embarque</th>
                                        <th>Cliente</th>
                                        <th>Cantidad</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($shipments as $shipment)
                                        <tr>
                                            <td>{{$shipment->shipmentid}}</td>
                                            <td>{{$shipment->customername}}</td>
                                            <td>{{$shipment->quantity}}</td>
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
    {{--    @include('Shipments.ReserveSchedulleInverse.partials.table')--}}
    <script>
        function reloadtable() {
            $('#table-categories').DataTable().ajax.reload();
        }
    </script>
    @include('layouts.Partials.DataTable')
@endsection
