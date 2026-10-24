@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Permisos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}

                        <div class="card-input">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{route('securitypermissions.create')}}">
                                        @include('buttons.add')
                                    </a>
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
                            <div class="row mt-4">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="moduleid">Modulo</label>
                                        <select name="moduleid" id='moduleid' class="form-control select2">
                                            <option value="" selected>Seleccione un valor</option>
                                            @foreach($modules as $model)
                                                <option value="{{$model->moduleid}}"> {{$model->description}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="functionid">Función</label>
                                        <select name="functionid" id='functionid' class="form-control select2">
                                            <option value="">Seleccione una función</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               aria-describedby="name">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Descripción del permiso</label>
                                        <input type="text" class="form-control" id="description" name="description"
                                               aria-describedby="name">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-10 col-md-8 col-sm-12">
                            <table id="table-permission"
                                   class="table table-bordered table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Modulo</th>
                                        <th>Función</th>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Guard</th>
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
    @include('Security.Permissions.partials.table')

    <script>
        function reloadtable() {
            $('#table-permission').DataTable().ajax.reload();
        }
        $("#moduleid,#functionid").change(function () {
            reloadtable();
        });
    </script>

    <script>
        $("#moduleid").change(event => {
            $.get(`../getFunctionForModule/${event.target.value}`, function (res) {
                $("#functionid").empty();
                res.forEach(element => {
                    $("#functionid").append(`<option value=${element.functionid}> ${element.description} </option>`);
                });
            });
        });
    </script>

@endsection
