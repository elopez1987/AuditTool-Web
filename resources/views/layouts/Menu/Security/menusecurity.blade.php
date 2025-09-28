@extends('layouts.app')

@section('bread')
    <li class="breadcrumb-item"><a href="#">Seguridad</a></li>
@stop

@section('content')
    <main>
        <div class="container__box">
            @can('securityusers.index')
                <a href="{{route('securityusers.index')}}">
                    <div class="box">
                        <i class="lni lni-users"></i>
                        <h5>Usuarios</h5>
                        <h4>Usuarios</h4>
                    </div>
                </a>
            @endcan
            @can('superusers.index')
                <a href="{{route('superusers.index')}}">
                    <div class="box">
                        <i class="lni lni-lock"></i>
                        <h5>Administradores</h5>
                        <h4>Administradores</h4>
                    </div>
                </a>
            @endcan
            @can('securityroles.index')
                <a href="{{route('securityroles.index')}}">
                    <div class="box">
                        <i class="lni lni-list"></i>
                        <h5>Perfiles</h5>
                        <h4>Perfiles</h4>
                    </div>
                </a>
            @endcan
            @can('securitypermissions.index')
                <a href="{{route('securitypermissions.index')}}">
                    <div class="box">
                        <i class="lni lni-unlock"></i>
                        <h5>Permisos</h5>
                        <h4>Permisos</h4>
                    </div>
                </a>
            @endcan
            @can('securitymenustructure.index')
                <a href="{{route('securitymenustructure.index')}}">
                    <div class="box">
                        <i class="lni lni-archive"></i>
                        <h5>Est. de Menu</h5>
                        <h4>Est. de Menu</h4>
                    </div>
                </a>
            @endcan
            @can('securitymodules.index')
                <a href="{{route('securitymodules.index')}}">
                    <div class="box">
                        <i class="lni lni-package"></i>
                        <h5>Modulos</h5>
                        <h4>Modulos</h4>
                    </div>
                </a>
            @endcan
            @can('securityfunctions.index')
                <a href="{{route('securityfunctions.index')}}">
                    <div class="box">
                        <i class="lni lni-layout"></i>
                        <h5>Funciones</h5>
                        <h4>Funciones</h4>
                    </div>
                </a>
            @endcan
        </div>

        <div class="container__box">
            @can('log.authentication')
                <a href="{{route('logauthentication')}}">
                    <div class="box">
                        <i class="lni lni-list"></i>
                        <h5>Log de inicios de sesión</h5>
                        <h4>Log de inicios de sesión</h4>
                    </div>
                </a>
            @endcan
            @can('log.suspicious')
                <a href="{{route('logsuspicious')}}">
                    <div class="box">
                        <i class="lni lni-list"></i>
                        <h5>Logs de sospechosos</h5>
                        <h4>Logs de sospechosos</h4>
                    </div>
                </a>
            @endcan
        </div>

    </main>
@endsection

