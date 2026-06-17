@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                  Incidencias encontradas
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
{{--                                @can('storage-incidence-safeties.create')--}}
{{--                                    <a href="{{route('storage-incidence-safeties.create')}}">--}}
{{--                                        @include('buttons.add')--}}
{{--                                    </a>--}}
{{--                                @endcan--}}
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                @include('buttons.reloadajax')
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                    </form>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card border-success">
                                <div class="card-header bg-success">
                                    <h4 class="mb-0 ">Terminadas</h4></div>
                                <div class="card-body">
                                    <h3 class="card-title">45</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning">
                                    <h4 class="mb-0 ">En Proceso</h4></div>
                                <div class="card-body">
                                    <h3 class="card-title">45</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger">
                                    <h4 class="mb-0 ">Pendientes</h4></div>
                                <div class="card-body">
                                    <h3 class="card-title">45</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-incidents" class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Descripción</th>
                                    <th class="bg-inverse text-white">Categoria</th>
                                    <th class="bg-inverse text-white">Sub-Categoria</th>
                                    <th class="bg-inverse text-white">Prioridad</th>
                                    <th class="bg-inverse text-white">Ubicación</th>
                                    <th class="bg-inverse text-white">Responsable</th>
                                    <th class="bg-inverse text-white">Creado por</th>
                                    <th class="bg-inverse text-white">Cerrado por</th>
                                    <th class="bg-inverse text-white">F.Creación</th>
                                    <th class="bg-inverse text-white">Estatus</th>
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
    @include('SafetyTours.StorageIncidenceSafety.partials.table')
    <script>
        function reloadtable() {
            $('#table-incidents').DataTable().ajax.reload();
        }
    </script>

@endsection
