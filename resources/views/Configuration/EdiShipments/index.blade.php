@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Interface pendiente de procesar
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{route('home')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-logs" class="table table-bordered table-hover table-condensed edilog">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Referencia</th>
                                    <th class="bg-inverse text-white">Cliente externo</th>
                                    <th class="bg-inverse text-white">Evento</th>
                                    <th class="bg-inverse text-white">Cantidad de envios</th>
                                    <th class="bg-inverse text-white">Modelo</th>
                                    <th class="bg-inverse text-white">F.Alta</th>
                                    <th class="bg-inverse text-white">Mensaje</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Eventos</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{$item->center->description}}</td>
                                        <td>{{$item->warehouse->name}}</td>
                                        <td>{{$item->shipmentid}}</td>
                                        <td>{{$item->reference_number}}</td>
                                        <td>{{$item->external_system}}</td>
                                        <td>{{$item->event_type}}</td>
                                        <td>{{$item->retry_count}}</td>
                                        <td>{{$item->model_type}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->error_message}}</td>
                                        @php
                                            $colors = [
                                                'Completed' => '#00e676',
                                                'Error'     => '#ff1744',
                                                'Pending'   => '#ffff00',
                                            ];
                                            $color = $colors[$item->status] ?? '#ffff00';
                                        @endphp
                                        <td style="background-color: {{ $color }}">
                                            {{ $item->status }}
                                        </td>
                                        <td>
                                            @if($item->status != 'Completed')
                                                <a href="" data-target="#modal-delete-{{$item->id}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                                                </a>
                                                @include('Shipments.EdiShipments.partials.delete')
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
    </div>

@endsection




@section('script')
    <script>
        $(document).ready(function () {
            $(".edilog").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "bDestroy": true,
                "sScrollY": "600px",
                "serverSide": false,
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                buttons: [
                    {
                        "extend": "colvis",
                        "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                        columns: ':not(:first):not(:last)'
                    },
                    {
                        "extend": "copy",
                        "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 11px;'> Copiar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
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
                    "emptyTable": "Todos los registros procesados con exito!!",
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

