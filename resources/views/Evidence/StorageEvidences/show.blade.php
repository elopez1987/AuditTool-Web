@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de evidencia
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('storageevidenceprocess.index')}}">
                                @include('buttons.back')
                            </a>
                            @if($storageevidenceprocess->statusid == 'E')
                                <a href="" data-target="#modal-destroy-{{$storageevidenceprocess->recordid}}"
                                   data-toggle="modal">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/destroy.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Eliminar</span>
                                    </button>
                                </a>
                                @include('Evidence.StorageEvidences.destroy')
                            @endif
                            @if($storageevidenceprocess->statusid == 'E')
                                <a href="" data-target="#modal-updatestatus-{{$storageevidenceprocess->recordid}}"
                                   data-toggle="modal">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Cerrar embarque</span>
                                    </button>
                                </a>
                                @include('Evidence.StorageEvidences.closeshipment')
                            @endif
                            @if($storageevidenceprocess->statusid == 'E')
                                <a href="" data-target="#modal-importdata-{{$storageevidenceprocess->recordid}}"
                                   data-toggle="modal">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/excel-impo.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Importar solicitud</span>
                                    </button>
                                </a>
                                @include('Evidence.StorageEvidences.importdata')
                            @endif
                            <a href="{{url('downloadfiles/' . $storageevidenceprocess->recordid)}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/files.png')}}" alt="" class="img-16">
                                    <span style="font-size: 11px;"> Descargar Evidencias</span>
                                </button>
                            </a>
                            @can('storageevidenceprocess.adjustrequest')
                                <a href="{{url('adjustrequest/' . $storageevidenceprocess->recordid)}}">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/viewdetails.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Ajustar solicitud</span>
                                    </button>
                                </a>
                            @endcan
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
                                    class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Datos Generales</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-camera"></i></span> <span class="hidden-xs-down">Verificación de embarque</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-list"></i></span> <span class="hidden-xs-down">Solicitud de material</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view04" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-list"></i></span> <span class="hidden-xs-down">Evidencia de Carga</span></a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active p20" id="view01" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="shipmentid">Embarque</label>
                                        <input type="text" class="form-control"
                                               value="{{$storageevidenceprocess->shipmentid}}"
                                               readonly id="shipmentid" name="shipmentid">
                                    </div>
                                </div>
                                @if($storageevidenceprocess->razonstatusid == 2)
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="provider">Proveedor</label>
                                            <input type="text" class="form-control"
                                                   value="{{$storageevidenceprocess->provider ? $storageevidenceprocess->provider->name : ''}}"
                                                   readonly id="provider" name="provider">
                                        </div>
                                    </div>
                                @endif
                                @if($storageevidenceprocess->razonstatusid == 1)
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="customer">Cliente</label>
                                            <input type="text" class="form-control"
                                                   value="{{$storageevidenceprocess->customer ? $storageevidenceprocess->customer->name : ''}}"
                                                   readonly id="customer" name="customer">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="workday">Fecha de trabajo</label>
                                        <input type="text" class="form-control"
                                               value="{{$storageevidenceprocess->workday}}"
                                               readonly id="workday" name="workday">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="carrier">Linea de Transporte</label>
                                        <input type="text" class="form-control"
                                               value="{{$storageevidenceprocess->carrier ? $storageevidenceprocess->carrier->name : ''}}"
                                               readonly id="carrier" name="carrier">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="boxnumber">Placa de caja</label>
                                        <input type="text" class="form-control"
                                               value="{{$storageevidenceprocess->boxnumber}}"
                                               readonly id="boxnumber" name="boxnumber">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="location">Cortina</label>
                                        <input type="text" class="form-control"
                                               value="{{$storageevidenceprocess->location ? $storageevidenceprocess->location->name : ''}}"
                                               readonly id="location" name="location">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane  p-20" id="view02" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table width="100%"
                                           class="table table-striped table-bordered table-condensed table-hover tablegeneral">
                                        <thead>
                                        <tr>
                                            <th>Sku</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <th>Lpn</th>
                                            <th>Evidencia Verificación</th>
                                            <th>Usiario Verificador</th>
                                            <th>F.Verificación</th>
                                            <th>Evidencia Carga</th>
                                            <th>Usuario Carga</th>
                                            <th>F.Carga</th>
                                            <th>Número de tarima</th>
                                            <th>Peso de tarima</th>
                                            <th>Estatus</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($lpns as $lpn)
                                            <tr>
                                                <td>{{$lpn->sku->prtnum}}</td>
                                                <td>{{$lpn->sku->lngdsc}}</td>
                                                <td>{{$lpn->quantity}}</td>
                                                <td>{{$lpn->lpnid}}</td>
                                                <td>
                                                    @if(!empty($lpn->filecheck))
                                                        <div class="el-card-item">
                                                            <div class="el-card-avatar el-overlay-1">
                                                                <div class="el-overlay">
                                                                    <ul class="list-style-none el-info">
                                                                        <li class="el-item bg-grey">
                                                                            <a class="btn default btn-outline image-popup-vertical-fit el-link"
                                                                               href="{{asset('storage/' . $lpn->filecheck)}}">
                                                                                Ver
                                                                                <i class="icon-magnifier"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{$lpn->usercheck->name}}</td>
                                                <td>{{$lpn->adddatecheck}}</td>
                                                <td>
                                                    @if(!empty($lpn->fileload))
                                                        <div class="el-card-item">
                                                            <div class="el-card-avatar el-overlay-1">
                                                                <div class="el-overlay">
                                                                    <ul class="list-style-none el-info">
                                                                        <li class="el-item bg-grey">
                                                                            <a class="btn default btn-outline image-popup-vertical-fit el-link"
                                                                               href="{{asset('storage/' . $lpn->fileload)}}">
                                                                                Ver
                                                                                <i class="icon-magnifier"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        Sin Evidencia
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$lpn->userload ? $lpn->userload->name : ''}}
                                                </td>
                                                <td>{{$lpn->adddateload}}</td>
                                                <td>{{$lpn->value01}}</td>
                                                <td>{{$lpn->value02}}</td>
                                                <td @if($lpn->statusid == 'FS') class="bg-danger text-white text-center flash"
                                                    @else class="bg-success text-white text-center" @endif>
                                                    {{$lpn->status->name}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="view03" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table width="100%"
                                           class="table table-condensed table-bordered table-striped table-hover tablegeneral">
                                        <thead>
                                        <tr>
                                            <th>Embarque</th>
                                            <th>Sku</th>
                                            <th>Descripción</th>
                                            <th>Cantidad solicitada</th>
                                            <th>Cantidad verificada</th>
                                            <th>Estatus</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($stocks as $stock)
                                            <tr>
                                                <td>{{$stock->shipmentid}}</td>
                                                <td>{{$stock->partnumber}}</td>
                                                <td>{{$stock->lngdsc}}</td>
                                                <td>{{$stock->solicitado}}</td>
                                                <td>{{$stock->verificado}}</td>
                                                <td
                                                    @if($stock->solicitado != $stock->verificado) class="bg-danger text-white text-center"
                                                    @else class="bg-success text-white text-center" @endif
                                                >
                                                    {{$stock->solicitado - $stock->verificado}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="view04" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">Evidencia de carga</div>
                                        <div class="card-body">
                                            @if (!empty($evidences))
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row el-element-overlay">
                                                                    @foreach($evidences as $photo)
                                                                        @if ($photo->auxiliary01 == 'SH')
                                                                            <div class="col-lg-2 col-md-6">
                                                                                <div class="card">
                                                                                    <div class="el-card-item">
                                                                                        <div
                                                                                            class="el-card-avatar el-overlay-1">
                                                                                            <img
                                                                                                src="{{asset('storage/' . $photo->path)}}"
                                                                                                alt="user"
                                                                                                style="max-width: 200px;max-height: 200px"/>
                                                                                            <div class="el-overlay">
                                                                                                <ul class="list-style-none el-info">
                                                                                                    <li class="el-item">
                                                                                                        <a href="{{url('downloadphoto/' . $photo->id)}}"
                                                                                                           data-togggle="tooltip"
                                                                                                           title="Descargar"
                                                                                                           class="btn default btn-outline el-link">
                                                                                                            <i class="mdi mdi-download"></i>
                                                                                                        </a>
                                                                                                    </li>
                                                                                                    @can('destroyphotocargo')
                                                                                                        <li class="el-item">
                                                                                                            <a href="{{url('destroyphoto/' . $photo->id)}}"
                                                                                                               class="btn default btn-outline el-link">
                                                                                                                <i class="mdi mdi-delete"></i>
                                                                                                            </a>
                                                                                                        </li>
                                                                                                    @endcan
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="el-card-content">
                                                                                            <h4 class="m-b-0 font-10 font-weight-light"> {{$photo->user->name}} </h4>
                                                                                            <span
                                                                                                class="text-muted">{{$photo->created_at}}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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

