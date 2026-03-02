@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de contenedor
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('reportauditshipments.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                role="tab"><span
                                    class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Detalle</span></a>
                        </li>
{{--                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02" role="tab"><span--}}
{{--                                    class="hidden-sm-up"><i class="ti-camera"></i></span> <span class="hidden-xs-down">Verificación de embarque</span></a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03" role="tab"><span--}}
{{--                                    class="hidden-sm-up"><i class="ti-list"></i></span> <span class="hidden-xs-down">Solicitud de material</span></a>--}}
{{--                        </li>--}}
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="view01" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                <table class="table table-condensed table-bordered table-striped table-hover tablegeneral">
                                    <thead>
                                        <tr>
                                            <th>Lpn</th>
                                            <th>Estación</th>
                                            <th>Usuario</th>
                                            <th>Fecha de movimiento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($details as $detail)
                                            <tr>
                                                <td>{{$detail->lpnid}}</td>
                                                <td>{{$detail->station ? $detail->station->name : ''}}</td>
                                                <td>{{$detail->user ? $detail->user->name : ''}}</td>
                                                <td>{{$detail->scandate}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
{{--                        <div class="tab-pane  p-20" id="view02" role="tabpanel">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-lg-12">--}}
{{--                                    <table--}}
{{--                                        class="table table-striped table-bordered table-condensed table-hover tablegeneral">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>Sku</th>--}}
{{--                                            <th>Descripción</th>--}}
{{--                                            <th>Cantidad</th>--}}
{{--                                            <th>Lpn</th>--}}
{{--                                            <th>Evidencia Verificación</th>--}}
{{--                                            <th>Usiario Verificador</th>--}}
{{--                                            <th>F.Verificación</th>--}}
{{--                                            <th>Evidencia Carga</th>--}}
{{--                                            <th>Usiario Carga</th>--}}
{{--                                            <th>F.Carga</th>--}}
{{--                                            <th>Estatus</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        @foreach($lpns as $lpn)--}}
{{--                                            <tr>--}}
{{--                                                <td>{{$lpn->sku->prtnum}}</td>--}}
{{--                                                <td>{{$lpn->sku->lngdsc}}</td>--}}
{{--                                                <td>{{$lpn->quantity}}</td>--}}
{{--                                                <td>{{$lpn->lpnid}}</td>--}}
{{--                                                <td>--}}
{{--                                                    <div class="el-card-item">--}}
{{--                                                        <div class="el-card-avatar el-overlay-1">--}}
{{--                                                            <div class="el-overlay">--}}
{{--                                                                <ul class="list-style-none el-info">--}}
{{--                                                                    <li class="el-item bg-grey">--}}
{{--                                                                        <a class="btn default btn-outline image-popup-vertical-fit el-link"--}}
{{--                                                                           href="{{asset('storage/' . $lpn->filecheck)}}">--}}
{{--                                                                            Ver--}}
{{--                                                                            <i class="icon-magnifier"></i>--}}
{{--                                                                        </a>--}}
{{--                                                                    </li>--}}
{{--                                                                </ul>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </td>--}}
{{--                                                <td>{{$lpn->usercheck->name}}</td>--}}
{{--                                                <td>{{$lpn->adddatecheck}}</td>--}}
{{--                                                <td>--}}
{{--                                                    @if(!empty($lpn->fileload))--}}
{{--                                                        <div class="el-card-item">--}}
{{--                                                            <div class="el-card-avatar el-overlay-1">--}}
{{--                                                                <div class="el-overlay">--}}
{{--                                                                    <ul class="list-style-none el-info">--}}
{{--                                                                        <li class="el-item bg-grey">--}}
{{--                                                                            <a class="btn default btn-outline image-popup-vertical-fit el-link"--}}
{{--                                                                               href="{{asset('storage/' . $lpn->fileload)}}">--}}
{{--                                                                                Ver--}}
{{--                                                                                <i class="icon-magnifier"></i>--}}
{{--                                                                            </a>--}}
{{--                                                                        </li>--}}
{{--                                                                    </ul>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    @else--}}
{{--                                                        Sin Evidencia--}}
{{--                                                    @endif--}}
{{--                                                </td>--}}
{{--                                                <td>--}}
{{--                                                    {{$lpn->userload ? $lpn->userload->name : ''}}--}}
{{--                                                </td>--}}
{{--                                                <td>{{$lpn->adddateload}}</td>--}}
{{--                                                <td @if($lpn->statusid == 'FS') class="bg-danger text-white text-center flash"--}}
{{--                                                    @else class="bg-success text-white text-center" @endif>--}}
{{--                                                    {{$lpn->status->name}}--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="tab-pane p-20" id="view03" role="tabpanel">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-lg-12">--}}
{{--                                    <table--}}
{{--                                        class="table table-condensed table-bordered table-striped table-hover tablegeneral">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>Embarque</th>--}}
{{--                                            <th>Sku</th>--}}
{{--                                            <th>Descripción</th>--}}
{{--                                            <th>Cantidad solicitada</th>--}}
{{--                                            <th>Cantidad verificada</th>--}}
{{--                                            <th>Estatus</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        @foreach($stocks as $stock)--}}
{{--                                            <tr>--}}
{{--                                              <td>{{$stock->shipment}}</td>--}}
{{--                                              <td>{{$stock->partnumberid}}</td>--}}
{{--                                              <td>{{$stock->description}}</td>--}}
{{--                                              <td>{{$stock->solicitado}}</td>--}}
{{--                                              <td>{{$stock->verificado}}</td>--}}
{{--                                              <td @if($stock->statustotal == 'Error') class="bg bg-danger text-center text-white" @else class="bg bg-success text-center text-white" @endif>--}}
{{--                                                  {{$stock->statustotal}}--}}
{{--                                              </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/magnific-popup/dist/magnific-popup.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/meg.init.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
            });
            var oTable = $(".tablegeneral").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "250px",
                "sScrollX": true,
                "processing": true,
                "serverSide": false,
                "responsive": true,
                "colReorder": true,
                "stateSave": true,
                "stateDuration": 0,
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],

                "columnDefs": [
                    {"width": "150px", "targets": 0},
                    {"width": "250px", "targets": 1},
                    {"width": "350px", "targets": 2},
                    {"width": "250px", "targets": 3},
                    // {"width": "150px", "targets": 4},
                    // {"width": "250px", "targets": 5},
                    // {"width": "150px", "targets": 6},
                    // {"width": "150px", "targets": 7},
                    // {"width": "250px", "targets": 8},
                    // {"width": "150px", "targets": 9},
                    // {"width": "150px", "targets": 10},
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
        });
    </script>

@endsection

