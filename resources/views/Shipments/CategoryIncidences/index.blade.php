@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Categorias de incidencia de procesos.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagecategoryincidences.create')
                                    <a href="{{route('storagecategoryincidences.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Nombre</label>
                                    <input type="text" class="form-control" id="description" name="description"
                                           aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-8">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <table id="table-categories" width="100%" class="table table-bordered table-hover table-condensed">
                                        <thead>
                                        <tr>
                                            <th>C.Distribución</th>
                                            <th>Bodega</th>
                                            <th>Nombre</th>
                                            <th>Tipo</th>
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
        </div>
    </div>

@endsection

@section('script')
    @include('Shipments.CategoryIncidences.partials.table')
    <script>
        function reloadtable() {
            $('#table-categories').DataTable().ajax.reload();
        }
    </script>
@endsection
