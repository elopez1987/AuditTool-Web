@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de inventario de cajas
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorybox/' . $inventorybox->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @if ($inventorybox->statusid != 'TO')
                                    <a href="">
                                        <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                            <span style="font-size: 0.675rem "> Cerrar Inventario</span>
                                        </button>
                                    </a>
                                @endif
                                <a href="{{route('inventorybox.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        @include('Yards.InventoryBoxes.partials.formEdit')
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-hover table-condensed table-bordered table-striped tablegeneral">
                                <thead>
                                <th class="bg-inverse text-white">C.Distribución</th>
                                <th class="bg-inverse text-white">Inventario</th>
                                <th class="bg-inverse text-white">Descripción</th>
                                <th class="bg-inverse text-white">Folio</th>
                                <th class="bg-inverse text-white">Linea de transporte</th>
                                <th class="bg-inverse text-white">C.Barra</th>
                                <th class="bg-inverse text-white">Placa de caja</th>
                                <th class="bg-inverse text-white">Eco. de caja</th>
                                <th class="bg-inverse text-white">Estatus de caja</th>
                                <th class="bg-inverse text-white">Zona</th>
                                <th class="bg-inverse text-white">Ubicación</th>
                                <th class="bg-inverse text-white">Nueva Zona</th>
                                <th class="bg-inverse text-white">Nueva Ubicación</th>
                                <th class="bg-inverse text-white">Estatus</th>
                                <th class="bg-inverse text-white">¿Ajustada?</th>
                                <th class="bg-inverse text-white">Usuario</th>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{$item->centername}}</td>
                                        <td>{{$item->countid}}</td>
                                        <td>{{$item->inventory->description}}</td>
                                        <td>{{$item->folionumber}}</td>
                                        <td>{{$item->folio->carrier->name}}</td>
                                        <td>{{$item->barcode}}</td>
                                        <td>{{$item->boxnumber}}</td>
                                        <td>{{$item->folio ? $item->folio->econumber : ''}}</td>
                                        <td>{{$item->folio->unitstatus->name}}</td>
                                        <td>{{$item->zoneid}}</td>
                                        <td>{{$item->location}}</td>
                                        <td>{{$item->newzoneid}}</td>
                                        <td>{{$item->newlocationid}}</td>
                                        <td>{{$item->status}}</td>
                                        <td
                                            @if ($item->isupdate == 'Y')
                                                class="bg-cyan text-white text-center"
                                            @endif
                                        >
                                            @if ($item->isupdate == 'Y')
                                                SI
                                            @endif
                                        </td>
                                        <td>{{$item->userid}}</td>
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
        var oTable = $(".tablegeneral").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "serverSide": false,
            "sScrollY": "400px",
            "sScrollX": true,
            "processing": true,


            "autoFill": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,

            "dom": '<"top"B>lrt<"bottom"p><"clear"i>',
            "lengthMenu": [[-1], ["Todos"]],
            "columnDefs": [
                {"width": "100px", "targets": 0},
                {"width": "60px", "targets": 1},
                {"width": "150px", "targets": 2},
                {"width": "120px", "targets": 3},
                {"width": "250px", "targets": 4},
                {"width": "100px", "targets": 5},
                {"width": "100px", "targets": 6},
                {"width": "100px", "targets": 7},
                {"width": "100px", "targets": 8},
                {"width": "100px", "targets": 9},
                {"width": "100px", "targets": 10},
                {"width": "100px", "targets": 11},
                {"width": "120px", "targets": 12},
                {"width": "100px", "targets": 13},
                {"width": "180px", "targets": 14},
                {"width": "180px", "targets": 15},
            ],
            buttons: [
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

