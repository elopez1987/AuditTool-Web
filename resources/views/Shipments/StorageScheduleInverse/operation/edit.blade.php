@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    Datos generales
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('storagescheduleinverse.index')}}">
                                @include('buttons.back')
                            </a>
                            @if(!empty($operation->finishdate))
                                @if($storagescheduleinverse->statusid != 'TD')
                                    <a onclick="closeCountCheck( {!! $storagescheduleinverse->orderid!!} )">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                                            <span style="font-size: 11px;"> Abrir Verificación</span>
                                        </button>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control" readonly id="shipmentid" name="shipmentid"
                                           value="{{$storagescheduleinverse->order->shipmentid}}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="customerid">Cliente</label>
                                    <input type="text" class="form-control" readonly id="customerid" name="customerid"
                                           value="{{$storagescheduleinverse->order->customer->name}}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carrierid">Linea de transporte</label>
                                    <input type="text" class="form-control" readonly id="carrierid" name="carrierid"
                                           value="{{$storagescheduleinverse->order->carrier->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="startdate">Inicio de verificación</label>
                                    <input type="text" class="form-control" id="startdate" name="startdate" readonly
                                           value="{{$operation != null ? $operation->startdate : ''}}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="finishdate">Fin de verificación</label>
                                    <input type="text" class="form-control" id="finishdate" name="finishdate" readonly
                                           value="{{$operation != null ? $operation->finishdate : ''}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    Modificar Cita
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{url('storagescheduleinverseoperation/' . $storagescheduleinverse->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @if(empty($operation->warehousedate))
                                    <a href="">
                                        @include('buttons.save')
                                    </a>
                                @endif
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="wmsdate">Ingreso a WMS <span style="color: red">*</span></label>
                                        <input type="text" class="form-control datefilter" id="wmsdate" @if($storagescheduleinverse->statusid == 'TD') readonly @endif
                                               name="wmsdate" value="{{$operation != null ?  $operation->wmsdate : ''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="warehousedate">F. Entrega a almacen <span
                                                style="color: red">*</span></label>
                                        <input type="text" class="form-control datefilter" id="warehousedate" @if($storagescheduleinverse->statusid == 'TD') readonly @endif
                                               name="warehousedate" value="{{$operation != null ?  $operation->warehousedate : ''}}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="folionumber">Folio de patios <span
                                                style="color: red">*</span></label>
                                        <input type="text" class="form-control numeric" id="folionumber" @if($storagescheduleinverse->statusid == 'TD') readonly @endif
                                               name="folionumber" value="{{$storagescheduleinverse->folionumber}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de la entrega
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                                role="tab"><span class="hidden-sm-up"><i
                                                        class="ti-home"></i></span> <span
                                                    class="hidden-xs-down">Recibo de producto</span></a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03"
                                                                role="tab"><span class="hidden-sm-up"><i
                                                        class="ti-list"></i></span> <span
                                                    class="hidden-xs-down">Skus sin reporte de incidencias</span></a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02"
                                                                role="tab"><span class="hidden-sm-up"><i
                                                        class="ti-list"></i></span> <span
                                                    class="hidden-xs-down">Datos de Transporte</span></a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view04"
                                                                role="tab"><span class="hidden-sm-up"><i
                                                        class="ti-list"></i></span> <span
                                                    class="hidden-xs-down">Evidencias</span></a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="view01" role="tabpanel">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <table class="table table-bordered table-condensed  table-hover tablegeneral">
                                                        <thead>
                                                        <tr>
                                                            <th>Embarque</th>
                                                            <th>Factura</th>
                                                            <th>IDH</th>
                                                            <th>Descripción</th>
                                                            <th>Cantidad Reportada</th>
                                                            <th>U.M Reportada</th>
                                                            <th>Cantidad Verificada</th>
                                                            <th>Diferencia</th>
                                                            <th>UM. Verificada</th>
                                                            <th>Categoria</th>
                                                            <th>Ubicación</th>
                                                            <th>Comentario</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($details as $item)
                                                            <tr>
                                                                <td>{{$item->shipmentid}}</td>
                                                                <td>{{$item->delivery}}</td>
                                                                <td>{{$item->partnumberid}}</td>
                                                                <td>{{$item->description}}</td>
                                                                <td>{{$item->reportada }} </td>
                                                                <td>{{$item->unitmeasure }} </td>
                                                                <td>{{$item->verificada}}</td>
                                                                <td> {{$item->reportada - $item->verificada}}</td>
                                                                <td> {{$item->unitmeasureid}}</td>
                                                                <td> {{$item->category}}</td>
                                                                <td> {{$item->location}}</td>
                                                                <td> {{$item->comment}}</td>
                                                                <td>
                                                                    @if(empty($operation->warehousedate))
                                                                        @if(!empty($item->verificada != 0))
                                                                            <button class="btn btn-sm btn-outline-info"
                                                                                    onclick="openCount( {!! $item->folioid !!} )">
                                                                                Reabrir Conteo
                                                                            </button>
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
                                        <div class="tab-pane " id="view02" role="tabpanel">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <table
                                                        class="table table-condensed table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Embarque</th>
                                                            <th>Factura</th>
                                                            <th>Tipo</th>
                                                            <th>Zere</th>
                                                            <th>Nota de Entrega</th>
                                                            <th>Nota de Credito</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(!empty($trafficDetails))
                                                            @foreach($trafficDetails as $detail)
                                                                <tr>
                                                                    <td> {{$detail->order ? $detail->order->shipmentid : ''}} </td>
                                                                    <td>{{$detail->delivery}}</td>
                                                                    <td>{{$detail->typereport}}</td>
                                                                    <td>{{$detail->zare}}</td>
                                                                    <td>{{$detail->ne}}</td>
                                                                    <td>{{$detail->nc}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="view03" role="tabpanel">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <table
                                                        class="table table-bordered table-condensed  table-hover tablegeneral">
                                                        <thead>
                                                        <tr>
                                                            <th>Embarque</th>
                                                            <th>Factura</th>
                                                            <th>IDH</th>
                                                            <th>Descripción</th>
                                                            <th>Cantidad Verificada</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(!empty($skusVerificados))
                                                            @foreach($skusVerificados as $sku)
                                                                <tr>
                                                                    <td>{{$sku->shipmentid}}</td>
                                                                    <td>{{$sku->delivery}}</td>
                                                                    <td>{{$sku->partnumberid}}</td>
                                                                    <td>{{$sku->description}}</td>
                                                                    <td>{{$sku->total}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="view04" role="tabpanel">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-header">Evidencia de descarga</div>
                                                        <div class="card-body">
                                                            @if (!empty($evidences))
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <div class="row el-element-overlay">
                                                                                    @foreach($evidences as $photo)
                                                                                        @if ($photo->auxiliary01 == 'DEV')
                                                                                            <div
                                                                                                class="col-lg-2 col-md-6">
                                                                                                <div class="card">
                                                                                                    <div
                                                                                                        class="el-card-item">
                                                                                                        <div
                                                                                                            class="el-card-avatar el-overlay-1">
                                                                                                            <img
                                                                                                                src="{{asset('storage/' . $photo->path)}}"
                                                                                                                alt="user"
                                                                                                                style="max-width: 200px;max-height: 200px"/>
                                                                                                            <div
                                                                                                                class="el-overlay">
                                                                                                                <ul class="list-style-none el-info">
                                                                                                                    <li class="el-item">
                                                                                                                        <a href="{{url('downloadphotocheckin/' . $photo->id)}}"
                                                                                                                           data-togggle="tooltip"
                                                                                                                           title="Descargar"
                                                                                                                           class="btn default btn-outline el-link">
                                                                                                                            <i class="mdi mdi-download"></i>
                                                                                                                        </a>
                                                                                                                    </li>
                                                                                                                </ul>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="el-card-content">
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
            $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
                $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
            });
            $('table.table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "350px",
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

    <script>
        function openCount($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "El siguiente evento limpiara los valores del conteo!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, reabrir conteo!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        data: {
                            id: $id,
                        },
                        url: '{!! route('openCountToReceiptInverse') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Evento procesado correctamente`,
                                width: 350,
                            })
                            setTimeout(function () {
                                window.location.reload()
                            }, 2000)
                        },
                    })

                }
            })
        }
    </script>
    <script>
        function closeCountCheck($orderid) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Cambiar estatus de verificando!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, reabrir la verificación!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        data: {
                            orderid: $orderid,
                        },
                        url: '{!! route('openCountCheck') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Evento cerrado correctamente`,
                                width: 350,
                            })
                            setTimeout(function () {
                                window.location.reload()
                            }, 2000)
                        },
                    })
                }
            })
        }
    </script>
@endsection


