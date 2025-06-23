@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Inventario por Zona
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{route('layoutcapacitycenter')}}">
                                @include('buttons.back')
                            </a>
                            <a href="{{route('exportDataLocationsForStatus')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/excel-impo.png')}}" alt="" class="img-16">
                                    <span style="font-size: 0.675rem "> Exportar Registros</span>
                                </button>

                            </a>
                        </div>
                    </div>
                    <hr class="mt-3 ">
                    <div class="row">
                        @foreach($zones as $zone)
                            <div class="col-lg-6 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="card-input">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="text-capitalize ">
                                                        {{$zone->name}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table
                                                class="table table-bordered table-striped table-condensed table-capacity">
                                                <thead>
                                                <tr>
                                                    <th>Ubicacion</th>
                                                    <th>Estatus</th>
                                                    <th>Caja</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($locations as $location)
                                                    @if ($location->zoneid == $zone->id)
                                                        <tr>
                                                            <td>{{$location->name}}</td>
                                                            <td>
                                                                @if($location->statusid == 'D')
                                                                    <span class="label label-rounded label-danger">  {{$location->status->name}}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($location->auxiliary01 != null)
                                                                    <span class="badge badge-success" style="font-size: .675rem"> {{$location->auxiliary01 }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $(".table-capacity").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "300px",
                "serverSide": false,
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[50, -1], [50, "All"]],
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
