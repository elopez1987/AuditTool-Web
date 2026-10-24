@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Usuarios activos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">

                    </form>
                    <div class="row">
                        <div class="col-lg-10 col-md-12 col-sm-12">
                            <table width="100%" id="table-users" class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Creado</th>
                                    <th>Expira</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{url('clearsessions')}}">
                        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                            <img src="{{asset('/ico/engine_gear_internet_option_security_setting_web_icon_127066.png')}}"
                                    alt="" class="img-16">
                            <span class="text-button"> Limpiar sesiones</span>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Security.ActiveUsers.partials.table')
    <script>
        function reloadtable() {
            $('#table-users').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#roleid,#statusid").change(function () {
            reloadtable();
        });
    </script>
@endsection
