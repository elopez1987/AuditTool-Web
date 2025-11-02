@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de eventos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagereasons.create')
                                    <a href="{{route('storagereasons.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-reasons"
                                   class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Código</th>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Evento WMS</th>
                                    <th class="bg-inverse text-white">Tipo de movimiento</th>
                                    <th class="bg-inverse text-white">Auxiliar 01</th>
                                    <th class="bg-inverse text-white">Auxiliar 02</th>
                                    <th class="bg-inverse text-white">Auxiliar 03</th>
                                    <th class="bg-inverse text-white">Auxiliar 04</th>
                                    <th class="bg-inverse text-white">Auxiliar 05</th>
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
    @include('Configuration.StorageReasons.partials.table')
@endsection
