@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de cédula
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{url('detailcardscount/' . $inventory->id)}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                                    <span style="font-size: 11px;"> Regresar</span>
                                </button>
                            </a>
                        </div>
                    </div>
                    <hr class="mb-2">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="inventoryid">Número de inventario:</label>
                                <input type="text" class="form-control" readonly value="{{$inventory->id}}"
                                       id="inventoryid" name="inventoryid">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="description">Número de inventario:</label>
                                <input type="text" class="form-control" readonly value="{{$inventory->description}}"
                                       id="description" name="description">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="cardid">Cédula:</label>
                                <input type="text" class="form-control" readonly value="{{$card->cardid}}"
                                       id="cardid" name="cardid">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-striped table-condensed tablegeneral">
                                <thead>
                                <tr>
                                    <th>Area</th>
                                    <th>Pasill0</th>
                                    <th>Cédula</th>
                                    <th>Ubicación</th>
                                    <th>Sku 01</th>
                                    <th>Descripción 01</th>
                                    <th>Cantidad 01</th>
                                    <th>Cantidad de tarimas</th>
                                    <th>Cantidad de restos</th>
                                    <th>Total Conteo 01</th>
                                    <th>Sku 02</th>
                                    <th>Descripción 02</th>
                                    <th>Cantidad 02</th>
                                    <th>Cantidad de tarimas</th>
                                    <th>Cantidad de restos</th>
                                    <th>Total Conteo 01</th>
                                    <th>E.Sku</th>
                                    <th>E.Cantidad</th>
                                    <th>E.general</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($locations as $location)
                                    <tr>
                                        <td>{{$location->areaid}}</td>
                                        <td>{{$location->aisleid}}</td>
                                        <td>{{$location->cardid}}</td>
                                        <td>{{$location->locationid}}</td>
                                        <td>{{$location->partnumber01}}</td>
                                        <td>{{$location->description01}}</td>
                                        <td>{{round($location->quantity01,4)}}</td>
                                        <td>{{$location->lpns01}}</td>
                                        <td>{{round($location->over01,4)}}</td>
                                        <td>{{round($location->totalcount01,4)}}</td>
                                        <td>{{$location->partnumber02}}</td>
                                        <td>{{$location->description02}}</td>
                                        <td>{{round($location->quantity02,4)}}</td>
                                        <td>{{$location->lpns02}}</td>
                                        <td>{{round($location->over02,4)}}</td>
                                        <td>{{round($location->totalcount02,4)}}</td>

                                        <td @if($location->sku == 'OK') style="background-color: #cfdd01 !important"
                                            @elseif($location->sku == 'Error') style="background-color: #ff5983 !important" @endif>
                                            {{$location->sku}}
                                        </td>
                                        <td @if($location->quantity == 'OK') style="background-color: #cfdd01 !important"
                                            @elseif($location->quantity == 'Error')  style="background-color: #ff5983 !important" @endif>
                                            {{$location->quantity}}
                                        </td>
                                        <td
                                            @if(!empty($location->sku) && !empty($location->quantity))
                                                @if($location->sku == 'OK' && $location->quantity == 'OK')
                                                    style="background-color: #cfdd01 !important"
                                            @else
                                                style="background-color: #ff5983 !important"
                                            @endif
                                            @endif
                                        >
                                            @if(!empty($location->sku) && !empty($location->quantity))
                                                @if($location->sku == 'OK' && $location->quantity == 'OK')
                                                    OK
                                                @else
                                                    Error
                                                @endif
                                            @endif
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

    <script>
        function reloadtable() {
            $('#table-cards').DataTable().ajax.reload();
        }
    </script>

    <script>
        var oTable = $(".tablegeneral").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "sScrollY": "450px",
            "sScrollX": true,
            "processing": true,
            // "serverSide": true,
            "responsive": true,
            // "colReorder": true,
            // "stateSave": true,
            // "stateDuration": 0,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 20, 35, 50, 100, 150, 200, 250, -1], [10, 20, 35, 50, 100, 150, 200, 250, "Todos"]],
            "columnDefs": [
                {"width": "120px", "targets": 0},
                {"width": "120px", "targets": 1},
                {"width": "120px", "targets": 2},
                {"width": "120px", "targets": 3},
                {"width": "120px", "targets": 4},
                {"width": "250px", "targets": 5},
                {"width": "120px", "targets": 6},
                {"width": "120px", "targets": 7},
                {"width": "120px", "targets": 8},
                {"width": "120px", "targets": 9},
                {"width": "120px", "targets": 10},
                {"width": "250px", "targets": 11},
                {"width": "120px", "targets": 12},
                {"width": "120px", "targets": 13},
                {"width": "120px", "targets": 14},
                {"width": "120px", "targets": 15},
                {"width": "120px", "targets": 16},
                {"width": "120px", "targets": 17},
                {"width": "120px", "targets": 18},
                // {"width": "120px", "targets": 19},
                // {"width": "120px", "targets": 20},
                // {"width": "120px", "targets": 21},
            ],
            buttons: [
                {
                    "extend": "colvis",
                    "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                    columns: ':not(:first):not(:last)'
                },
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 11px;'> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 11px;'> Excel</span>",
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

