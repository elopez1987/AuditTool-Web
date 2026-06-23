@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignación de datos complementos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <a>
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
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Folio de unidad</label>
                                    <input type="text" class="form-control numeric"  id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcode">Código de barra</label>
                                    <input type="text" class="form-control numeric"  id="barcode" name="barcode">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control"  id="boxnumber" name="boxnumber" onKeyUp=this.value=this.value.toUpperCase()>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid" onKeyUp=this.value=this.value.toUpperCase()>
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
                        <div class="col-lg-12 ">
                            <table id="table-assignshipment"
                                   class="table table-bordered  table-condensed table-hover table-uppercase table-responsive">
                                <thead>
                                <tr class="bg-inverse">
                                    <th class="text-white">Folio</th>
                                    <th class="text-white">Código de barra</th>
                                    <th class="text-white">Linea de transporte</th>
                                    <th class="text-white">Placas de caja</th>
                                    <th class="text-white">Número de caja</th>
                                    <th class="text-white">Operador</th>
                                    <th class="text-white">Hora de llegada</th>
                                    <th class="text-white">Embarque</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')

@endsection


@section('script')
    @include('Yards.AssignShipment.partials.table')
    <script>
        function reloadtable() {
            $('#table-assignshipment').DataTable().ajax.reload();
        }
    </script>
@endsection
