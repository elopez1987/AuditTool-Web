@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Número de embarque: <span
                        style="font-size: 15px;color: red;font-weight: 700;text-align: left;margin-right: 100px"> {{ $shipment->order->shipmentid}}</span>
                    Fecha de carga: <span
                        style="font-size: 13px;color: red;font-weight: 400;text-align: left;margin-right: 100px"> {{ $shipment->order->workday}}</span>
                    Fecha de descarga: <span
                        style="font-size: 13px;color: red;font-weight: 400;text-align: left;margin-right: 100px"> {{$shipment->order->downloaddate}}</span>
                    Cliente: <span
                        style="font-size: 13px;color: red;font-weight: 400;text-align:right;margin-right: 100px"> {{ $shipment->order->customer->name}}</span>
                    Destino: <span
                        style="font-size: 13px;color: red;font-weight: 400;text-align:right;"> {{$shipment->order->customer->city}}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{route('followupofincidences.index')}}">
                                @include('buttons.back')
                            </a>

                            @if($shipment->key ? $shipment->key->statusid != 'PI' : '')
                                @if($shipment->statuskeytrid != 'OA' )
                                    @if($shipment->statuskeytrid == 'RF')

                                    @else
                                        @can('storageincidencesjourneys.acceptresponse')
                                            <a href="">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-outline-info btn-md"
                                                        id="saveResponseAccept">
                                                    <img src="{{asset('/ico/unitstatus.png')}}" alt="" class="img-16">
                                                    <span style="font-size: 11px;"> Aceptar Respuesta</span>
                                                </button>
                                            </a>
                                        @endcan
                                        @can('storageincidencesjourneys.validateresponse')
                                            <a href="">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-outline-info btn-md"
                                                        id="saveResponseValidate">
                                                    <img src="{{asset('/ico/review.png')}}" alt="" class="img-16">
                                                    <span style="font-size: 11px;"> Solicitar Revisión</span>
                                                </button>
                                            </a>
                                        @endcan
                                    @endif
                                @endif
                            @endif

                            @can('storageincidencesjourneys.open')
                                @if($shipment->statusid == 'CD')
                                    <a href="{{url('storageincidencesjourneys/' . $shipment->folioid .'/open')}}">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/tracefor.png')}}" alt="" class="img-16">
                                            <span style="font-size: 11px;"> Abrir incidencia</span>
                                        </button>
                                    </a>
                                @endif
                            @endcan

                        </div>
                    </div>
                    <div class="card-input">

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body p-b-0">
                                        <ul class="nav nav-tabs customtab" role="tablist">
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                                    href="#home2" role="tab"><span class="hidden-sm-up"><i
                                                            class="ti-home"></i></span> <span class="hidden-xs-down">Detalle</span></a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#profile2"
                                                                    role="tab"><span class="hidden-sm-up"><i
                                                            class="ti-comment"></i></span> <span class="hidden-xs-down">Comentarios</span></a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#messages2"
                                                                    role="tab"><span class="hidden-sm-up"><i
                                                            class="ti-camera"></i></span> <span class="hidden-xs-down">Evidencias</span></a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="home2" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table
                                                            class="table table-condensed table-bordered table-hover tablegeneral">
                                                            <thead>
                                                            <tr>
                                                                <th>Factura</th>
                                                                <th>IDH</th>
                                                                <th>Descripción</th>
                                                                <th>Cantidad</th>
                                                                <th>Unidad de medida</th>
                                                                <th>Motivo</th>
                                                                <th>Motivo Reportado</th>
                                                                <th>Respuesta</th>
                                                                <th>Estatus</th>
                                                                <th>Responsable</th>
                                                                <th>Motivo de Firma almacen</th>
                                                                <th>Firmado por</th>
                                                                <th>Fecha de Firma almacen</th>
                                                                <th>Motivo de Firma transporte</th>
                                                                <th>Firmado por</th>
                                                                <th>Fecha de Firma transporte</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($details as $detail)
                                                                <tr>
                                                                    <td>{{$detail->delivery}}</td>
                                                                    <td>{{$detail->partnumberid}}</td>
                                                                    <td>{{$detail->description}}</td>
                                                                    <td>{{$detail->quantity}}</td>
                                                                    <td>{{$detail->unitmeasure}}</td>
                                                                    <td>{{$detail->concepto}}</td>
                                                                    <td>{{$detail->code}}</td>
                                                                    <td>
                                                                        <a href=""
                                                                           data-target="#modal-comment-{{$detail->folioid}}"
                                                                           data-toggle="modal">
                                                                            <button type="submit"
                                                                                    class=" waves-effect waves-light btn-outline-info btn-md">
                                                                                <i class="ti ti-comment"></i>
                                                                            </button>
                                                                        </a>
                                                                        @include('Shipments.StorageIncidenceJourneys.comment')
                                                                    </td>
                                                                    <td>{{$detail->status}}</td>
                                                                    <td>{{$detail->responsable}}</td>
                                                                    <td>{{$detail->keyresponse}}</td>
                                                                    <td>{{$detail->createuser}}</td>
                                                                    <td>{{$detail->responsedate}}</td>
                                                                    <td>{{$detail->codetr}}</td>
                                                                    <td>{{$detail->keyusertr}}</td>
                                                                    <td>{{$detail->keytransportdate}}</td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        @foreach($comments as $comment)
                                                            <div class="comment-widgets scrollable">
                                                                <div class="d-flex flex-row comment-row m-t-0">
                                                                    <div class="p-2"><img
                                                                            src="{{asset('/img/YMS.png')}}"
                                                                            alt="user" width="50"
                                                                            class="rounded-circle"></div>
                                                                    <div class="comment-text w-100">
                                                                        <h6 class="font-medium"></h6>
                                                                        <span
                                                                            class="m-b-15 d-block">{{$comment->comment}} </span>
                                                                        <div class="comment-footer">
                                                                    <span
                                                                        class="text-muted float-right">{{$comment->created_at}}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mb-2">
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane p-20" id="messages2" role="tabpanel">
                                                <div class="row el-element-overlay">
                                                    <div class="col-lg-6">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        Fotos de evidencia de entrega
                                                                    </div>
                                                                    <div class="card-body">
                                                                        @if (!empty($entregas))
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <div class="row el-element-overlay">
                                                                                                @foreach($entregas as $photo)
                                                                                                    @if ($photo->auxiliary01 == 'ENT')
                                                                                                        <div class="col-lg-2 col-md-6">
                                                                                                            <div class="card">
                                                                                                                <div class="el-card-item">
                                                                                                                    <div class="el-card-avatar el-overlay-1">
                                                                                                                        <img src="{{asset('storage/' . $photo->path)}}" alt="user" style="max-width: 200px;max-height: 200px"/>
                                                                                                                        <div class="el-overlay">
                                                                                                                            <ul class="list-style-none el-info">
                                                                                                                                <li class="el-item"><a
                                                                                                                                        class="btn default btn-outline image-popup-vertical-fit el-link"
                                                                                                                                        href="{{asset('storage/' . $photo->path)}}"><i
                                                                                                                                            class="ti ti-search"></i></a>
                                                                                                                                </li>
                                                                                                                                <li class="el-item"><a
                                                                                                                                        class="btn default btn-outline el-link"
                                                                                                                                        href="{{url('downloadphoto/' . $photo->id)}}"><i
                                                                                                                                            class="ti ti-download"></i></a>
                                                                                                                                </li>
                                                                                                                                @can('destroyphotoincidence')
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
                                                                                                                        <span class="text-muted">{{$photo->created_at}}</span>
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
                                                    <div class="col-lg-6">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        Fotos de evidencia de revisión de inventario
                                                                    </div>
                                                                    <div class="card-body">
                                                                        @if (!empty($entregas))
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <div class="row el-element-overlay">
                                                                                                @foreach($entregas as $photo)
                                                                                                    @if ($photo->auxiliary01 == 'INC')
                                                                                                        <div class="col-lg-2 col-md-6">
                                                                                                            <div class="card">
                                                                                                                <div class="el-card-item">
                                                                                                                    <div class="el-card-avatar el-overlay-1">
                                                                                                                        <img src="{{asset('storage/' . $photo->path)}}" alt="user" style="max-width: 200px;max-height: 200px"/>
                                                                                                                        <div class="el-overlay">
                                                                                                                            <ul class="list-style-none el-info">
                                                                                                                                <li class="el-item"><a
                                                                                                                                        class="btn default btn-outline image-popup-vertical-fit el-link"
                                                                                                                                        href="{{asset('storage/' . $photo->path)}}"><i
                                                                                                                                            class="ti ti-search"></i></a>
                                                                                                                                </li>
                                                                                                                                <li class="el-item"><a
                                                                                                                                        class="btn default btn-outline el-link"
                                                                                                                                        href="{{url('downloadphoto/' . $photo->id)}}"><i
                                                                                                                                            class="ti ti-download"></i></a>
                                                                                                                                </li>
                                                                                                                                @can('destroyphotoincidence')
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
                                                                                                                        <h4 class="m-b-0 font-10 font-weight-light"> {{$photo->user ? $photo->user->name : ''}} </h4>
                                                                                                                        <span class="text-muted">{{$photo->created_at}}</span>
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
                                        <div class="card-body p-t-0">

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
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/meg.init.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        let oTable = $(".tablegeneral").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "sScrollY": "350px",
            "sScrollX": true,
            "processing": true,
            "serverSide": false,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[5, 10, 30, 50, 100, 150, 200, 250, -1], [5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],

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
            "columnDefs": [
                {"width": "100px", "targets": 0},
                {"width": "150px", "targets": 1},
                {"width": "250px", "targets": 2},
                {"width": "150px", "targets": 3},
                {"width": "120px", "targets": 4},
                {"width": "150px", "targets": 5},
                {"width": "180px", "targets": 6},
                {"width": "180px", "targets": 7},
                {"width": "180px", "targets": 8},
                {"width": "180px", "targets": 9},
                {"width": "180px", "targets": 10},
                {"width": "180px", "targets": 11},
                {"width": "180px", "targets": 12},
                {"width": "180px", "targets": 13},
                {"width": "180px", "targets": 14},
                {"width": "180px", "targets": 15},
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

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#saveResponseAccept').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        folioid: {{$shipment->folioid}}
                    },
                    url: '{!! route('saveResponseAcceptIncidence') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        Swal.fire({
                            type: 'success',
                            title: ` ${data.Message}`,
                            // text: ` ${data.Name}`,
                            width: 350,
                        })
                    },
                });
                $('#saveResponseAccept').hide();
                $('#saveResponseValidate').hide();
            });
        });
    </script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#saveResponseValidate').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        folioid: {{$shipment->folioid}}
                    },
                    url: '{!! route('saveResponseValidateIncidence') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        Swal.fire({
                            type: 'success',
                            title: ` ${data.Message}`,
                            // text: ` ${data.Name}`,
                            width: 350,
                        })
                    },
                });
                $('#saveResponseAccept').hide();
                $('#saveResponseValidate').hide();
            });
        });
    </script>

@endsection
