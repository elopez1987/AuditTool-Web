@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('namesystem.create')}}">
                                    @include('buttons.add')
                                </a>
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a href="{{route('namesystem.index')}}">
                                    @include('buttons.reload')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
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
                    <hr class="mt-3">
                    <div class="row">
                        <div class="col-12">
                            <table id="table" class="table table-bordered table-condensed tablegeneral">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Versión</th>
                                    <th class="bg-inverse text-white">Auxiliar 01</th>
                                    <th class="bg-inverse text-white">Auxiliar 02</th>
                                    <th class="bg-inverse text-white">Auxiliar 03</th>
                                    <th class="bg-inverse text-white">Auxiliar 04</th>
                                    <th class="bg-inverse text-white">Auxiliar 05</th>
                                    <th width="5%" class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($names as $name)
                                    <tr>
                                        <td>{{$name->name}}</td>
                                        <td>{{$name->version}}</td>
                                        <td>{{$name->auxiliary01}}</td>
                                        <td>{{$name->auxiliary02}}</td>
                                        <td>{{$name->auxiliary03}}</td>
                                        <td>{{$name->auxiliary04}}</td>
                                        <td>{{$name->auxiliary05}}</td>
                                        <td>
                                            <a href="{{url('namesystem/' . $name->id. '/edit')}}" data-toggle="tooltip"
                                               data-placement="top"
                                               title="Modificar Registro">
                                                @include('buttons.edit')
                                            </a>
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

    <div class="row">
        <div class="col-lg-12 text-center">
            <p class="font-14 font-bold">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </p>
        </div>
    </div>

@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection
