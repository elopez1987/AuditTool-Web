@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                Lpns de migración de SAP
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
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
                        <hr class="m-t-1">
                        <div class="row">
                            <x-text-input
                                    name="lpnizel"
                                    label="Lpn de Izel"
                            />
                            <x-text-input
                                    name="lpnsap"
                                    label="Lpn de Sap"
                            />
                            <x-text-input
                                    name="locationid"
                                    label="Ubicación"
                            />
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-migration" class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>C.Distribución</th>
                                        <th>Bodega</th>
                                        <th>Ubicación</th>
                                        <th>Lpn Izel</th>
                                        <th>Lpn SAP</th>
                                        <th>Usuario creador</th>
                                        <th>Fecha de creación</th>
                                        <th>Evidencia</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')

@endsection

@section('script')
    @include('Inventory.MigrationSap.table')
    <script>
        function reloadtable() {
            $('#table-migration').DataTable().ajax.reload();
        }
    </script>
@endsection
