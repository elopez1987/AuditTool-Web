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
                                <a href="{{route('menusecurity')}}">
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
