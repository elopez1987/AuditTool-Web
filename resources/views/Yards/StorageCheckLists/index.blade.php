@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Check list de transporte
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagechecklists.create')
                                    <a href="{{route('storagechecklists.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="warehouseid">Bodega</label>
                                    <select name="warehouseid" id='warehouseid'  class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($warehouses as $model)
                                            <option value="{{$model->warehouseid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-4">
                        <div class="col-12">
                            <table id="table-checklist" width="100%" class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Tipo</th>
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
    @include('Yards.StorageCheckLists.partials.table')
    <script>
        function reloadtable() {
            $('#table-checklist').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#zoneid,#statusid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>

@endsection
