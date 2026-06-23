@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de operaciones de embarques.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
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
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carriers">Linea de transporte </label>
                                    <select name="carrierid[]" id="carrierid" class="form-control select2"
                                            multiple="multiple">
                                        @foreach($carriers as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="customerid">Cliente</label>
                                    <select name="customerid[]" id="customerid" class="form-control select2"
                                            multiple="multiple">
                                        @foreach($customers as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus</label>
                                    <select name="statusid[]" id="statusid" class="form-control select2"
                                            multiple="multiple">
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        @foreach($statusQuantitys as $status)
                            <div class="col-lg-1 col-md-3">
                                <div class="card " style="border: 1px solid #cfdd01;border-radius: 8px">
                                    <div class="d-flex flex-row">
                                        <div class="p-10">
                                            <h3 class="text-black-50 m-b-0 ">{{$status->total}}</h3>
                                            <span class="text-black">{{$status->status->name}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="mt-3 mb-3">
                    <div class="row">
                        <div class="col-lg-8">
                            <table id="table-monitortrafico" class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Cliente</th>
                                    <th class="bg-inverse text-white">Ciudad</th>
                                    <th class="bg-inverse text-white">Linea de transporte</th>
                                    <th class="bg-inverse text-white">Estatus Almacén</th>
                                    <th class="bg-inverse text-white">Estatus de unidad</th>
                                    <th class="bg-inverse text-white">Comentarios</th>
                                    <th class="bg-inverse text-white">Ingreso de Unidad</th>
                                    <th class="bg-inverse text-white">Confirmación de Llaves</th>
                                    <th class="bg-inverse text-white">Estadia Total</th>
                                    <th class="bg-inverse text-white">Cita de entrega</th>
                                    <th class="bg-inverse text-white">Maxima de Salida</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-lg-4">
                            <table class="table table-bordered table-condensed table-hover tablegeneral"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Embarque</th>
                                    <th>Cliente</th>
                                    <th>Estatus</th>
                                    <th>Estadia</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shipmentsStatus as $item)
                                    <tr>
                                        <td>{{$item->shipmentid}}</td>
                                        <td>{{$item->customer ? $item->customer->name : ''}}</td>
                                        <td style="background-color: {{$item->status ? $item->status->auxiliary01 : ''}}">
                                            {{$item->status ? $item->status->name : ''}}
                                        </td>
                                        <td>
                                            {{$item->folio ? $item->folio->staytime : ''}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr class="mt-3 mb-3">

                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table table-bordered table-condensed table-hover tablegeneral"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Fecha de trabajo</th>
                                    <th>Estatus de transporte</th>
                                    <th>Programado</th>
                                    <th>Preparando</th>
                                    <th>Preparado en piso</th>
                                    <th>Por Cargar</th>
                                    <th>Cargando</th>
                                    <th>Cargado</th>
                                    <th>Doc. Entregada a FAC</th>
                                    <th>Doc. Entregado</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($monitorStatus as $item)
                                    <tr>
                                        <td>{{$item->workday}}</td>
                                        <td>{{$item->statustransporte}}</td>
                                        <td>{{$item->PROGRAMADO}}</td>
                                        <td>{{$item->PREPARANDO}}</td>
                                        <td>{{$item->PREPARADO_EN_PISO}}</td>
                                        <td>{{$item->POR_CARGAR}}</td>
                                        <td>{{$item->CARGANDO}}</td>
                                        <td>{{$item->CARGADO}}</td>
                                        <td>{{$item->DOC_ENTREGADA_A_FAC}}</td>
                                        <td>{{$item->DOC_ENTREGADO}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table class="table table-bordered  table-condensed table-hover tablegeneral"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Fecha de trabajo</th>
                                    <th>Estatus del embarque</th>
                                    <th>Total de embarques</th>
                                    <th>Total picking</th>
                                    <th>Total pallets</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($totalPallets as $item)
                                    <tr>
                                        <th>{{$item->workday}}</th>
                                        <th>{{$item->statusname}}</th>
                                        <th>{{$item->totalembarques}}</th>
                                        <th>{{$item->picking}}</th>
                                        <th>{{$item->totalcontainers}}</th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="card-footer">
                    Control de patios.
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('Shipments.MonitorShipments.MonitorOperation.table')
    <script>
        function reloadtable() {
            $('#table-monitortrafico').DataTable().ajax.reload();
        }
    </script>
    <script>
        var oTable = $(".tablegeneral").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "serverSide": false,
            "sScrollY": "450px",
            'colReorder': true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[30, 50, 100, 150, 200, 250, -1], [30, 50, 100, 150, 200, 250, "Todos"]],

            buttons: [
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Excel</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                }
            ],


            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    </script>
@endsection
