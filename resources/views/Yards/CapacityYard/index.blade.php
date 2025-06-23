@extends('layouts.app')

@section('content')
    @foreach($centers as $center)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                     ocupación -   {{$center->description}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row " style="border-bottom: 5px solid #1a237e">
                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <span class="font-16 font-weight-bold">CAPACIDAD DE PATIO</span>
                                                        <h2 class="font-medium display-6 m-b-0 text-success"
                                                            style="font-size: 50px !important;font-weight: 700 !important;">{{$totalLocations}} </h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="d-flex align-items-center">
                                                    <div><span class="font-16 font-weight-bold">UNIDADES EN PATIO</span>
                                                        @if($locationsBusy > 0)
                                                            <h2 class="font-medium  display-6 m-b-0 text-warning"
                                                                style="font-size: 50px !important;font-weight: 700 !important;">
                                                                {{$locationsBusy}}</h2>
                                                        @else
                                                            <h2 class="font-medium  display-6 m-b-0 text-warning"
                                                                style="font-size: 50px !important;font-weight: 700 !important;">
                                                                0</h2>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="d-flex align-items-center">
                                                    <div><span class="font-16 font-weight-bold">OCUPACIÓN</span>
                                                        <h2 class="font-medium display-6 m-b-0 text-danger"
                                                            style="font-size: 50px !important;font-weight: 700 !important;"> {{ round((100 / ($totalLocations)) *  $locationsBusy) }}
                                                            % </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <table id="table-capacity" class="table table-striped table-bordered table-condensed  table-capacity font-12">
                                                    <thead>
                                                    <tr>
                                                        <th class="bg-inverse text-white"
                                                            style="font-size: 11px !important;font-weight: 600">
                                                            C.Distribución
                                                        </th>
                                                        <th class="bg-inverse text-white"
                                                            style="font-size: 11px !important;font-weight: 600">Zona
                                                        </th>
                                                        <th class="bg-inverse text-white"
                                                            style="font-size: 11px !important;font-weight: 600">
                                                            Capacidad
                                                        </th>
                                                        <th class="bg-inverse text-white"
                                                            style="font-size: 11px !important;font-weight: 600">Ocupado
                                                        </th>
                                                        <th class="bg-inverse text-white"
                                                            style="font-size: 11px !important;font-weight: 600">Libre
                                                        </th>
                                                        <th class="bg-inverse text-white"
                                                            style="font-size: 11px !important;font-weight: 600">Estatus
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($zones as $zone)
                                                        @if ($zone->centerid == $center->centerid)
                                                            <tr>
                                                                <td>{{$zone->center->description}}</td>
                                                                <td>{{$zone->name}}</td>
                                                                <td>{{$zone->capacity}}</td>
                                                                @foreach($locationfill as $fill)
                                                                    @if($fill->zoneid == $zone->id)
                                                                        <td>
                                                                            {{$fill->ocupado}}
                                                                        </td>
                                                                        <td>
                                                                            @if($zone->capacity > 0)
                                                                                {{$zone->capacity - $fill->ocupado}}
                                                                            @else
                                                                                0
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($zone->capacity > 0)
                                                                                @if((100 / $zone->capacity) * $fill->ocupado > $center->capacityyard)
                                                                                    <span class="label label-danger font-12"> {{round((100 / $zone->capacity ) * $fill->ocupado)}} %</span>
                                                                                @else
                                                                                    <span class="label label-success font-12"> {{round((100 / $zone->capacity ) * $fill->ocupado)}} %</span>
                                                                                @endif
                                                                            @else
                                                                                <span class="label label-info font-12"> 0 %</span>
                                                                            @endif
                                                                        </td>
                                                                    @endif
                                                                @endforeach
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr class="m-t-5 m-b-5" style="border-color: #1a237e">
                                        <div class="row mt-3">
                                            <div class="col-lg-2 col-sm-12 col-xs-12">
                                                <a href="{{route('layoutinventoryforzone')}}">
                                                    <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md">
                                                        <img src="{{asset('/ico/adbuscar.png')}}" alt="" class="img-16 ml-1">
                                                        <span style="font-size: 0.675rem "> Ver detalles</span>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('css')
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $("#table-capacity").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "200px",
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                buttons: [
                    {
                        "extend": "colvis",
                        "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Mostar/Ocultar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                        columns: ':not(:first):not(:last)'
                    },
                    {
                        "extend": "copy",
                        "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Copiar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                    },
                    {
                        "extend": "csv",
                        "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem !important;'> Csv</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                    },
                    {
                        "extend": "excel",
                        "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Excel</span>",
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
        })
    </script>

@endsection
