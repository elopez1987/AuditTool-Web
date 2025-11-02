@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modulo de super usuarios
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12 ">
                                <a href="{{route('superusers.create')}}">
                                    @include('buttons.add')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menusecurity')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-2">
                    </form>
                    <div class="card-input">
                        <div class="row mt-3">
                            <div class="col-lg-6 ">
                                <table id="table-users" class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Nombre</th>
                                        <th class="bg-inverse text-white">Usuario</th>
                                        <th class="bg-inverse text-white">Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->username}}</td>
                                            <td>
                                                <a href="" data-target="#modal-delete-{{$user->userid}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16 ml-2">
                                                </a>
                                                @include('Security.SuperUsers.delete')
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
    </div>
@endsection

