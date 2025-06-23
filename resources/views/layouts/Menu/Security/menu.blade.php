@extends('layouts.app')

@section('bread')
    <li class="breadcrumb-item"><a href="#">Configuración</a></li>
@stop

@section('content')

    <main>
        {{--                <h1 class="name-menu">Configuración</h1>--}}
        <div class="container__box">
            @can('systemcenterdistributions.index')
                <a href="{{route('systemcenterdistributions.index')}}">
                    <div class="box">
                        <i class="lni lni-home"></i>
                        <h5>C.Distribución</h5>
                        <h4>C.Distribución</h4>
                    </div>
                </a>
            @endcan
            @can('systemwarehouses.index')
                <a href="{{route('systemwarehouses.index')}}">
                    <div class="box">
                        <i class="lni lni-grid-alt"></i>
                        <h5>Bodegas</h5>
                        <h4>Bodegas</h4>
                    </div>
                </a>
            @endcan
            @can('inventorylabels.index')
                <a href="{{route('inventorylabels.index')}}">
                    <div class="box">
                        <i class="lni lni-ticket"></i>
                        <h5>Etiquetas</h5>
                        <h4>Etiquetas</h4>
                    </div>
                </a>
            @endcan
            @can('storagereasons.index')
                <a href="{{route('storagereasons.index')}}">
                    <div class="box">
                        <i class="lni lni-search-alt"></i>
                        <h5>Conceptos</h5>
                        <h4>Conceptos</h4>
                    </div>
                </a>
            @endcan
            @can('systemschemas.index')
                <a href="{{route('systemschemas.index')}}">
                    <div class="box">
                        <i class="lni lni-hammer"></i>
                        <h5>Esquemas</h5>
                        <h4>Esquemas</h4>
                    </div>
                </a>
            @endcan
            @can('systemparameters.index')
                <a href="{{route('systemparameters.index')}}">
                    <div class="box">
                        <i class="lni lni-cogs"></i>
                        <h5>Parametros</h5>
                        <h4>Parametros</h4>
                    </div>
                </a>
            @endcan
            @can('systemwhatsapps')
                <a href="{{route('systemwhatsapps.index')}}">
                    <div class="box">
                        <i class="lni lni-whatsapp"></i>
                        <h5>WhatsApp</h5>
                        <h4>WhatsApp</h4>
                    </div>
                </a>
            @endcan
            @can('systemmails.index')
                <a href="{{route('systemmails.index')}}">
                    <div class="box">
                        <i class="lni lni-mailchimp"></i>
                        <h5>Correos</h5>
                        <h4>Correos</h4>
                    </div>
                </a>
            @endcan
            @can('activitylog.index')
                <a href="{{route('activitylog.index')}}">
                    <div class="box">
                        <i class="lni lni-empty-file"></i>
                        <h5>Eventos</h5>
                        <h4>Eventos</h4>
                    </div>
                </a>
            @endcan
            @can('LISTAR NOMBRE')
                <a href="{{route('namesystem.index')}}">
                    <div class="box">
                        <i class="lni lni-connectdevelop"></i>
                        <h5>Sistema</h5>
                        <h4>Sistema</h4>
                    </div>
                </a>
            @endcan
            {{--            @can('filemanager')--}}
            {{--                <a href="{{route('filemanager')}}">--}}
            {{--                    <div class="box">--}}
            {{--                        <i class="lni lni-files"></i>--}}
            {{--                        <h5>Archivos</h5>--}}
            {{--                        <h4>Archivos</h4>--}}
            {{--                    </div>--}}
            {{--                </a>--}}
            {{--            @endcan--}}
            @can('systemprinters.index')
                <a href="{{route('systemprinters.index')}}">
                    <div class="box">
                        <i class="lni lni-printer"></i>
                        <h5>Impresoras</h5>
                        <h4>Impresoras</h4>
                    </div>
                </a>
            @endcan

            @role('ADMINISTRADOR')

            @can('systemfolios.index')
                <a href="{{route('systemfolios.index')}}">
                    <div class="box">
                        <i class="lni lni-tag"></i>
                        <h5>Folios</h5>
                        <h4>Folios</h4>
                    </div>
                </a>
            @endcan
            @can('editokens.index')
                <a href="{{route('editokens.index')}}">
                    <div class="box">
                        <i class="lni lni-agenda"></i>
                        <h5>Edi Token</h5>
                        <h4>Edi Token</h4>
                    </div>
                </a>
            @endcan
            @can('databaseupdate')
                <a href="{{route('databaseupdate.index')}}">
                    <div class="box">
                        <i class="lni lni-database"></i>
                        <h5>Mantenimiento</h5>
                        <h4>Mantenimiento</h4>
                    </div>
                </a>
            @endcan
            @can('systemtables.index')
                <a href="{{route('systemtables.index')}}">
                    <div class="box">
                        <i class="lni lni-layout"></i>
                        <h5>Tablas</h5>
                        <h4>Tablas</h4>
                    </div>
                </a>
            @endcan
            @can('logsystems')
                <a href="{{route('logsystems')}}" target="_blank">
                    <div class="box">
                        <i class="lni lni-files"></i>
                        <h5>Logs</h5>
                        <h4>Logs</h4>
                    </div>
                </a>
            @endcan
            @can('commandmenu')
                <a href="{{route('commandmenu')}}">
                    <div class="box">
                        <i class="lni lni-code"></i>
                        <h5>Comandos</h5>
                        <h4>Comandos</h4>
                    </div>
                </a>
            @endcan

            @can('logdeviceuser')
                <a href="{{route('logdeviceuser')}}">
                    <div class="box">
                        <i class="lni lni-laptop-phone"></i>
                        <h5>Dispositivos</h5>
                        <h4>Dispositivos</h4>
                    </div>
                </a>
            @endcan

            @endrole
        </div>
    </main>

@endsection

