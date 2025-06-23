@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Registro
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('updatecolumntotable/' . $table->tableid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('systemtables.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="tableid">Nombre de tabla <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" readonly id="tableid" name="tableid"
                                           value="{{$table->tableid}}"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="columnid">Nombre de columna <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="columnid" name="columnid"
                                           value="{{old('columnid')}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shortdesc">Descripción corta <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="shortdesc" name="shortdesc"
                                           value="{{old('shortdesc')}}"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="descriptionname">Descripción larga <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="descriptionname"
                                           name="descriptionname"
                                           value="{{old('descriptionname')}}"
                                    >
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table table-bordered table-condensed table-striped table-hover tablegeneral">
                                <thead>
                                <tr>
                                    <th>Tabla</th>
                                    <th>Columna</th>
                                    <th>Descripción</th>
                                    <th>Descripción corta</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($columns as $column)
                                    <tr>
                                        <td>{{$column->tableid}}</td>
                                        <td>{{$column->columnid}}</td>
                                        <td>{{$column->description}}</td>
                                        <td>{{$column->shortdesc}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('systemtables.importcolumns')
                        <a href="" data-target="#modal-upload-columns" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span style="font-size: 11px;"> Armar Columnas </span>
                            </button>
                        </a>
                        @include('Configuration.SystemTables.uploadfilecolumns')
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection

