@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de destinos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagedestinations.create')
                                    <a href="{{route('storagedestinations.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                    <a>
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Refrescar la pagina"
                                                onclick="reloadtable();">
                                            <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                            <span style="font-size: 11px;"> Refrescar</span>
                                        </button>
                                    </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-trucks" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Cliente</th>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Tiempo de traslado</th>
                                    <th class="bg-inverse text-white">Auxiliar 01</th>
                                    <th class="bg-inverse text-white">Auxiliar 02</th>
                                    <th class="bg-inverse text-white">Auxiliar 03</th>
                                    <th class="bg-inverse text-white">Auxiliar 04</th>
                                    <th class="bg-inverse text-white">Auxiliar 05</th>
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
    @include('Yards.StorageDestinations.partials.table')
    <script>
        function reloadtable() {
            $('#table-trucks').DataTable().ajax.reload();
        }
    </script>

@endsection
