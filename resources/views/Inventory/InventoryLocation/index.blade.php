@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Areas
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('inventorycards.create')
                                    <a href="{{route('inventorycards.create')}}">
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
                        <hr class="m-t-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{old('name')}}">
                                </div>
                            </div>
                            <x-select2
                                    name="statusid"
                                    label="Estatus"
                                    :options="$status"
                                    placeholder="Seleccione un registro"
                            />
                            <x-select2
                                    name="areaid"
                                    label="Areas"
                                    :options="$areas"
                                    placeholder="Seleccione un registro"
                            />
                            <x-select2
                                    name="aisleid"
                                    label="Pasillo"
                                    :options="$aisles"
                                    placeholder="Seleccione un registro"
                            />
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-cards" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Area</th>
                                    <th class="bg-inverse text-white">Pasillo</th>
                                    <th class="bg-inverse text-white">Cedula</th>
                                    <th class="bg-inverse text-white">Auxiliar 01</th>
                                    <th class="bg-inverse text-white">Auxiliar 02</th>
                                    <th class="bg-inverse text-white">Auxiliar 03</th>
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
    @include('Inventory.InventoryCard.partials.table')
    <script>
        function reloadtable() {
            $('#table-cards').DataTable().ajax.reload();
        }
    </script>
@endsection
