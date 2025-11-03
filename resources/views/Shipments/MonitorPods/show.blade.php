@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de PODs
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{route('monitorpods.index')}}">
                                @include('buttons.back')
                            </a>
                            <a>
                                <button id="searchPODInRC" type="button"
                                        class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/adbuscar.png')}}" alt=""
                                         class="img-16 ml-1">
                                    <span style="font-size: 11px;"> Buscar POD</span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="shipmentid">Embarque</label>
                                <input type="text" class="form-control" readonly value="{{$shipment->shipmentid}}"
                                       id="shipmentid" name="shipmentid">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="workday">Fecha de trabajo</label>
                                <input type="text" class="form-control" readonly value="{{$shipment->workday}}"
                                       id="workday" name="workday">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="downloaddate">Fecha de descarga</label>
                                <input type="text" class="form-control" readonly value="{{$shipment->downloaddate}}"
                                       id="downloaddate" name="downloaddate">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div id="loader" style="display:none ">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Descargando documentos</h4>
                                        <div class="spinner-grow" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-secondary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-success" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-danger" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-warning" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-info" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-dark" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="cliente">Cliente</label>
                                <input type="text" class="form-control" readonly value="{{$shipment->customer->name}}"
                                       id="cliente" name="cliente">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="city">Ciudad</label>
                                <input type="text" class="form-control" readonly value="{{$shipment->customer->city}}"
                                       id="city" name="city">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="linea">Ciudad</label>
                                <input type="text" class="form-control" readonly value="{{$shipment->carrier->name}}"
                                       id="linea" name="linea">
                            </div>
                        </div>
                    </div>
                    <hr class="mt-3">
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <table class="table table-condensed table-bordered table-hover table-list">
                                <thead>
                                <tr>
                                    <th>Creado por</th>
                                    <th>F.Creación</th>
                                    <th>Archivo</th>
                                    <th>Validar</th>
                                    <th>Eliminar</th>
                                    <th>Estatus</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($files as $file)
                                    <tr>
                                        <td>{{$file->user ? $file->user->name : ''}}</td>
                                        <td>{{$file->user ? $file->created_at : ''}}</td>
                                        <td>
                                            <a href="{{asset('storage/'. $file->pathfile)}}" target="_blank">
                                                <img src="{{asset('/ico/pdf.png')}}" class="img-16" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            @can('monitorpods.updatestatusfile')
                                                <a href="" data-target="#modal-update-{{$file->recordid}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/pics/addesmarcar.png')}}" alt="" class="img-16">
                                                </a>
                                                @include('Shipments.MonitorPods.partials.updatestatus')
                                            @endcan
                                        </td>
                                        <td>
                                            @if($file->statusid != 'COR')
                                                @can('monitorpods.deletefilepod')
                                                    <a href="" data-target="#modal-delete-{{$file->recordid}}"
                                                       data-toggle="modal">
                                                        <img src="{{asset('/ico/adcancelar.png')}}" alt=""
                                                             class="img-16">
                                                    </a>
                                                    @include('Shipments.MonitorPods.partials.deletefile')
                                                @endcan
                                            @endif
                                        </td>
                                        <td>
                                            @if($file->statusid == 'PED')
                                                <span class="label label-inverse font-12 text-white">
                                                    {{$file->status->name}}
                                                </span>
                                            @elseif($file->statusid == 'INC')
                                                <span class="label label-danger font-12 text-white">
                                                    {{$file->status->name}}
                                                </span>
                                            @elseif($file->statusid == 'COR')
                                                <span class="label label-success font-12 text-white">
                                                    {{$file->status->name}}
                                                </span>
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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    Subir evidencia
                </div>
                <div class="card-body">
                    @can('monitorpods.uploadfile')
                        <div class="card-foto mt-3">
                            <form action="{{ url('uploadPdfToShipment/'. $shipment->id .'/uploadpdf') }}"
                                  class="dropzone" id="myAwesomeDropzone">
                                {{ csrf_field() }}
                                <div class="dz-message" data-dz-message>
                                        <span style="font-size: 12px;color: #4db6ac;font-weight: 500">
                                            Toca para abrir el selector de imagenes, las imagenes deben de tener un maximo de 20 mb. <br><br>
                                            <i class="fa fa-camera fa-4x"></i>
                                        </span>
                                </div>
                                <div class="fallback">
                                    <input name="file" type="file" multiple/>
                                </div>
                            </form>
                        </div>
                    @endcan
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/dropzone/dist/min/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".table-list").DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "300px",
                "dom": '<"top"B>lrt<"bottom"p><"clear"i>',
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
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
    <script src="{{asset('/theme/assets/libs/dropzone/dist/dropzone.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        Dropzone.options.myAwesomeDropzone = {
            paramName: "file",
            maxFiles: 1,
            acceptedFiles: ".pdf",
            chunking: true,
            chunkSize: 2000000,
            forceChunking: true,
            parallelChunkUploads: true,
            retryChunks: true,
            retryChunksLimit: 3,
            chunksUploaded: function (file, done) {
                done();
            },
            maxFilesize: 30,
            success: function (file, response) {
                Swal.fire({
                    type: 'success',
                    text: ` Documento cargado con exito ${response}`,
                    width: 250,
                })
                setInterval("actualizar()", 2000);
            },
            error: function (file, response) {
                Swal.fire({
                    type: 'error',
                    text: ` Error al subir el documento ${response}`,
                    width: 250,
                })
                setInterval("actualizar()", 2000);
            },
        };
    </script>
    <script type="text/javascript">
        function actualizar() {
            location.reload(true);
        }
    </script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#deletefile').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        recordid: $("#recordid").val(),
                    },
                    url: '{!! route('deletefilepod') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        $('#recordid').val('');
                        Swal.fire({
                            type: 'success',
                            text: 'Registro eliminado con exito.',
                            width: 250,
                        })
                        setInterval("actualizar()", 2000);
                    },

                });
            });
        });
    </script>
    <script>
        $('#searchPODInRC').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let shipmentid = $('#shipmentid').val();
            console.log(shipmentid)
            $.ajax({
                url: "{{url('searchPODInRecConfiable')}}",
                type: "POST",
                data: {
                    shipmentid: shipmentid,
                },
                beforeSend: function () {
                    $("#loader").show();
                },
                complete: function () {
                    $("#loader").hide();
                },
                success: function (result) {
                    console.log(result)
                    Swal.fire({
                        type: 'success',
                        title: `Documentos encontrados con exito`,
                        width: 350,
                    })
                    setInterval("actualizar()", 2000);
                },
            });
        });
    </script>

@endsection
