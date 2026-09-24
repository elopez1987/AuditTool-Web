@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Dispositivos GPS
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('gps-devices.create')
                                    <a href="{{route('gps-devices.create')}}">
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
                        <hr class="mb-3">
                        <div class="row">
                            <x-text-input
                                    name="imei"
                                    label="Número Imei"
                                    uppercase
                                    colClass="col-lg-2 col-md-12"
                            />
                            <x-text-input
                                    name="sim_number"
                                    label="Número SIM"
                                    uppercase
                                    colClass="col-lg-2 col-md-12"
                            />
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-gps" class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Imei</th>
                                    <th>Modelo</th>
                                    <th>SIM</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @include('layouts.Partials.Errors')
                        </div>
                    </div>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
        @include('Yards.GpsDevices.partials.table')
        <script>
            function reloadtable() {
                $('#table-gps').DataTable().ajax.reload();
            }
        </script>
@endsection
