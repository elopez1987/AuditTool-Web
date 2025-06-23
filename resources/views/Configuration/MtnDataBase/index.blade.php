@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Mantenimiento a base de datos
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="POST" action="{{url('databaseupdate')}}">
                                @csrf
                                <div class="row mb-3 mt-3">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('menuconfiguration')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mt-3">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="event">Evento a realizar <span style="color: red">*</span></label>
                                            <select name="event" required id="event" class="form-control select2">
                                                <option value="update"> Actualizar registro</option>
                                                <option value="delete"> Borrar registro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="table">Seleccione una tabla<span style="color: red">*</span></label>
                                            <select name="table" id="table" required class="form-control select2">
                                                @foreach($tables as $model)
                                                    <option value="{{$model->tableid}}"> {{$model->tableid}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="key">Seleccione una columna llave<span
                                                    style="color: red">*</span></label>
                                            <select name="key" id="key" required class="form-control select2"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="keyValue">Valor llave para filtrado <span
                                                    style="color: red">*</span></label>
                                            <input type="text" class="form-control"  id="keyValue" name="keyValue"
                                                   aria-describedby="keyValue"
                                                   value="{{old('keyValue')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="columndiv">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="column">Seleccione la columna a actualizar<span
                                                    style="color: red">*</span></label>
                                            <select name="column" id="column"  class="form-control select2"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="newvaluediv">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="newValue">Nuevo valor <span style="color: red">*</span></label>
                                            <input type="text" class="form-control"  id="newValue" name="newValue">
                                        </div>
                                    </div>
                                </div>
                            </form>
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
    <script>
        $("#event").change(event => {
            let razon = document.getElementById("event").value;
            if (razon === 'delete') {
                $("#columndiv").hide();
                $("#newvaluediv").hide();
            }
            if (razon === 'update') {
                $("#columndiv").show();
                $("#newvaluediv").show();
            }
        });
    </script>

    <script>
        $("#table").change(event => {
            $.get(`./getColumnForTable/${event.target.value}`, function (res) {
                $("#key").empty();
                $("#column").empty();
                res.forEach(element => {
                    $("#key").append(`<option value=${element.columnid}> ${element.columnid} </option>`);
                    $("#column").append(`<option value=${element.columnid}> ${element.columnid} </option>`);
                });
            });
        });
    </script>

@endsection
