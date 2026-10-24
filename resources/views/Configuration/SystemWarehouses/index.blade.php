@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Bodegas
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('systemwarehouses.create')
                                    <a href="{{route('systemwarehouses.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
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
                        <br>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-warehouse" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>Nombre</th>
                                    <th>Auxiliar 01</th>
                                    <th>Auxiliar 02</th>
                                    <th>Auxiliar 03</th>
                                    <th>Auxiliar 04</th>
                                    <th>Auxiliar 05</th>
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
    @include('Configuration.SystemWarehouses.partials.table')
    <script>
        function reloadtable() {
            $('#table-warehouse').DataTable().ajax.reload();
        }
    </script>
@endsection
