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
                                    <label for="shipmentid">Embarque <span style="color: red">*</span></label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="customername">Nombre de cliente <span style="color: red">*</span></label>
                                    <input type="text" class="form-control"  id="customername" name="customername"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
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
                                   class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Linea de transporte</th>
                                    <th class="bg-inverse text-white">Placas de caja</th>
                                    <th class="bg-inverse text-white">Cliente</th>
                                    <th class="bg-inverse text-white">Destino</th>
                                    <th class="bg-inverse text-white">Cita con cliente</th>
                                    <th class="bg-inverse text-white">Cita de carga</th>
                                    <th class="bg-inverse text-white">F.llegada a carga</th>
                                    <th class="bg-inverse text-white">Fin de facturación</th>
                                    <th class="bg-inverse text-white">Liberación de unidad</th>
                                    <th class="bg-inverse text-white">Salida del cedis</th>
                                    <th class="bg-inverse text-white">Maximo de salida</th>
                                    <th class="bg-inverse text-white">E. de salida</th>
                                    <th class="bg-inverse text-white">E. de transito</th>
                                    <th class="bg-inverse text-white">Ubicación</th>
                                    <th class="bg-inverse text-white">Eta con cliente</th>
                                    <th class="bg-inverse text-white">Llegada a destino</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Liberación de descarga</th>
                                    <th class="bg-inverse text-white">Razon de salida tarde</th>
                                    <th class="bg-inverse text-white">Causas de no automatización</th>
                                    <th class="bg-inverse text-white">Comentario de BPO</th>
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
    @include('Shipments.ShipmentJourneys.partials.table')
    <script>
        function reloadtable() {
            $('#table-journeys').DataTable().ajax.reload();
        }
    </script>
@endsection
