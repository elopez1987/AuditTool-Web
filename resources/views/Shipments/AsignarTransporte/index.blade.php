@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Asignar Tracto Camion a Remolque.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <table id="table-shipments" class="table table-bordered table-condensed table-hover ">
                                <thead>
                                    <tr>
                                        <th>Embarque</th>
                                        <th>Cliente</th>
                                        <th>Ciudad</th>
                                        <th>Linea de transporte</th>
                                        <th>Cita de carga</th>
                                        <th>Cita de descarga</th>
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

@section('css')

@endsection


@section('script')
    @include('Shipments.AsignarTransporte.partials.table')
    <script>
        function reloadtable() {
            $('#table-shipments').DataTable().ajax.reload();
        }
    </script>
@endsection
