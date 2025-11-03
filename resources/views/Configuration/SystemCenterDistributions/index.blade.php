@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Centro de distribución
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('CREAR CENTROS')
                                <a href="{{route('systemcenterdistributions.create')}}">
                                   @include('buttons.add')
                                </a>
                                @endcan
                                <a href="">
                                   @include('buttons.search')
                                </a>
                                <a href="{{route('systemcenterdistributions.index')}}">
                                  @include('buttons.reload')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                   @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-center" class="table table-bordered table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">C.Distribución</th>
                                        <th class="bg-inverse text-white">Nombre</th>
                                        <th class="bg-inverse text-white">Descripción</th>
                                        <th class="bg-inverse text-white">Capacidad de Patio</th>
                                        <th class="bg-inverse text-white">Url Web Service WMS</th>
                                        <th class="bg-inverse text-white">Usuario Web Service WMS</th>
                                        <th class="bg-inverse text-white">Contraseña Web Service WMS</th>
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
    @include('Configuration.SystemCenterDistributions.partials.table')
@endsection
