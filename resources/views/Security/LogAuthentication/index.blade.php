@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Logs de inicio de sesión
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
                            <table width="100%" id="table-logins"
                                   class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">ID</th>
                                    <th class="bg-inverse text-white">UserName</th>
                                    <th class="bg-inverse text-white">User ID</th>
                                    <th class="bg-inverse text-white">Dirección IP</th>
                                    <th class="bg-inverse text-white">Estado</th>
                                    <th class="bg-inverse text-white">Detalle</th>
                                    <th class="bg-inverse text-white">Pais</th>
                                    <th class="bg-inverse text-white">Region</th>
                                    <th class="bg-inverse text-white">Ciudad</th>
                                    <th class="bg-inverse text-white">Time Zone</th>
                                    <th class="bg-inverse text-white">Path</th>
                                    <th class="bg-inverse text-white">Fecha de evento</th>
                                    <th width="5%" class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Security.LogAuthentication.partials.table')
    <script>
        function reloadtable() {
            $('#table-logins').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#roleid,#statusid").change(function () {
            reloadtable();
        });
    </script>
@endsection
