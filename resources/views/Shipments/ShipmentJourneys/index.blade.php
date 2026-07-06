@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Seguimiento de viajes
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                               @include('buttons.reloadajax')
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="customername">Nombre de cliente</label>
                                    <input type="text" class="form-control"  id="customername" name="customername"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
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
                    <div class="row">
                        <div class="col-12">
                            <table id="table-journeys"
                                   class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Embarque</th>
                                    <th>Linea de transporte</th>
                                    <th>Placas de caja</th>
                                    <th>Cliente</th>
                                    <th>Destino</th>
                                    <th>Cita con cliente</th>
                                    <th>Cita de carga</th>
                                    <th>F.llegada a carga</th>
                                    <th>Fin de facturación</th>
                                    <th>Liberación de unidad</th>
                                    <th>Salida del cedis</th>
                                    <th>Maximo de salida</th>
                                    <th>E. de salida</th>
                                    <th>E. de transito</th>
                                    <th>Ubicación</th>
                                    <th>Eta con cliente</th>
                                    <th>Llegada a destino</th>
                                    <th>Estatus</th>
                                    <th>Liberación de descarga</th>
                                    <th>Razon de salida tarde</th>
                                    <th>Causas de no automatización</th>
                                    <th>Comentario de BPO</th>
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
    @include('Shipments.ShipmentJourneys.partials.table')
    <script>
        function reloadtable() {
            $('#table-journeys').DataTable().ajax.reload();
        }
    </script>
@endsection
