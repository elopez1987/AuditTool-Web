@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    Folios de conteo de andenes
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
                            </div>
                        </div>

                        <hr class="mt-4">
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-folios" class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Folio</th>
                                    <th class="bg-inverse text-white">Fecha de trabajo</th>
                                    <th class="bg-inverse text-white">Creador</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Estatus de firma</th>
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
    @include('Shipments.Bridgestone.ShipmentFolios.partials.table')
    <script>
        function reloadtable() {
            $('#table-folios').DataTable().ajax.reload();
        }
    </script>
@endsection
