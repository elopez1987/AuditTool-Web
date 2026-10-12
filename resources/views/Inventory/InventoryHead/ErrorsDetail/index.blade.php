@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Errores de inventario fiscal - {{ $inventoryhead->name }}
                </div>
                <div class="card-body">

                    <form method="GET" id="search" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{ url('inventoryheads/' . $inventoryhead->inventory_uuid) }}">
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
                                           value="{{ old('name') }}"
                                           placeholder="Cédula, ubicación o SKU"
                                           autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    Concentrado de errores
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover table-bordered table-condensed"
                                           id="inventoryErrorsConcentratedTable"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Cantidad de errores</th>
                                            <th>Imprimir</th>
                                            <th>3er conteo</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    Asignar contador 3er conteo
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover table-bordered table-condensed"
                                           id="inventoryErrorsAssignTable"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Usuario asignado</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    ASIGNACION DE TAREAS
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-condensed" id="inventorycardassing" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Cedula</th>
                                                <th>Usuario</th>
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
    @include('layouts.Partials.Errors')
@endsection

@section('script')
    @include('Inventory.InventoryHead.ErrorsDetail.tables')
    <script>
        function reloadtable() {
            if ($.fn.DataTable.isDataTable('#inventoryErrorsConcentratedTable')) {
                $('#inventoryErrorsConcentratedTable').DataTable().ajax.reload(null, false);
            }
            if ($.fn.DataTable.isDataTable('#inventoryErrorsAssignTable')) {
                $('#inventoryErrorsAssignTable').DataTable().ajax.reload(null, false);
            }
            if ($.fn.DataTable.isDataTable('#inventorycardassing')) {
                $('#inventorycardassing').DataTable().ajax.reload(null, false);
            }
        }
    </script>
@endsection
