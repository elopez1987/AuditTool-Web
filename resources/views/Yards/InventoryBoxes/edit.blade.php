@php use Illuminate\Support\Carbon; @endphp
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
                                        <button type="submit"
                                                class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                            <span> Cerrar Inventario</span>
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
                            <table class="table table-hover table-condensed table-bordered  tablegeneral">
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

                                <th class="bg-inverse text-white">Origen de caja</th>
                                <th class="bg-inverse text-white">Concepto de entrada</th>
                                <th class="bg-inverse text-white">Tipo de unidad</th>
                                <th class="bg-inverse text-white">Categoria</th>
                                <th class="bg-inverse text-white">Fecha de entrada</th>
                                <th class="bg-inverse text-white">Estadia</th>


                                <th class="bg-inverse text-white">Zona</th>
                                <th class="bg-inverse text-white">Ubicación</th>
                                <th class="bg-inverse text-white">Nueva Zona</th>
                                <th class="bg-inverse text-white">Nueva Ubicación</th>
                                <th class="bg-inverse text-white">Estatus</th>
                                <th class="bg-inverse text-white">Estatus de inventario</th>
                                <th class="bg-inverse text-white">Usuario</th>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{$item->center ? $item->center->description : ''}}</td>
                                        <td>{{$item->countid}}</td>
                                        <td>{{$item->inventory ? $item->inventory->description : ''}}</td>
                                        <td>{{$item->folionumber}}</td>
                                        <td>{{$item->folio ? $item->folio->carrier->name : ''}}</td>
                                        <td>{{$item->barcode}}</td>
                                        <td>{{$item->boxnumber}}</td>
                                        <td>{{$item->folio ? $item->folio->econumber : ''}}</td>
                                        <td>{{$item->folio ? $item->folio->unitstatus->name : ''}}</td>
                                        <td>{{$item->folio ? $item->folio->unittype->description : ''}}</td>
                                        <td>{{$item->folio ? $item->folio->reason->name : ''}}</td>
                                        <td>{{$item->folio ? $item->folio->truckcategory->description : ''}}</td>
                                        <td>{{$item->folio ? $item->folio->truck->name : ''}}</td>
                                        <td>{{$item->folio ? $item->folio->entrydate : ''}}</td>
                                        <td>{{Carbon::now()->diff($item->folio->entrydate)->format('%m m %d d %H:%i h/m')}}</td>
                                        <td>{{$item->zoneold ? $item->zoneold->name : ''}}</td>
                                        <td>{{$item->locationold ? $item->locationold->name : ''}}</td>
                                        <td>{{$item->zonenew ? $item->zonenew->name : ''}}</td>
                                        <td>{{$item->locationnew ? $item->locationnew->name : ''}}</td>
                                        <td>{{$item->status ? $item->status->name : ''}}</td>
                                        <td
                                                @if ($item->isupdate == 'Y')
                                                    class="bg-cyan text-white text-center"
                                                @endif
                                                @if ($item->isupdate == 'P')
                                                    class="bg-warning  text-center"
                                                @endif
                                                @if ($item->isupdate == 'C')
                                                    class="bg-green  text-center"
                                                @endif
                                        >
                                            @if ($item->isupdate == 'Y')
                                                Ajustada
                                            @endif
                                            @if ($item->isupdate == 'P')
                                                Pendiente
                                            @endif
                                            @if ($item->isupdate == 'C')
                                                Correcto
                                            @endif
                                        </td>
                                        <td>{{$item->user ? $item->user->name : ''}}</td>
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

            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[-1], ["Todos"]],
            "columnDefs": [
                {"width": "280px", "targets": 0},
                {"width": "100px", "targets": 1},
                {"width": "250px", "targets": 2},
                {"width": "120px", "targets": 3},
                {"width": "290px", "targets": 4},
                {"width": "150px", "targets": 5},
                {"width": "160px", "targets": 6},
                {"width": "160px", "targets": 7},
                {"width": "160px", "targets": 8},
                {"width": "160px", "targets": 9},
                {"width": "280px", "targets": 10},
                {"width": "160px", "targets": 11},
                {"width": "160px", "targets": 12},
                {"width": "200px", "targets": 13},
                {"width": "180px", "targets": 14},
                {"width": "250px", "targets": 15},
                {"width": "250px", "targets": 16},
                {"width": "250px", "targets": 17},
                {"width": "250px", "targets": 18},
                {"width": "250px", "targets": 19},
                {"width": "280px", "targets": 20},
                {"width": "280px", "targets": 21},
            ],
            buttons: [
                {
                    "extend": "copy",
                    "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: .675rem;'> Copiar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: .675rem;'> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: .675rem;'> Excel</span>",
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

