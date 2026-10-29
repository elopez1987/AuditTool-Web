<!DOCTYPE html>
<html dir="ltr" lang="es">
@include('layouts.Partials.Head')
<body>
<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<div id="main-wrapper">
    @include('layouts.Partials.Header')

    <aside class="left-sidebar">
        <div class="scroll-sidebar">
            <nav class="sidebar-nav">
                <ul id="sidebarnav">

                    <li class="sidebar-item"><a class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('home')}}" aria-expanded="false">
                            <img src="{{asset('ico/dashboard.png')}}"
                                 alt="" class="img-16 ml-2 mr-2">
                            <span class="hide-menu">Dashboard</span></a>
                    </li>


                    @can('RECIBO')
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark"
                               href="javascript:void(0)" aria-expanded="false">
                                <img src="{{asset('ico/logistica.png')}}" alt="" class="img-16 ml-2 mr-2">
                                <span class="hide-menu"> Recibo</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                @can('RECIBO.schedulle')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Horarios</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            @can('receiptasn.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('receiptasn.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-layers"></i>
                                                        <span class="hide-menu"> Planeación </span></a>
                                                </li>
                                            @endcan
                                            @can('schedulereceipt')
                                                <li class="sidebar-item"><a
                                                            href="{{route('schedulereceipt.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-layers"></i>
                                                        <span class="hide-menu"> Horario </span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('RECIBO.reports')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Reportes</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            @can('reportreceipts')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reportreceipts')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Reportes</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('RECIBO.catalogs')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Catalogos</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            @can('storageoperators.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storageoperators.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Operadores</span></a>
                                                </li>
                                            @endcan
                                            @can('receiptproviders.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('receiptproviders.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Rutas</span></a>
                                                </li>
                                            @endcan
                                            @can('shipmentcarriers.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('shipmentcarriers.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Lineas de transporte</span></a>
                                                </li>
                                            @endcan

                                            @can('storagezones.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagezones.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Zonas</span></a>
                                                </li>
                                            @endcan
                                            @can('storagelocations.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagelocations.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Ubicaciones</span></a>
                                                </li>
                                            @endcan

                                            @can('storagechecklists.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagechecklists.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Check List</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

                    @can('INVENTARIO')
                        <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                    href="javascript:void(0)" aria-expanded="false">
                                <img src="{{asset('ico/caja-de-entrega.png')}}"
                                     alt="" class="img-16 ml-2 mr-2">
                                <span class="hide-menu"> Inventario</span></a>
                            <ul aria-expanded="false" class="collapse first-level">

                                @can('INVENTARIO.catalogs')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Catalogos</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('inventorypartnumbers.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventorypartnumbers.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-layers"></i>
                                                        <span class="hide-menu"> Maestro de materiales</span></a>
                                                </li>
                                            @endcan

                                            @can('inventoryunitmeasure.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventoryunitmeasure.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-layers"></i>
                                                        <span class="hide-menu"> Unidad de medida</span></a>
                                                </li>
                                            @endcan

                                            @can('inventorypartnumbercategory01.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventorypartnumbercategory01.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Tipo de material 1</span></a>
                                                </li>
                                            @endcan

                                            @can('inventorypartnumbercategory02.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventorypartnumbercategory02.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Tipo de material 2</span></a>
                                                </li>
                                            @endcan

                                            @can('inventorystockcategories.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventorystockcategories.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Origenes</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

                    @can('security')
                        <li class="sidebar-item">

                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                               aria-expanded="false">
                                <img src="{{asset('ico/datos-seguros.png')}}"
                                     alt="" class="img-16 ml-2 mr-2">
                                <span class="hide-menu"> Seguridad</span>
                            </a>

                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                            href="javascript:void(0)" aria-expanded="false"><i
                                                class="mdi mdi-collage"></i><span class="hide-menu">Usuarios</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        @can('securityusers.index')
                                            <li class="sidebar-item"><a
                                                        href="{{route('securityusers.index')}}"
                                                        class="sidebar-link">
                                                    <i class="lni lni-users"></i>
                                                    <span class="hide-menu"> Usuarios</span></a>
                                            </li>
                                        @endcan
                                        @can('securityroles.index')
                                            <li class="sidebar-item"><a href="{{route('securityroles.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-list"></i>
                                                    <span class="hide-menu"> Perfiles de usuario</span></a>
                                            </li>
                                        @endcan
                                        @role('ADMINISTRADOR')
                                        @can('superusers.index')
                                            <li class="sidebar-item"><a href="{{route('superusers.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-lock"></i>
                                                    <span class="hide-menu"> Administradores</span></a>
                                            </li>
                                        @endcan
                                        @endrole
                                        @can('securitypermissions.index')
                                            <li class="sidebar-item"><a href="{{route('securitypermissions.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-unlock"></i>
                                                    <span class="hide-menu"> Permisos</span></a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                                <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                            href="javascript:void(0)" aria-expanded="false"><i
                                                class="mdi mdi-receipt"></i><span
                                                class="hide-menu">Administración</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        @can('securitymenustructure.index')
                                            <li class="sidebar-item"><a href="{{route('securitymenustructure.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-archive"></i>
                                                    <span class="hide-menu"> Estructura de ménu</span></a>
                                            </li>
                                        @endcan
                                        @can('securitymodules.index')
                                            <li class="sidebar-item"><a href="{{route('securitymodules.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-package"></i>
                                                    <span class="hide-menu"> Modulos</span></a>
                                            </li>
                                        @endcan
                                        @can('securityfunctions.index')
                                            <li class="sidebar-item"><a href="{{route('securityfunctions.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-layout"></i>
                                                    <span class="hide-menu"> Funciones</span></a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>

                                <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                            href="javascript:void(0)" aria-expanded="false"><i
                                                class="mdi mdi-receipt"></i><span
                                                class="hide-menu">Reportes</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        @can('usersfortransports')
                                            <li class="sidebar-item"><a href="{{route('usersfortransports')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-archive"></i>
                                                    <span class="hide-menu"> Usuarios por linea de transporte</span></a>
                                            </li>
                                        @endcan
                                        @can('log.authentication')
                                            <li class="sidebar-item"><a href="{{route('logauthentication')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-package"></i>
                                                    <span class="hide-menu"> Logs de inicio de sesión</span></a>
                                            </li>
                                        @endcan
                                        @can('log.suspicious')
                                            <li class="sidebar-item"><a href="{{route('logsuspicious')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-list"></i>
                                                    <span class="hide-menu"> Log de sospechosos</span></a>
                                            </li>
                                        @endcan
                                        @can('activeusers.index')
                                            <li class="sidebar-item"><a href="{{route('activeusers.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-list"></i>
                                                    <span class="hide-menu"> Usuarios activos APP</span></a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>

                            </ul>
                        </li>
                    @endcan

                    @can('configuration')
                        <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                    href="javascript:void(0)" aria-expanded="false">
                                {{--                                <i class="mdi mdi-collage"></i>--}}
                                <img src="{{asset('ico/configuracion-del-navegador.png')}}"
                                     alt="" class="img-16 ml-2 mr-2">
                                <span class="hide-menu"> Configuración</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                            href="javascript:void(0)" aria-expanded="false"><i
                                                class="mdi mdi-collage"></i><span class="hide-menu">Configuración</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        @can('systemcenterdistributions.index')
                                            <li class="sidebar-item"><a
                                                        href="{{route('systemcenterdistributions.index')}}"
                                                        class="sidebar-link">
                                                    <i class="lni lni-home"></i>
                                                    <span class="hide-menu"> Centro de distribución</span></a>
                                            </li>
                                        @endcan
                                        @can('systemwarehouses.index')
                                            <li class="sidebar-item"><a href="{{route('systemwarehouses.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-grid-alt"></i>
                                                    <span class="hide-menu"> Bodegas</span></a>
                                            </li>
                                        @endcan
                                        @can('storagereasons.index')
                                            <li class="sidebar-item"><a href="{{route('storagereasons.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-search-alt"></i>
                                                    <span class="hide-menu"> Conceptos de ajuste</span></a>
                                            </li>
                                        @endcan
                                        @can('systemschemas.index')
                                            <li class="sidebar-item"><a href="{{route('systemschemas.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-hammer"></i>
                                                    <span class="hide-menu"> Esquemas</span></a>
                                            </li>
                                        @endcan
                                        @can('systemparameters.index')
                                            <li class="sidebar-item"><a href="{{route('systemparameters.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="lni lni-cogs"></i>
                                                    <span class="hide-menu"> Parametros</span></a>
                                            </li>
                                        @endcan
                                        @can('systemprinters.index')
                                            <li class="sidebar-item"><a href="{{route('systemprinters.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-toggle-switch-off"></i><span
                                                            class="hide-menu"> Impresoras</span></a>
                                            </li>
                                        @endcan

                                        @can('commandmenu')
                                            <li class="sidebar-item"><a href="{{route('commandmenu')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-relative-scale"></i><span class="hide-menu"> Comandos</span></a>
                                            </li>
                                        @endcan
                                        @can('logdeviceuser')
                                            <li class="sidebar-item"><a href="{{route('logdeviceuser')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-tab-unselected"></i><span class="hide-menu"> Dispositivos</span></a>
                                            </li>
                                        @endcan
                                        @can('systemfolios.index')
                                            <li class="sidebar-item"><a href="{{route('systemfolios.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-tab-unselected"></i><span class="hide-menu"> Folios</span></a>
                                            </li>
                                        @endcan
                                        @can('inventorylabels.index')
                                            <li class="sidebar-item"><a href="{{route('inventorylabels.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="lni lni-ticket"></i><span class="hide-menu"> Etiquetas</span></a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                                <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                            href="javascript:void(0)" aria-expanded="false"><i
                                                class="mdi mdi-receipt"></i><span class="hide-menu">Comunicación</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        @can('systemmails.index')
                                            <li class="sidebar-item"><a href="{{route('systemmails.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="lni lni-mailchimp"></i><span
                                                            class="hide-menu"> Configuración de Correos</span></a>
                                            </li>
                                        @endcan
                                        @can('systemwhatsapps.index')
                                            <li class="sidebar-item"><a href="{{route('systemwhatsapps.index')}}"
                                                                        class="sidebar-link"> <i
                                                            class="lni lni-whatsapp"></i><span
                                                            class="hide-menu"> Configuración de WhatsApp</span></a>
                                            </li>
                                        @endcan
                                        @can('storagetaskwhatsapp')
                                            <li class="sidebar-item"><a href="{{route('storagetaskwhatsapp.index')}}l"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-file-document-box"></i><span
                                                            class="hide-menu"> Mensajes pendientes</span></a>
                                            </li>
                                        @endcan
                                        @can('storagetaskmail')
                                            <li class="sidebar-item"><a href="{{route('storagetaskmail.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-file-document-box"></i><span
                                                            class="hide-menu"> Correos pendientes</span></a>
                                            </li>
                                        @endcan
                                        @can('jobs')
                                            <li class="sidebar-item"><a href="{{route('jobs')}}" class="sidebar-link"><i
                                                            class="mdi mdi-file-document-box"></i><span
                                                            class="hide-menu"> Tareas pendientes</span></a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                                <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                            href="javascript:void(0)" aria-expanded="false"><i
                                                class="mdi mdi-receipt"></i><span class="hide-menu">Web Services</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        @can('editokens.index')
                                            <li class="sidebar-item"><a href="{{route('editokens.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-vector-difference-ba"></i><span
                                                            class="hide-menu"> Configuración de Okta</span></a>
                                            </li>
                                        @endcan
                                        @can('edi-configurations.index')
                                            <li class="sidebar-item"><a href="{{route('edi-configurations.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-file-document-box"></i><span
                                                            class="hide-menu"> Configuración de interfaces</span></a>
                                            </li>
                                        @endcan
                                        @can('edishipments.index')
                                            <li class="sidebar-item"><a href="{{route('edishipments.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-file-document-box"></i><span
                                                            class="hide-menu"> Monitor de interfaces</span></a>
                                            </li>
                                        @endcan
                                        @can('webservicelog')
                                            <li class="sidebar-item"><a href="{{route('webservicelog.index')}}"
                                                                        class="sidebar-link"><i
                                                            class="mdi mdi-file-document-box"></i><span
                                                            class="hide-menu"> Logs de interfaces</span></a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>

                                <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                            href="javascript:void(0)" aria-expanded="false"><i
                                                class="mdi mdi-receipt"></i><span class="hide-menu">Base de datos</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        @role('ADMINISTRADOR')
                                        @can('databaseupdate')
                                            <li class="sidebar-item"><a href="{{route('databaseupdate.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="mdi mdi-arrow-right text-trax"></i>
                                                    <span class="hide-menu"> Mantenimiento</span></a>
                                            </li>
                                        @endcan
                                        @can('systemtables.index')
                                            <li class="sidebar-item"><a href="{{route('systemtables.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="mdi mdi-arrow-right text-trax"></i>
                                                    <span class="hide-menu"> Tablas</span></a>
                                            </li>
                                        @endcan
                                        @can('label-dictionary.index')
                                            <li class="sidebar-item"><a href="{{route('label-dictionary.index')}}"
                                                                        class="sidebar-link">
                                                    <i class="mdi mdi-arrow-right text-trax"></i>
                                                    <span class="hide-menu"> Diccionario de etiquetas</span></a>
                                            </li>
                                        @endcan
                                        @endrole
                                    </ul>
                                </li>

                                @can('logsystems')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('logsystems')}}" aria-expanded="false" target="_blank">
                                            <i class="lni lni-files"></i>
                                            <span class="hide-menu">Logs</span></a>
                                    </li>
                                @endcan

                                @can('activitylog.index')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('activity-logs.index')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span class="hide-menu">Eventos</span></a>
                                    </li>
                                @endcan
                                @can('LISTAR NOMBRE')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('namesystem.index')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span class="hide-menu">Sistema</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan

                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                            <img src="{{asset('ico/folder_contacts_15440.png')}}"
                                 alt="" class="img-16 ml-2 mr-2">
                            <span class="hide-menu">Contacto</span>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>


    <div class="page-wrapper">
        <div class="container-fluid">
            @yield('content')
        </div>
        <footer class="footer text-center">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-3">
                    @auth
                        @php
                            $centerId = Auth::user()->access?->center?->centerid;
                            $logo = config("center_logos.$centerId");
                        @endphp
                        @if($logo)
                            <img
                                    src="{{ asset('img/' . $logo['image']) }}"
                                    alt="Logo"
                                    style="width: {{ $logo['width'] }}; height: {{ $logo['height'] }}"
                            >
                        @endif
                    @endauth
                </div>
            </div>
        </footer>
    </div>

</div>


<div class="chat-windows"></div>

@include('layouts.Partials.Scripts')
@include('layouts.Scripts.Messages')
@yield('script')


</body>

</html>
