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
                                @can('storage-category-safeties.create')
                                    <a href="{{route('storage-category-safeties.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
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
                        <div class="row">
                            <x-text-input
                                    name="name"
                                    label="Nombre de categoría"
                            />
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-10">
                            <table id="table-categories" class="table table-bordered table-condensed table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>C.Distribución</th>
                                        <th>Nombre</th>
                                        <th>F.Creación</th>
                                        <th>Estatus</th>
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
    @include('SafetyTours.StorageCategorySafety.partials.table')
    <script>
        function reloadtable() {
            $('#table-categories').DataTable().ajax.reload();
        }
    </script>

@endsection
