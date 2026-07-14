@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignación de operadores a embarques.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
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
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid"
                                           onKeyUp=this.value=this.value.toUpperCase()>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carrierid">Linea de transporte</label>
                                    <select name="carrierid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($carriers as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-journeytransport" class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>Embarque</th>
                                    <th>Cliente</th>
                                    <th>Ciudad</th>
                                    <th>Cita de carga</th>
                                    <th>Cita de entrega</th>
                                    <th>Linea de transporte</th>
                                    <th>Operador</th>
                                    <th>Placa de caja</th>
                                    <th>Placa de tracto</th>
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
    @include('Shipments.StorageShipmenTransport.partials.table')
    <script>
        function reloadtable() {
            $('#table-journeytransport').DataTable().ajax.reload();
        }
    </script>
@endsection
