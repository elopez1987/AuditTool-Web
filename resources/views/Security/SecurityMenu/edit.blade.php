@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Estructura de Menu
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{url('securitymenustructure/' . $securitymenustructure->menustrucid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securitymenustructure.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Security.SecurityMenu.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar modulos al menu
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="POST"
                                  action="{{url('savedatailmenustructure/' . $securitymenustructure->menustrucid)}}">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <a>
                                            @include('buttons.save')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <select multiple="multiple" size="15" class="duallistbox-with-filter" name="moduleid[]"
                                        required>
                                    @foreach($modules as $modul)
                                        <option value="{{$modul->moduleid}}"> {{$modul->description}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mt-5">
                                <div class="col-lg-12">
                                    <table class="table table-hover table-bordered table-condensed tablegeneral ">
                                        <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($modulesDetails as $module)
                                            <tr>
                                                <td>{{$module->module->description}}</td>
                                                <td>
                                                    <a data-toggle="tooltip" data-placement="top"
                                                       title="Eliminar registro"
                                                       onclick="deleteDetailMenu( {!! $module->id!!} , '{!!$module->menustrucid!!}' )">
                                                        <img src="{{asset('/ico/addestroy1.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                    </a>
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
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar funciones al menu
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="POST"
                                  action="{{url('savefunctionsformenustructure/' . $securitymenustructure->menustrucid)}}">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <a>
                                            @include('buttons.save')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <select multiple="multiple" size="15"
                                        class="duallistbox-with-filter"
                                        name="functionid[]"
                                        required>
                                    @foreach($functions as $fuction)
                                        <option value="{{$fuction->functionid}}"> {{$fuction->description}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mt-5">
                                <div class="col-lg-12">
                                    <table class="table table-hover table-bordered table-condensed tablegeneral">
                                        <thead>
                                        <tr>
                                            <th>Modulo</th>
                                            <th>Funcion</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($functionsDetails as $function)
                                            <tr>
                                                <td>{{$function->module->description}}</td>
                                                <td>{{$function->funtion->description}}</td>
                                                <td>
                                                    <a data-toggle="tooltip" data-placement="top"
                                                       title="Eliminar registro"
                                                       onclick="deleteFunctionMenu( {!! $function->id !!} , '{!! $function->menustrucid !!}' )">
                                                        <img src="{{asset('/ico/addestroy1.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                    </a>
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
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection


@section('script')
    <script>
        var oTable =   $(".tablegeneral").DataTable({
            "paging"      : true,
            "searching"  : true,
            "ordering"    : true,
            "serverSide" :false,
            'colReorder': true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],

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
    <script
        src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>

    <script>
        $('.duallistbox-with-filter').bootstrapDualListbox({
            nonSelectedListLabel: 'No hay registros selecionados:',
            selectedListLabel: 'Seleccionado',
            preserveSelectionOnMove: 'Mover',
            moveOnSelect: false,
        });
        $(function () {
            $('.moveall i').removeClass().addClass('fas fa-angle-right');
            $('.removeall i').removeClass().addClass('fas fa-angle-left');
            $('.move i').removeClass().addClass('fas fa-angle-right');
            $('.remove i').removeClass().addClass('fas fa-angle-left');
        });
    </script>

    <script>
        function deleteDetailMenu($id, $menustrucid) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "El siguiente evento eliminara el modulo del menu seleccionado!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar modulo!'
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
                            menustrucid: $menustrucid,
                        },
                        url: '{!! route('destroyDetailMenu') !!}',
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
        function deleteFunctionMenu($id, $menustrucid) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "El siguiente evento eliminara la función del menu seleccionado!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar función!'
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
                            menustrucid: $menustrucid,
                        },
                        url: '{!! route('deletefunctionformenu') !!}',
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
                            }, 1000)
                        },
                    })

                }
            })
        }
    </script>

@endsection

