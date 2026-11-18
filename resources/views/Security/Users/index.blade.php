@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modulo de usuarios
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12 ">
                                <a href="{{route('securityusers.create')}}">
                                    @include('buttons.add')
                                </a>
                                <a>
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-2">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="userid">Usuario</label>
                                        <input type="text" class="form-control" id="userid" name="userid"
                                               aria-describedby="userid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email">Correo electronico</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                               aria-describedby="email">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="role_id">Perfil de usuario</label>
                                        <select name="role_id" id="role_id" class="form-control select2">
                                            <option value="">Seleccione una opción</option>
                                            @foreach($roles as $rol)
                                                <option value="{{$rol->id}}"> {{$rol->description}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="statusid">Estatus</label>
                                        <select name="statusid" id="statusid" class="form-control select2">
                                            <option value="">Seleccione una opción</option>
                                            @foreach($status as $model)
                                                <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-input">
                        <div class="row mt-3">
                            <div class="col-lg-12 ">
                                <table id="table-users" class="table table-bordered  table-condensed table-hover" width="100%">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Nombre</th>
                                        <th class="bg-inverse text-white">Usuario</th>
                                        <th class="bg-inverse text-white">Correo</th>
                                        <th class="bg-inverse text-white">Perfil</th>
                                        <th class="bg-inverse text-white">Estatus</th>
                                        <th class="bg-inverse text-white">F.Creación</th>
                                        <th class="bg-inverse text-white">F.Actualización</th>
                                        <th class="bg-inverse text-white">F.Ultimo Ingreso</th>
                                        <th class="bg-inverse text-white">Opciones</th>
                                    </tr>
                                    </thead>
                                </table>
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
                    Importadores
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @can('users.import')
                                <a href="" data-target="#modal-importusers" data-toggle="modal" data-placement="top">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md"
                                            data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                        <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Importar Usuarios </span>
                                    </button>
                                </a>
                                @include('Security.Users.Imports.importusers')
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
@endsection


@section('script')
    @include('Security.Users.partials.table')
    <script>
        function reloadtable() {
            $('#table-users').DataTable().ajax.reload();
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script type="text/javascript">
        let route = "{{url('autocompletuser')}}";
        $('#name').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    return process(data);
                });
            }
        });
    </script>
    <script>
        $("#statusid,#role_id").change(function () {
            reloadtable();
        });
        $("#userid").blur(function () {
            reloadtable();
        });

        // Función para enviar correo de reset de contraseña
        {{--function sendPasswordReset(uuiduser, email) {--}}
        {{--    event.preventDefault();--}}
        {{--    --}}
        {{--    Swal.fire({--}}
        {{--        title: '¿Enviar correo de reinicio?',--}}
        {{--        html: `Se enviará un correo de reinicio de contraseña a:<br><strong>${email}</strong>`,--}}
        {{--        icon: 'question',--}}
        {{--        showCancelButton: true,--}}
        {{--        confirmButtonColor: '#3085d6',--}}
        {{--        cancelButtonColor: '#d33',--}}
        {{--        confirmButtonText: 'Sí, enviar',--}}
        {{--        cancelButtonText: 'Cancelar'--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.isConfirmed) {--}}
        {{--            // Crear un form temporal para enviar la solicitud POST--}}
        {{--            const form = document.createElement('form');--}}
        {{--            form.method = 'POST';--}}
        {{--            form.action = `/securityusers/${uuiduser}/send-password-reset`;--}}
        {{--            --}}
        {{--            // Agregar token CSRF--}}
        {{--            const csrfToken = document.createElement('input');--}}
        {{--            csrfToken.type = 'hidden';--}}
        {{--            csrfToken.name = '_token';--}}
        {{--            csrfToken.value = '{{ csrf_token() }}';--}}
        {{--            form.appendChild(csrfToken);--}}
        {{--            --}}
        {{--            // Agregar al DOM y enviar--}}
        {{--            document.body.appendChild(form);--}}
        {{--            form.submit();--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}
    </script>
@endsection
