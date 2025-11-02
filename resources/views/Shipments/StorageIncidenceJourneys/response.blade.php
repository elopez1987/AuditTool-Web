@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">Datos y respuestas</span></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-camera"></i></span> <span
                                    class="hidden-xs-down">Evidencia de transporte</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-comment"></i></span> <span
                                    class="hidden-xs-down">Comentarios de transporte</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="view01" role="tabpanel">
                            <form method="POST"
                                  action="{{url('storageincidencesjourneysresponsestore/' . $shipment->folioid)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        Agregar respuesta.
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                @if($shipment->statusid != 'CD')
                                                                    <a href="">
                                                                        @include('buttons.save')
                                                                    </a>
                                                                @endif
                                                                <a href="{{route('storageincidencesjourneys.index')}}">
                                                                    @include('buttons.back')
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr class="mb-3">
                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="shipmentid">Embarque</label>
                                                                            <input type="text" class="form-control"
                                                                                   readonly id="shipmentid"
                                                                                   name="shipmentid"
                                                                                   onKeyUp=this.value=this.value.toUpperCase()
                                                                                   value="{{$shipment->order->shipmentid}}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="workday">Fecha de
                                                                                trabajo</label>
                                                                            <input type="text" class="form-control"
                                                                                   readonly id="workday"
                                                                                   name="workday"
                                                                                   onKeyUp=this.value=this.value.toUpperCase()
                                                                                   value="{{$shipment->order->workday}}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="downloaddate">Fecha de
                                                                                descarga</label>
                                                                            <input type="text" class="form-control"
                                                                                   readonly id="downloaddate"
                                                                                   name="downloaddate"
                                                                                   onKeyUp=this.value=this.value.toUpperCase()
                                                                                   value="{{$shipment->order->downloaddate}}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-8 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="cliente">Cliente</label>
                                                                            <input type="text" class="form-control"
                                                                                   readonly id="cliente"
                                                                                   name="cliente"
                                                                                   onKeyUp=this.value=this.value.toUpperCase()
                                                                                   value="{{$shipment->order->customer->name}}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="city">Ciudad</label>
                                                                            <input type="text" class="form-control"
                                                                                   readonly id="city"
                                                                                   name="city"
                                                                                   onKeyUp=this.value=this.value.toUpperCase()
                                                                                   value="{{$shipment->order->customer->city}}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if($shipment->statusid != 'CD')
                                                                    <div class="row mt-2">
                                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="statusid">Estatus <span
                                                                                        style="color: red">*</span></label>
                                                                                <select name="statusid"
                                                                                        class="form-control select2">
                                                                                    @foreach($status as $statu)
                                                                                        <option
                                                                                            value="{{$statu->statusid}}"
                                                                                            @if($statu->statusid == $shipment->statusid)
                                                                                                selected="selected"
                                                                                            @endif>
                                                                                            {{$statu->name}}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
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
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <table id="table-incidencesjourney"
                                                       class="table table-bordered table-condensed table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Folio</th>
                                                        <th>Factura</th>
                                                        <th>IDH</th>
                                                        <th>Descripción</th>
                                                        <th>Cantidad</th>
                                                        <th>Unidad de medida</th>
                                                        <th>Concepto de reporte</th>
                                                        <th>Respuesta</th>
                                                        <th>Estatus de registro</th>
                                                        <th>Responsable</th>
                                                        <th>Estatus</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($details as $item)
                                                        <tr>
                                                            <td><input class="form-control" readonly
                                                                       name='folioiddetail[]' type="text"
                                                                       value="{{$item->folioid}}"></td>
                                                            <td>{{$item->delivery}}</td>
                                                            <td>{{$item->partnumberid}}</td>
                                                            <td>{{$item->partnumber ? $item->partnumber->lngdsc : ''}}</td>
                                                            <td> {{$item->quantity}} </td>
                                                            <td> {{$item->unitmeasure}} </td>
                                                            <td> {{$item->category ? $item->category->description : ''}} </td>
                                                            <td>
                                                                <input class="form-control" name='comment[]' required
                                                                       type="text" value="{{$item->comment}}">
                                                            </td>
                                                            <td>
                                                                <select class="form-control select2"
                                                                        id="statusiddetail-{{$loop->iteration}}[]"
                                                                        name="statusiddetail[]" required>
                                                                    <option value=""></option>
                                                                    @foreach($statusDetails as $statusD)
                                                                        <option value="{{$statusD->statusid}}"
                                                                                @if($statusD->statusid == $item->statusiddetail) selected="selected" @endif >{{$statusD->name}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control select2"
                                                                        id="responsible-{{$loop->iteration}}[]"
                                                                        name="responsible[]" required>
                                                                    <option value=""></option>
                                                                    @foreach($responsable as $respon)
                                                                        <option value="{{$respon->categoryincdetailid}}"
                                                                                @if($respon->categoryincdetailid == $item->auxiliary02) selected="selected" @endif >{{$respon->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td> {{$item->status->name}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane " id="view02" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            @can('searchPhotoInShipment')
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <a href="{{url('searchPhotoInShipment/' . $shipment->folioid)}}">
                                                            <button type="button"
                                                                    class="btn waves-effect waves-light btn-outline-info btn-block btn-md"
                                                                    id="saveResponseAccept">
                                                                <img src="{{asset('/ico/unitstatus.png')}}"
                                                                     alt="" class="img-16">
                                                                <span style="font-size: 11px;"> Buscar evidencias en embarques</span>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endcan
                                            <hr class="mt-3">
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
                                                                                                                                    <a href="{{url('destroyphotoincidence/' . $photo->id)}}"
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Subir evidencia de respuesta.
                                        </div>
                                        <div class="card-body">
                                            <form
                                                action="{{ url('uploadEvidenceForIncidences/'. $shipment->folioid .'/upload') }}"
                                                class="dropzone">
                                                {{ csrf_field() }}
                                                <div class="dz-message" data-dz-message>
                                                         <span style="font-size: 12px;color: #4db6ac;font-weight: 500">
                                                             Toca para abrir el selector de imagenes. <br><br>
                                                              <i class="fa fa-camera fa-4x"></i>
                                                         </span>
                                                </div>
                                                <div class="fallback">
                                                    <input name="file" type="file" multiple/>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-20" id="view03" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            @foreach($comments as $comment)
                                                <div class="comment-widgets scrollable">
                                                    <div class="d-flex flex-row comment-row m-t-0">
                                                        <div class="p-2">
                                                            <img src="{{asset('/ico/alarm_alert_bell_internet_notice_notification_security_icon_127089.png')}}"
                                                                              width="20"
                                                                              class="rounded-circle">
                                                        </div>
                                                        <div class="comment-text ">
                                                            <span class="m-b-15 d-block">{{$comment->comment}} </span>
                                                            <div class="comment-footer">
                                                                <span class="text-muted float-right">{{$comment->created_at}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="mb-2">
                                            @endforeach
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
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/dropzone/dist/min/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/magnific-popup/dist/magnific-popup.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/dropzone/dist/min/dropzone.min.js')}}"></script>
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/meg.init.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
        $(".radio-switch input[type='radio']").bootstrapSwitch();
    </script>
    <script>
        Dropzone.options.myAwesomeDropzone = {
            paramName: "file",
            maxFiles: 10,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFilesize: 8,
        };
    </script>

    <script>
        var oTable = $("#table-incidencesjourney").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "sScrollY": "450px",
            "sScrollX": true,
            "processing": true,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "dom": '<"top"B>lrt<"bottom"p><"clear"i>',
            "lengthMenu": [[50, 100, 150, 200, 250, -1], [50, 100, 150, 200, 250, "Todos"]],
            "columnDefs": [
                {"width": "100px", "targets": 0},
                {"width": "150px", "targets": 1},
                {"width": "150px", "targets": 2},
                {"width": "220px", "targets": 3},
                {"width": "120px", "targets": 4},
                {"width": "120px", "targets": 5},
                {"width": "220px", "targets": 6},
                {"width": "180px", "targets": 7},
                {"width": "180px", "targets": 8},
                {"width": "180px", "targets": 9},
                {"width": "180px", "targets": 10},
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
