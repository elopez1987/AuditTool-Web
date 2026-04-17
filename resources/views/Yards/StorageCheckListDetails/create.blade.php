@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Agregar detalle de check list
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <form method="POST"
                                  action="{{url('storechecklistdetails/' . $storagechecklist->id)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('storagechecklists.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                @include('Yards.StorageCheckListDetails.partials.formCreate')
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10">
                            <table width="100%" id="table-detailchecklist"
                                   class="table table-condensed  table-bordered ">
                                <thead>
                                <tr>
                                    <th># ID</th>
                                    <th>Descripción</th>
                                    <th>Auxiliary 01</th>
                                    <th>Auxiliary 02</th>
                                    <th>Auxiliary 03</th>
                                    <th>Auxiliary 04</th>
                                    <th>Auxiliary 05</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{$detail->id}}</td>
                                        <td>{{$detail->description}}</td>
                                        <td>{{$detail->auxiliary01}}</td>
                                        <td>{{$detail->auxiliary02}}</td>
                                        <td>{{$detail->auxiliary03}}</td>
                                        <td>{{$detail->auxiliary04}}</td>
                                        <td>{{$detail->auxiliary05}}</td>
                                        <td>
                                            @if($detail->statusid == 'E')
                                                <span
                                                    class="label label-green font-table text-black">{{$detail->status->name}}</span>
                                            @elseif($detail->statusid == 'D')
                                                <span
                                                    class="label label-red font-table text-white">{{$detail->status->name}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="" data-target="#modal-updatestatus-{{$detail->id}}"
                                               data-toggle="modal">
                                                <img src="{{asset('/ico/typemovements.png')}}" alt=""
                                                     class="img-16 ml-2">
                                            </a>
                                            @include('Yards.StorageCheckListDetails.updatestatus')
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <a href="" data-target="#modal-importchecklistdetails" data-toggle="modal"
                       data-placement="top">
                        <button type="button"
                                class="btn waves-effect waves-light btn-outline-info btn-md"
                                data-toggle="tooltip" data-placement="top" title="Importar archivo">
                            <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                            <span> Importar Detalle </span>
                        </button>
                    </a>
                    @include('Yards.StorageCheckListDetails.Import.importdetails')
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')
    <script>
        var oTable = $("#table-detailchecklist").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "serverSide": false,
            "sScrollY": "350px",
            "sScrollX": true,
            'colReorder': true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],
            "columnDefs":
                [
                    {"width": "50px", "targets": 0},
                    {"width": "300px", "targets": 1},
                    {"width": "150px", "targets": 2},
                    {"width": "150px", "targets": 3},
                    {"width": "150px", "targets": 4},
                    {"width": "150px", "targets": 5},
                    {"width": "150px", "targets": 6},
                    {"width": "120px", "targets": 7},
                    {"width": "120px", "targets": 8},
                ],
            buttons: [
                {
                    "extend": "colvis",
                    "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Mostar/Ocultar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                    columns: ':not(:first):not(:last)'
                },
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Excel</span>",
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

