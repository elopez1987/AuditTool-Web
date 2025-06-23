@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modulos del sistema
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('securitymodules.create')}}">
                                    @include('buttons.add')
                                </a>
                                <a>
                                    @include('buttons.search')
                                </a>
                                <a >
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menusecurity')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Modulo</label>
                                    <input type="text" class="form-control"  id="description" name="description">
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-modules" class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Secuencia</th>
                                        <th class="bg-inverse text-white">Descripción</th>
                                        <th class="bg-inverse text-white">Auxiliar 01</th>
                                        <th class="bg-inverse text-white">Auxiliar 02</th>
                                        <th class="bg-inverse text-white">Auxiliar 03</th>
                                        <th class="bg-inverse text-white">Auxiliar 04</th>
                                        <th class="bg-inverse text-white">Auxiliar 05</th>
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
@endsection

@section('script')
    @include('Security.SecurtiyModules.partials.table')
    <script>
        function reloadtable() {
            $('#table-modules').DataTable().ajax.reload();
        }
    </script>
@endsection
