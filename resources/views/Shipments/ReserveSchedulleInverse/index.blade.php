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
                        {{--                        <div class="row">--}}
                        {{--                            <div class="col-lg-2 col-md-6 col-sm-12">--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <label for="description">Nombre</label>--}}
                        {{--                                    <input type="text" class="form-control" id="description" name="description"--}}
                        {{--                                           aria-describedby="name">--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-schedulereserve"
                                   class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Folio de cita</th>
                                    <th class="bg-inverse text-white">Fecha</th>
                                    <th class="bg-inverse text-white">Linea de transporte</th>
                                    <th class="bg-inverse text-white">Usuario</th>
                                    {{--                                    <th class="bg-inverse text-white">Cantidad de embarques</th>--}}
                                    {{--                                    <th class="bg-inverse text-white">Cantidad de cajas</th>--}}
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

@section('script')
    @include('Shipments.ReserveSchedulleInverse.partials.table')
    <script>
        function reloadtable() {
            $('#table-categories').DataTable().ajax.reload();
        }
    </script>
@endsection
