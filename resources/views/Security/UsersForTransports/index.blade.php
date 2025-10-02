@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Usuarios por línea de transporte.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12 ">
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menusecurity')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-2">
                    </form>
                    <div class="card-input">
                        <div class="row mt-3">
                            <div class="col-lg-12 ">
                                <table id="table-userstransports" width="100%"
                                       class="table table-bordered table-striped table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Nombre</th>
                                        <th class="bg-inverse text-white">Usuario</th>
                                        <th class="bg-inverse text-white">Correo</th>
                                        <th class="bg-inverse text-white">Línea de transporte</th>
                                        <th class="bg-inverse text-white">Vendor</th>
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
@endsection

@section('css')

@endsection


@section('script')
    @include('Security.UsersForTransports.partials.table')
    <script>
        function reloadtable() {
            $('#table-userstransports').DataTable().ajax.reload();
        }
    </script>
@endsection
