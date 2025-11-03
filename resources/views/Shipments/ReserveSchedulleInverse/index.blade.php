@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Reservas de citas de entrega
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('reserveschedulleinverse.create')
                                    <a href="{{route('reserveschedulleinverse.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
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
                        <hr class="m-t-1">
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-schedulereserve"
                                   class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th >C.Distribución</th>
                                    <th >Bodega</th>
                                    <th >Folio de cita</th>
                                    <th >Fecha</th>
                                    <th >Linea de transporte</th>
                                    <th >Usuario</th>
                                    <th >Estatus</th>
                                    <th >Opciones</th>
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
    @include('Shipments.ReserveSchedulleInverse.partials.table')
    <script>
        function reloadtable() {
            $('#table-categories').DataTable().ajax.reload();
        }
    </script>
@endsection
