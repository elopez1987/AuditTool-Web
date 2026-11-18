@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Registro de eventos
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
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="model">Modelo </label>
                                    <select name="model" id="model" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($models as $model)
                                            <option value="{{$model->subject_type}}"> {{$model->subject_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="subject_id">Modelo Id</label>
                                    <input type="text" class="form-control" id="subject_id" name="subject_id"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="userid">Usuario </label>
                                    <select name="userid" id="userid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($users as $model)
                                            <option value="{{$model->id}}"> {{$model->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-logs" class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Description</th>
                                    <th>Modelo</th>
                                    <th>Modelo Id</th>
                                    <th>Usuario</th>
                                    <th>F.Creación</th>
                                    <th>F.Modificación</th>
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

    @include('Configuration.ActivityLogs.partials.table')
    <script>
        function reloadtable() {
            $('#table-logs').DataTable().ajax.reload();
        }
    </script>
@endsection
