@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Parametros
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('CREAR PARAMETROS')
                                <a href="{{route('systemparameters.create')}}">
                                  @include('buttons.add')
                                </a>
                                @endcan
                                <a href="">
                                  @include('buttons.search')
                                </a>
                                <a href="{{route('systemparameters.index')}}">
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
                                    <label for="parameterid">Parametro</label>
                                    <input type="text" class="form-control"  id="parameterid" name="parameterid"
                                           aria-describedby="parameterid" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('parameterid')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control"  id="name" name="name"
                                           aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('name')}}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <table id="table-parameters" class="table table-bordered table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Parametro</th>
                                        <th class="bg-inverse text-white">Nombre</th>
                                        <th class="bg-inverse text-white">Auxiliar 01</th>
                                        <th class="bg-inverse text-white">Auxiliar 02</th>
                                        <th class="bg-inverse text-white">Auxiliar 03</th>
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
    @include('Configuration.SystemParameters.partials.table')
@endsection
