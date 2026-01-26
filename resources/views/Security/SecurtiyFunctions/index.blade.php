@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Funciones
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form" action="{{route('securityfunctions.index')}}">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('securityfunctions.create')}}">
                                    @include('buttons.add')
                                </a>
                                <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <span class="text-button"> <i class="fa fa-search"></i> Buscar</span>
                                </button>
                                <a href="{{route('securityfunctions.index')}}">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <span class="text-button"> <i class="fa fa-trash mr-2"></i>Limpiar</span>
                                    </button>
                                </a>
                                <a href="{{route('menusecurity')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        <div class="row mt-2">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="moduleid">Modulo</label>
                                    <select name="moduleid" id="moduleid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($modules as $model)
                                            <option value="{{$model->moduleid}}" {{request('moduleid') == $model->moduleid ? 'selected' : ''}}> {{$model->description}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Función</label>
                                    <input type="text" class="form-control" id="description" name="description" value="{{request('description')}}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-10 col-sm-12">
                            <table id="table-modules" class="table table-bordered table-hover table-condensed ">
                                <thead>
                                    <tr>
                                        <th>Secuencia</th>
                                        <th>Modulo Padre</th>
                                        <th>Descripción</th>
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
    @include('Security.SecurtiyFunctions.partials.table')
    <script>
        function reloadtable() {
            $('#table-modules').DataTable().ajax.reload();
        }

        $("#moduleid").change(function () {
            reloadtable();
        });
    </script>
@endsection
