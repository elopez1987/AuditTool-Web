@extends('layouts.app')

@section('content')
    @include('layouts.Partials.Errors')
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30"><img src="{{asset('storage/' . Auth::user()->avatar)}}"
                                                class="rounded-circle" width="150"/>
                        <h4 class="card-title m-t-10">{{$user->name}}</h4>
                        <div class="row text-center justify-content-md-center">
                        </div>
                    </center>
                </div>
                <div>
                    <hr>
                </div>
                <div class="card-body"><small class="text-muted">Correo electronico</small>
                    <h5>{{$user->email}}</h5> <small class="text-muted p-t-30 db">Número de contacto</small>
                    <h5>{{$user->auxiliary02}}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab"
                           aria-controls="pills-setting" aria-selected="false">Configuración</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="previous-month" role="tabpanel"
                         aria-labelledby="pills-setting-tab">
                        <div class="card-body">
                            <form method="POST" action="{{url('updateprofile/' . $user->uuiduser)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="form-group">
                                    <label class="col-md-12">Nombre completo</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{$user->name}}" id="name" name="name" required
                                               maxlength="50"
                                               class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Correo electronico</label>
                                    <div class="col-md-12">
                                        <input type="email" value="{{$user->email}}"
                                               class="form-control form-control-line" name="email" required id="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Nueva contraseña</label>
                                    <div class="col-md-12">
                                        <input type="password" name="password3" id="password3" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-md-12">Imagen de Perfil</label>
                                    <div class="input-group mb-3 col-md-12">
                                        <div class="custom-file">
                                            <input type="file" name="file" class="custom-file-input"
                                                   id="file">
                                            <label class="custom-file-label" for="file">Seleccione una
                                                imagen</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-success text-white">Actualizar Datos</button>
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

@section('script')

@endsection
