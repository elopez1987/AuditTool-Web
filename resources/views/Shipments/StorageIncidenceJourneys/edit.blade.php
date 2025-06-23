@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar detalle de incidencia
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('storageincidencesjourneys.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    @include('Shipments.StorageIncidenceJourneys.partials.formEdit')
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-10">
                            <table class="table table-striped table-bordered table-condensed table-list">
                                <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>IDH</th>
                                    <th>Cantidad</th>
                                    <th>U.Medida</th>
                                    <th>Concepto</th>
                                    <th>IDH Error</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{$detail->delivery}}</td>
                                        <td>{{$detail->partnumberid}}</td>
                                        <td>{{$detail->quantity}}</td>
                                        <td>{{$detail->unitmeasure}}</td>
                                        <td>{{$detail->category->description}}
                                        <td>{{$detail->partnumberiderror}}</td>
                                        <td>{{$detail->status->name}}
                                        <td>
                                            @can('storageincidencesjourneys.updatedetail')
                                                <a href="" data-target="#modal-updatedetail-{{$detail->folioid}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/adedit.png')}}" alt=""
                                                         class="img-16">
                                                </a>
                                                @include('Shipments.StorageIncidenceJourneys.updatedetail')
                                            @endcan
                                            @can('storageincidencesjourneys.deletedetail')
                                                <a href="" data-target="#modal-deletedetail-{{$detail->folioid}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/adcancelar.png')}}" alt=""
                                                         class="img-16">
                                                </a>
                                                @include('Shipments.StorageIncidenceJourneys.deletedetail')
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-2">
                            <div class="card mt-5">
                                <div class="card-header">
                                    Evidencia de entrega
                                </div>
                                <div class="card-body">
                                    <form action="{{ url('uploadevidenceshipment/'. $shipment->folioid .'/upload') }}"
                                          class="dropzone" id="myAwesomeDropzone">
                                        {{ csrf_field() }}
                                        <div class="dz-message" data-dz-message>
                                        <span style="font-size: 12px;color: #4db6ac;font-weight: 500">
                                            Toca para abrir el selector de imagenes, las imagenes deben de tener un maximo de 8 mb. <br><br>
                                            <i class="fa fa-camera fa-4x"></i>
                                        </span>
                                        </div>
                                        <div class="fallback">
                                            <input name="file" type="file" multiple/>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer">
                                    YardTrax
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
    <link rel="stylesheet"  href="{{asset('/theme/assets/libs/dropzone/dist/min/dropzone.min.css')}}">
@endsection

@section('script')
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
    <script>
        Dropzone.options.myAwesomeDropzone = {
            paramName: "file",
            maxFiles: 10,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFilesize: 8,
        };
    </script>
@endsection

