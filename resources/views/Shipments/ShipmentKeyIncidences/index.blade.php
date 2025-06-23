@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    monitor de firmas de almacen
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
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-10 col-xs-12">
                            <table id="table-monitorkeys" class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Embarque</th>
                                        <th class="bg-inverse text-white">Transporte</th>
                                        <th class="bg-inverse text-white">Cliente</th>
                                        <th class="bg-inverse text-white">Ciudad</th>
                                        <th class="bg-inverse text-white">Cita de entrega</th>
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

@section('css')

@endsection

@section('script')
    @include('Shipments.ShipmentKeyIncidences.partials.table')
    <script>
        function reloadtable() {
            $('#table-monitorkeys').DataTable().ajax.reload();
        }

    </script>
    <script>
        $("#shipmentid").blur(function () {
            let value = document.getElementById("shipmentid").value;
            sessionStorage.setItem("shipmentidpod", value);
            reloadtable();
        });
    </script>
    <script>
        $(document).ready(function () {
            document.getElementById("shipmentid").value = sessionStorage.getItem('shipmentidpod');
            reloadtable();
        });
    </script>
@endsection
