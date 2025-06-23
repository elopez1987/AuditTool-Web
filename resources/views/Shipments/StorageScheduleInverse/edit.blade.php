@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    Datos generales de Trafico
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('storagescheduleinverse.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" readonly id="shipmentid" name="shipmentid"
                                           value="{{$storagescheduleinverse->order->shipmentid}}"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="customerid">Cliente <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" readonly id="customerid" name="customerid"
                                           value="{{$storagescheduleinverse->order->customer->name}}"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" readonly id="carrierid" name="carrierid"
                                           value="{{$storagescheduleinverse->order->carrier->name}}"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Modificar Cita
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagescheduleinversetraffic/' . $storagescheduleinverse->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="appointment">Cita de entrage a Cedis
                                            @error("appointment")
                                            <span class="message-error">{{$message}}</span>
                                            @enderror
                                            <span style="color: red">*</span></label>
                                        <input type="text"
                                               class="form-control @error('appointment') is-invalid @enderror"
                                               id="appointment" name="appointment"
                                               aria-describedby="appointment"
                                               onKeyUp=this.value=this.value.toUpperCase();
                                               value="{{$storagescheduleinverse->appointment}}"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="folionumber">Folio de control de patios
                                            @error("folionumber")
                                            <span class="message-error">{{$message}}</span>
                                            @enderror
                                            <span style="color: red">*</span></label>
                                        <input type="text"
                                               class="form-control numeric @error('folionumber') is-invalid @enderror"
                                               id="folionumber" name="folionumber"
                                               aria-describedby="folionumber"
                                               onKeyUp=this.value=this.value.toUpperCase();
                                               value="{{$storagescheduleinverse->folionumber}}"
                                        >
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
                            <form method="POST" action="{{url('updateDataTraffic/' . $storagescheduleinverse->id)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            <button type="submit"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                                <span style="font-size: 11px;"> Guardar Detalle</span>
                                            </button>

                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="table-responsive">
                                    <table id="table-incidences"
                                           class="table table-bordered table-condensed table-striped table-hover table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Embarque</th>
                                                <th>Factura</th>
                                                <th>IDH</th>
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th>UM</th>
                                                <th>División</th>
                                                <th>Motivo</th>
                                                <th>Tipo</th>
                                                <th>F.Solicitud Zare</th>
                                                <th>F.Respuesta Zare</th>
                                                <th>Zare</th>
                                                <th>F.Solicitud NE</th>
                                                <th>F.Recepción NE</th>
                                                <th>Nota de Entrega</th>
                                                <th>¿Procede ajuste?</th>
                                                <th>F.Solicitud NC</th>
                                                <th>F.Recepción NC</th>
                                                <th>Nota de credito</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($details as $item)
                                            <tr>
                                                <th>{{$item->shipmentid}}</th>
                                                <th>
                                                    <input type="text" readonly value="{{$item->delivery}}"
                                                           class="form-control" name="delivery[]">
                                                </th>
                                                <th>
                                                    <input type="text" readonly value="{{$item->partnumberid}}"
                                                           class="form-control" name="partnumberid[]">
                                                </th>
                                                <th>{{$item->description}}</th>
                                                <th>{{$item->quantity}}</th>
                                                <th>{{$item->unitmeasure}}</th>
                                                <th>{{$item->div}}</th>
                                                <th>{{$item->type}}</th>
                                                <th>
                                                    <select class="form-control select2"
                                                            id="type-{{$loop->iteration}}[]"
                                                            name="type[]" required>
                                                        <option value=""></option>
                                                        <option value="FIS"
                                                                @if($item->typereport == 'FIS') selected="selected" @endif >
                                                            Fisico
                                                        </option>
                                                        <option value="VIR"
                                                                @if($item->typereport == 'VIR') selected="selected" @endif >
                                                            Virtual
                                                        </option>
                                                    </select>
                                                </th>
                                                <th>
                                                    <input type="text" class="datefilter form-control"
                                                           value="{{$item->requestzare}}" name="requestzare[]">
                                                </th>
                                                <th>
                                                    <input type="text" class="datefilter form-control"
                                                           value="{{$item->responsezare}}" name="responsezare[]">
                                                </th>
                                                <th>
                                                    <input type="text" class="numeric form-control"
                                                           value="{{$item->zare}}"
                                                           name="zare[]">
                                                </th>
                                                <th>
                                                    <input type="text" class="datefilter form-control"
                                                           value="{{$item->requestne}}" name="requestne[]">
                                                </th>
                                                <th>
                                                    <input type="text" class="datefilter form-control"
                                                           value="{{$item->responsene}}" name="responsene[]">
                                                </th>
                                                <th>
                                                    <input type="text" class="numeric form-control"
                                                           value="{{$item->ne}}"
                                                           name="ne[]">
                                                </th>
                                                <th>
                                                    <select class="form-control select2"
                                                            id="adjust-{{$loop->iteration}}[]"
                                                            name="adjust[]" required>
                                                        <option value=""></option>
                                                        <option value="S"
                                                                @if($item->adjust == 'S') selected="selected" @endif >SI
                                                        </option>
                                                        <option value="N"
                                                                @if($item->adjust == 'N') selected="selected" @endif >No
                                                        </option>
                                                    </select>
                                                </th>
                                                <th>
                                                    <input type="text" class="datefilter form-control"
                                                           value="{{$item->requestnc}}" name="requestnc[]">
                                                </th>
                                                <th>
                                                    <input type="text" class="datefilter form-control"
                                                           value="{{$item->responsenc}}" name="responsenc[]">
                                                </th>
                                                <th>
                                                    <input type="text" class="numeric form-control"
                                                           value="{{$item->nc}}"
                                                           name="nc[]">
                                                </th>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script
        src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script>
        $('#appointment').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
    </script>
    <script>
        var oTable = $("#table-incidences").DataTable({
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
            // "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],

            "dom": '<"top"B>lrt<"bottom"p><"clear"i>',
            "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
            "columnDefs": [
                {"width": "100px", "targets": 0},
                {"width": "150px", "targets": 1},
                {"width": "150px", "targets": 2},
                {"width": "250px", "targets": 3},
                {"width": "120px", "targets": 4},
                {"width": "120px", "targets": 5},
                {"width": "120px", "targets": 6},
                {"width": "120px", "targets": 7},
                {"width": "120px", "targets": 8},
                {"width": "120px", "targets": 9},
                {"width": "120px", "targets": 10},
                {"width": "120px", "targets": 11},
                {"width": "120px", "targets": 12},
                {"width": "120px", "targets": 13},
                {"width": "120px", "targets": 14},
                {"width": "120px", "targets": 15},
                {"width": "120px", "targets": 16},
                {"width": "120px", "targets": 17},
                {"width": "120px", "targets": 18},
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


