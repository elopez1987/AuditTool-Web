@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de conteo
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{route('home')}}">
                                @include('buttons.cancel')
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
                                <label for="idh">IDH / SKU</label>
                                <input type="text" class="form-control" id="idh" name="idh">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="location">Ubicación</label>
                                <input type="text" class="form-control" id="location" name="location">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="area">Area</label>
                                <input type="text" class="form-control" id="area" name="area">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="family">Familia</label>
                                <input type="text" class="form-control" id="family" name="family">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-detailcount" class="table table-striped table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th>Número de Conteo</th>
                                    <th>WD</th>
                                    <th>Lpn</th>
                                    <th>Ubicación</th>
                                    <th>Ultimo movimiento</th>
                                    <th>IDH</th>
                                    <th>Cantidad Pz</th>
                                    <th>Uom</th>
                                    <th>Cajas</th>
                                    <th>Footprint</th>
                                    <th>Estatus</th>
                                    <th>Lote</th>
                                    <th>Fecha de manufactura</th>
                                    <th>Fecha de expiración</th>
                                    <th>Fecha de alta</th>
                                    <th>Ultimo usuario</th>
                                    <th>Familia</th>
                                    <th>Descripción</th>
                                    <th>Area</th>
                                    <th>Match</th>
                                    <th>Real</th>
                                    <th>Incidencia</th>
                                    <th>Nota</th>
                                    <th>Comentario</th>
                                    <th>Fecha de registro</th>
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
    @include('Counting.StorageCountDronHead.partials.tableDetail')
    <script>
        function reloadtable() {
            $('#table-detailcount').DataTable().ajax.reload();
        }
    </script>
@endsection
