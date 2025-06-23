@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    Modificación de perfil
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="{{url('securityroles/' . $securityrole->id)}}">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('securityroles.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mt-3">
                                @include('Security.Roles.partials.formEdit')
                            </form>
                        </div>
                    </div>
                    <form method="POST" action="{{url('updatePermissionToRole/' . $securityrole->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-lg-3">
                                <a href="">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-danger">
                                        <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Asignar permisos</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <hr class="mt-4">
                        <div class="row">
                            <div class="col-lg-10" style="height: 500px;width: 200px;overflow-y: scroll;">
                                <ul id="myUL">
                                    @foreach($modules as $module)
                                        <li>
                                        <span class="caret"
                                              style="font-weight: 600;color: #0e2259;font-size: 12px;">{{$module->description}}</span>
                                            <ul class="nested">
                                                @foreach($functions as $function)
                                                    @if ($module->moduleid == $function->moduleid)
                                                        <li>
                                                        <span class="caret"
                                                              style="font-size: 12px;color: #0e2259;font-weight: 500;">{{$function->description}}</span>
                                                            <ul class="nested">
                                                                @foreach($permissions as $permission)
                                                                    @if ($permission->functionid == $function->functionid )
                                                                        <li class=" text-capitalize"
                                                                            style="font-size: 11px">
                                                                            @if (!empty($access))
                                                                                <input
                                                                                    type="checkbox"
                                                                                    class="font-16"
                                                                                    value="{{$permission->id}}"
                                                                                    @foreach ($access as $check)
                                                                                        @if ($check->permission_id == $permission->id)
                                                                                            checked
                                                                                    @endif
                                                                                    @endforeach
                                                                                    name="permissions[]"
                                                                                >
                                                                                <span class="text-dark"
                                                                                      style="font-size: 11px">{{$permission->description}}</span>
                                                                            @else
                                                                                <input type="checkbox"
                                                                                       value="{{$permission->id}}"
                                                                                       name="permissions[]">
                                                                                <span
                                                                                    style="font-size: 11px">{{$permission->description}}</span>
                                                                            @endif
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Permisos Asignados</div>
                        <div class="card-body">
                            <table class="table table-condensed table-bordered table-striped" id="table-assing">
                                <thead>
                                <tr>
                                    <th>Modulo</th>
                                    <th>Función</th>
                                    <th>Descripión</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($assings as $item)
                                    <tr>
                                        <td>{{$item->permission->module->description}}</td>
                                        <td>{{$item->permission->function->description}}</td>
                                        <td>{{$item->permission->description}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">Parametros de perfil</div>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td style="width: 50px;">
                                        <input
                                            type="checkbox"
                                            name="param-user-check"
                                            id="param-user-check"
                                            data-size="mini" data-on-color="success" data-off-color="danger"
                                            data-on-text="<i class='ti ti-check'></i>"
                                            data-off-text="<i class='ti ti-close'></i>"
                                        >
                                    </td>
                                    <td class="font-12">Los usuarios que tengan este perfil asignado podran ser asignados
                                        para el proceso de chek list de montacargas en el modulo de mantenimiento.</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
    <script>
        $(document).ready(function(){
            var checkMtn = <?php echo $checkMtn; ?>;
            if(checkMtn === 1) {
                $('#param-user-check').prop('checked', true); // Marca el checkbox
            } else {
                $('#param-user-check').prop('checked', false); // Desmarca el checkbox
            }
        });
    </script>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#param-user-check').change(function(){
                let isChecked = $(this).is(":checked");
                if(isChecked) {
                    $.ajax({
                        url: '{{url('registercheckforchecklistverific')}}',
                        method: 'POST',
                        data: { id: {{$securityrole->id}} },
                        success: function(data) {
                            Swal.fire({
                                type: 'success',
                                title: `Parametro asignado correctamente`,
                                width: 350,
                            })
                        },
                    });
                } else {
                    $.ajax({
                        url: '{{url('deletecheckforchecklistverific')}}',
                        method: 'POST',
                        data: { id: {{$securityrole->id}}},
                        success: function(data) {
                            Swal.fire({
                                type: 'success',
                                title: `Parametro eliminado correctamente`,
                                width: 350,
                            })
                        },
                    });
                }
            });
        });
    </script>
    <script>
        let oTable = $("#table-assing").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "sScrollY": "350px",
            // "sScrollX": true,
            "processing": true,
            // "serverSide": true,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,

            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
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
    <script>
        let toggler = document.getElementsByClassName("caret");
        let i;
        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function () {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("caret-down");
            });
        }
    </script>
@endsection

