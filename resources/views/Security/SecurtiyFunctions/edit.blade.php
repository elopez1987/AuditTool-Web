@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    Modificar función
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securityfunctions/' . $securityfunction->functionid)}}">
                        {{ method_field('PUT') }}
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securityfunctions.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Security.SecurtiyFunctions.partials.formEdit')
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre del permiso <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{old('name')}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="typeid">Tipo de acceso <span style="color: red">*</span></label>
                                    <select name="typeid" id="typeid" class="form-control select2">
                                        <option value="web">WEB</option>
                                        <option value="api">API</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <span>Tipos de Permisos</span>
                                            </div>
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <label>
                                                            <input type="checkbox" name="route[]" value="index"/> Listar
                                                            tabla
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label>
                                                            <input type="checkbox" name="route[]" value="create"
                                                                   class="flat-red"/> Agregar
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label>
                                                            <input type="checkbox" name="route[]" value="show"
                                                                   class="flat-red"/> Detalle
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label>
                                                            <input type="checkbox" name="route[]" value="edit"
                                                                   class="flat-red"/> Modificar
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <label>
                                                            <input type="checkbox" name="route[]" value="destroy"
                                                                   class="flat-red"/> Eliminar
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Agrega permiso personalizado
                        </div>
                        <form method="POST"
                              action="{{url('createNewPermissionForFunction/' . $securityfunction->functionid)}}">
                            {{ method_field('PUT') }}
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-condensed table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Permiso</th>
                                                <th>Descripción</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for($i=1;$i <=8;$i++)
                                                <tr>
                                                    <td><input type="text" class="form-control" name="permisionid[]" ></td>
                                                    <td><input type="text" class="form-control" name="permisionname[]"></td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10">
                            <table class="table table-condensed table-bordered table-striped table-hover tablegeneral">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Modulo</th>
                                    <th>Funcion</th>
                                    <th>Permiso</th>
                                    <th>Descripción</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>{{$permission->id}}</td>
                                        <td>{{$permission->module ? $permission->module->description : ''}}</td>
                                        <td>{{$permission->function ? $permission->function->description : ''}}</td>
                                        <td>{{$permission->name}}</td>
                                        <td>{{$permission->description}}</td>
                                        <td>
                                            @can('securitypermissions.destroy')
                                                <div class="btn-group">
                                                    <a href="" data-target="#modal-delete-{{$permission->id}}"
                                                       data-toggle="modal">
                                                        <img src="{{asset('/ico/addestroy.png')}}" alt=""
                                                             class="img-16 ml-2">
                                                    </a>
                                                    @include('Security.Permissions.partials.destroy')
                                                </div>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection

