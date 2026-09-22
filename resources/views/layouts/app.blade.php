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

                    @can('CONTROL DE PATIOS')
                        <li class="sidebar-item">

                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                               aria-expanded="false">
                                <img src="{{asset('ico/carga.png')}}"
                                     alt="" class="img-16 ml-2 mr-2">
                                <span class="hide-menu"> Control de patios</span>
                            </a>

                            <ul aria-expanded="false" class="collapse first-level">
                                @can('yard.dashboard')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Dashboard</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('dashboardyard')
                                                <li class="sidebar-item"><a
                                                            href="{{route('dashboardyard')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-display"></i>
                                                        <span class="hide-menu"> Dashboard 1 </span></a>
                                                </li>
                                            @endcan

                                            @can('vitualyard')
                                                <li class="sidebar-item"><a
                                                            href="{{route('vitualyard')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-delivery"></i>
                                                        <span class="hide-menu"> Yard</span></a>
                                                </li>
                                            @endcan


                                        </ul>
                                    </li>
                                @endcan

                                @can('yard.stock')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Existencia</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('receiptunits.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('receiptunits.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-display"></i>
                                                        <span class="hide-menu"> Existencia de cajas </span></a>
                                                </li>
                                            @endcan

                                            @can('receipttrucks.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('receipttrucks.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-delivery"></i>
                                                        <span class="hide-menu"> Existencia de tractos</span></a>
                                                </li>
                                            @endcan

                                            @can('authorizetransportentry.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('authorizetransportentry.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-alarm-clock"></i>
                                                        <span class="hide-menu"> Pendiente de entrada</span></a>
                                                </li>
                                            @endcan

                                            @can('reportauthorizedoutputs')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reportauthorizedoutputs')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-delivery"></i>
                                                        <span class="hide-menu"> Pendiente de salida</span></a>
                                                </li>
                                            @endcan

                                            @can('inventorybox.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventorybox.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-layout"></i>
                                                        <span class="hide-menu"> Programar inventario</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('yard.follow-up')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Seguimiento</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('monitorunitsyard')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitorunitsyard')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Monitor de cajas</span></a>
                                                </li>
                                            @endcan
                                            @can('monitorunitsyard')
                                                <li class="sidebar-item"><a
                                                            href="{{route('processmonitoring.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Seguimiento de procesos</span></a>
                                                </li>
                                            @endcan
                                            @can('assignshipment')
                                                <li class="sidebar-item"><a
                                                            href="{{route('assignshipment')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Asignación de embarques</span></a>
                                                </li>
                                            @endcan
                                            @can('asssigntransporttoshipment.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('asssigntransporttoshipment.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Asignar transporte</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('yard.circuit')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Circuito</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            @can('monitorcircuitplant')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitorcircuitplant')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Monitor de circuito</span></a>
                                                </li>
                                            @endcan
                                            @can('generateaccesswarehouse.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('generateaccesswarehouse.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Crear Qr de acceso</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('yard.reports')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Reportes</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            @can('reportsyards')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reportsyards')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Reportes</span></a>
                                                </li>
                                            @endcan

                                            @can('layoutcapacitycenter')
                                                <li class="sidebar-item"><a
                                                            href="{{route('layoutcapacitycenter')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Ocupación</span></a>
                                                </li>
                                            @endcan

                                            @can('reportstays')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reportstays')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Estadia</span></a>
                                                </li>
                                            @endcan

                                            @can('tracereceiptunit.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('tracereceiptunit.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Kardex de folio</span></a>
                                                </li>
                                            @endcan

                                            @can('receipttrucktrace.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('receipttrucktrace.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Kardex de tracto</span></a>
                                                </li>
                                            @endcan


                                        </ul>
                                    </li>
                                @endcan

                                @can('yard.events')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Eventos</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('movebox')
                                                <li class="sidebar-item"><a
                                                            href="{{route('movebox')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Reubicar unidad</span></a>
                                                </li>
                                            @endcan
                                            @can('viewkeyreception')
                                                <li class="sidebar-item"><a
                                                            href="{{route('viewkeyreception')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Recepción de llaves</span></a>
                                                </li>
                                            @endcan
                                            @can('viewmovementsofbox')
                                                <li class="sidebar-item"><a
                                                            href="{{route('viewmovementsofbox')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Registrar llegada</span></a>
                                                </li>
                                            @endcan
                                            @can('autorizeoutput')
                                                <li class="sidebar-item"><a
                                                            href="{{route('autorizeoutput')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Autorizar salida</span></a>
                                                </li>
                                            @endcan
                                            @can('autorizeoutput')
                                                <li class="sidebar-item"><a
                                                            href="{{route('authorize-empty')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Autorizar salida vacia</span></a>
                                                </li>
                                            @endcan
                                            @can('refuseunit')
                                                <li class="sidebar-item"><a
                                                            href="{{route('refuseunit')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Rechazar unidad</span></a>
                                                </li>
                                            @endcan
                                            @can('storagejointrucktobox.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagejointrucktobox.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Emparejar remolque</span></a>
                                                </li>
                                            @endcan
                                            @can('receiptunits.autorizeunit')
                                                <li class="sidebar-item"><a
                                                            href="{{route('receiptunits.autorizeunit')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Reutilizar unidad</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('yard.administration')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Administración</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('adjustmentbox')
                                                <li class="sidebar-item"><a
                                                            href="{{route('adjustmentbox')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Salida directa</span></a>
                                                </li>
                                            @endcan
                                            @can('receiptunits.adjustmentunit')
                                                <li class="sidebar-item"><a
                                                            href="{{route('adjustmentunit')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Ajuste de unidad</span></a>
                                                </li>
                                            @endcan
                                            @can('storagelabeltrucks.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagelabeltrucks.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Etiquetas de caja y tracto</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('yard.catalogs')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Catalogos</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('ordercustomers.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('ordercustomers.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-customer"></i>
                                                        <span class="hide-menu"> Clientes</span></a>
                                                </li>
                                            @endcan
                                            @can('storageareas.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storageareas.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-text-align-left"></i>
                                                        <span class="hide-menu"> Areas</span></a>
                                                </li>
                                            @endcan
                                            @can('storageunittypes.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storageunittypes.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-text-align-left"></i>
                                                        <span class="hide-menu"> Origenes</span></a>
                                                </li>
                                            @endcan
                                            @can('shipmenttrucks.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('shipmenttrucks.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Tipos de unidad</span></a>
                                                </li>
                                            @endcan
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
                                                        <span class="hide-menu"> Proveedores</span></a>
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
                                            @can('shipmenttruckcategories.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('shipmenttruckcategories.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Categorias de transporte</span></a>
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
                                            @can('storageunitstatus.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storageunitstatus.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Estatus de unidad</span></a>
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
                                            @can('storagesealloto.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagesealloto.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Candados LOTO</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('storagetask.index')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('storagetask.index')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span
                                                    class="hide-menu"> Tareas</span></a>
                                    </li>
                                @endcan

                                @can('printerlabel')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('printlabelstock')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span
                                                    class="hide-menu"> Generar etiquetas</span></a>
                                    </li>
                                @endcan

                                @can('receiptunitticket.index')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('receiptunitticket.index')}}" aria-expanded="false">
                                            <i class="lni lni-cloud-sync"></i>
                                            <span class="hide-menu"> Ticket de báscula</span></a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

                    @can('EMBARQUES')
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark"
                               href="javascript:void(0)" aria-expanded="false">
                                <img src="{{asset('ico/cadena-de-suministro.png')}}" alt="" class="img-16 ml-2 mr-2">
                                <span class="hide-menu"> Embarques</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                @can('EMBARQUES.schedulle')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Horarios
                                            </span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('orderdeliveries.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('orderdeliveries.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-calendar"></i>
                                                        <span class="hide-menu"> Planeación </span></a>
                                                </li>
                                            @endcan

                                            @can('monitoroperationbrid')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitoroperationbrid')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-calendar"></i>
                                                        <span class="hide-menu"> Horario de carga</span></a>
                                                </li>
                                            @endcan

                                            @can('monitoranalistatrafico.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitoranalistatrafico.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Monitor de trafico</span></a>
                                                </li>
                                            @endcan
                                            @can('monitoroperation.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitoroperation.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Monitor de operaciones</span></a>
                                                </li>
                                            @endcan
                                            @can('ordermonitor.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('ordermonitor.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Monitor general</span></a>
                                                </li>
                                            @endcan
                                            @can('statusshipmentchek')
                                                <li class="sidebar-item"><a
                                                            href="{{route('statusshipmentchek')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-dashboard"></i>
                                                        <span class="hide-menu"> Monitor de verificación</span></a>
                                                </li>
                                            @endcan
                                            @can('calculatetimeshipment')
                                                <li class="sidebar-item"><a
                                                            href="{{route('calculatetimeshipment')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-calculator"></i>
                                                        <span class="hide-menu"> Calcular tiempo de embarque  </span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('EMBARQUES.follow-up')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Seguimiento </span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            @can('monitoranalistatrafico.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitor-customer-service')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Customer Service</span></a>
                                                </li>
                                            @endcan
                                            @can('monitoranalistatrafico.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitor-backlog')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> BackLog</span></a>
                                                </li>
                                            @endcan
                                            @can('shipmentjourney')
                                                <li class="sidebar-item"><a
                                                            href="{{route('shipmentjourney.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Seguimiento de entrega</span></a>
                                                </li>
                                            @endcan
                                            @can('monitoroperationhenkel')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitoroperationhenkel')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Monitor de operaciones</span></a>
                                                </li>
                                            @endcan
                                            @can('monitortrafico')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitortrafico')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Monitor de arribos</span></a>
                                                </li>
                                            @endcan
                                            @can('shipments-panels')
                                                <li class="sidebar-item"><a
                                                            href="{{route('shipments-panels')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Semáforo de arribos  </span></a>
                                                </li>
                                            @endcan

                                        </ul>
                                    </li>
                                @endcan

                                @can('EMBARQUES.journeys')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Viajes</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            @can('datatransport.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('shipment-transport.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Asignar viaje</span></a>
                                                </li>
                                            @endcan
                                            @can('shipmentstatustransport')
                                                <li class="sidebar-item"><a
                                                            href="{{route('shipmentstatustransport')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Estatus de embarque</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('EMBARQUES.reports')
                                    <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                                href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Reportes</span></a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            @can('reporteshipments')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reporteshipments')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Reportes</span></a>
                                                </li>
                                            @endcan
                                            @can('dashboard-shipment')
                                                <li class="sidebar-item"><a
                                                            href="{{route('dashboard-shipment')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-dashboard"></i>
                                                        <span class="hide-menu"> Dashboard</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('EMBARQUES.incidents')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Incidencias en entrega</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('storageincidencesjourneys.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storageincidencesjourneys.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Reportar incidencias</span></a>
                                                </li>
                                            @endcan

                                            @can('followupofincidences.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('followupofincidences.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Seguimiento de incidencias</span></a>
                                                </li>
                                            @endcan
                                            @can('monitorpods.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('monitorpods.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Monitor de PODs</span></a>
                                                </li>
                                            @endcan
                                            @can('storagekeyincidencesopetations.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagekeyincidencesopetations.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Firmas de almacen</span></a>
                                                </li>
                                            @endcan
                                            @can('storagekeyincidencestransport.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagekeyincidencestransport.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Firma de transporte</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('EMBARQUES.inverse')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Logística Inversa</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('storagescheduleinverse.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagescheduleinverse.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Logística Inversa</span></a>
                                                </li>
                                            @endcan
                                            @can('scheduleinverse.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('scheduleinverse.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Horario de descarga</span></a>
                                                </li>
                                            @endcan
                                            @can('reserveschedulleinverse.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reserveschedulleinverse.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Generar Cita</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('EMBARQUES.garrett')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu"> Garrett Mxl</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('storageevidenceprocess.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storageevidenceprocess.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Embarques </span></a>
                                                </li>
                                            @endcan
                                            @can('storageevidenceprocess.dashboard')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storageevidenceprocess.dashboard')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Seguimiento de embarque</span></a>
                                                </li>
                                            @endcan
                                            @can('reportauditshipments.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reportauditshipments.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Reporte de auditoria</span></a>
                                                </li>
                                            @endcan
                                            @can('reportauditshipments.dashboard')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reportdashboard')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Seguimiento DC</span></a>
                                                </li>
                                            @endcan
                                            @can('reportevidence.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('reportevidence.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Reportes</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('EMBARQUES.catalogs')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Catalogos</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('storagecategoryincidences.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagecategoryincidences.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Categorias de incidencias</span></a>
                                                </li>
                                            @endcan
                                            @can('shipmenttypes.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('shipmenttypes.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Tipo de embarque</span></a>
                                                </li>
                                            @endcan
                                            @can('ordercustomers.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('ordercustomers.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Clientes</span></a>
                                                </li>
                                            @endcan
                                            @can('storagedoorstage.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagedoorstage.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Cortinas / Stages</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('layoutyard')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('layout-stages')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span
                                                    class="hide-menu">LayOut</span></a>
                                    </li>
                                @endcan

                                @can('storageshipmentfolios.index')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('storageshipmentfolios.index')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span
                                                    class="hide-menu"> Folios de conteo</span></a>
                                    </li>
                                @endcan

                                @can('edilogs.index')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('edilogs.index')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span
                                                    class="hide-menu"> Los de importación</span></a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

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

                                            @can('schedulereceipt.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('schedulereceipt.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Horario V1</span></a>
                                                </li>
                                            @endcan

                                            @can('appointmentschedule')
                                                <li class="sidebar-item"><a
                                                            href="{{route('appointmentschedule')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Horario V2</span></a>
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
                                            @can('receiptproviders.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('receiptproviders.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Proveedores</span></a>
                                                </li>
                                            @endcan
                                            @can('storagereceiptperiods.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagereceiptperiods.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Periodos</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                @can('monitorreceiptplant')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('monitorreceiptplant')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span
                                                    class="hide-menu">Transitos</span></a>
                                    </li>
                                @endcan

                                @can('monitortransportreceipt')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('monitortransportreceipt')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span
                                                    class="hide-menu">Generar QR</span></a>
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

                                @can('INVENTARIO.inventoryfiscal')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Inventario Fiscal</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('inventoryheads.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventoryheads.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-layers"></i>
                                                        <span class="hide-menu"> Inventarios</span></a>
                                                </li>
                                            @endcan

                                            @can('inventorycards.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventorycards.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Cédulas de conteo</span></a>
                                                </li>
                                            @endcan

                                            @can('inventoryaisles.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventoryaisles.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Pasillos</span></a>
                                                </li>
                                            @endcan

                                            @can('inventoryareas.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('inventoryareas.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Areas</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

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

                                @can('inventory-sap-migration')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('inventory-sap-migration')}}" aria-expanded="false"><i
                                                    class="mdi mdi-creation"></i><span class="hide-menu">Migración de SAP</span></a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

                    @can('MTNTRUCK')
                        <li class="sidebar-item"><a class="sidebar-link has-arrow waves-effect waves-dark"
                                                    href="javascript:void(0)" aria-expanded="false">
                                {{--                                <i class="mdi mdi-collage"></i>--}}
                                <img src="{{asset('ico/maquina-elevadora.png')}}" alt="" class="img-16 ml-2 mr-2">
                                <span class="hide-menu"> Mantenimiento</span></a>
                            <ul aria-expanded="false" class="collapse first-level">


                                @can('dashboardtrucks')
                                    <li class="sidebar-item"><a
                                                class="sidebar-link waves-effect waves-dark sidebar-link"
                                                href="{{route('dashboardtrucks')}}" aria-expanded="false">
                                            <i class="lni lni-dashboard"></i>
                                            <span class="hide-menu">Equipos en uso</span></a>
                                    </li>
                                @endcan

                                <li class="sidebar-item">
                                    @can('MTNTRUCK.reports')
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Reportes</span>
                                        </a>
                                    @endcan

                                    <ul aria-expanded="false" class="collapse second-level">
                                        @can('checklistlifttrucks.index')
                                            <li class="sidebar-item"><a
                                                        href="{{route('checklistlifttrucks.index')}}"
                                                        class="sidebar-link">
                                                    <i class="lni lni-layers"></i>
                                                    <span class="hide-menu"> Check list realizados</span></a>
                                            </li>
                                        @endcan
                                        @can('storageassignlifttrucks.index')
                                            <li class="sidebar-item"><a
                                                        href="{{route('storageassignlifttrucks.index')}}"
                                                        class="sidebar-link">
                                                    <i class="lni lni-network"></i>
                                                    <span class="hide-menu"> Asignación de equipos</span></a>
                                            </li>
                                        @endcan
                                        @can('storagelifttrucks.reports')
                                            <li class="sidebar-item"><a
                                                        href="{{route('storagelifttrucksreports')}}"
                                                        class="sidebar-link">
                                                    <i class="mdi mdi-book"></i>
                                                    <span class="hide-menu"> Reportes</span></a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>


                                <li class="sidebar-item">
                                    @can('MTNTRUCK.configuration')
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Configuración</span>
                                        </a>
                                    @endcan
                                    <ul aria-expanded="false" class="collapse second-level">
                                        @can('storagechecklistlifttrucks.index')
                                            <li class="sidebar-item"><a
                                                        href="{{route('storagechecklistlifttrucks.index')}}"
                                                        class="sidebar-link">
                                                    <i class="lni lni-layers"></i>
                                                    <span class="hide-menu"> Check List</span></a>
                                            </li>
                                        @endcan
                                        @can('storagelifttrucks.index')
                                            <li class="sidebar-item"><a
                                                        href="{{route('storagelifttrucks.index')}}"
                                                        class="sidebar-link">
                                                    <i class="lni lni-weight"></i>
                                                    <span class="hide-menu"> Montacargas</span></a>
                                            </li>
                                        @endcan
                                        @can('storagelifttruckscatalogs')
                                            <li class="sidebar-item"><a
                                                        href="{{route('storagelifttruckscatalogs')}}"
                                                        class="sidebar-link">
                                                    <i class="mdi mdi-book"></i>
                                                    <span class="hide-menu"> Catalogos</span></a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>

                            </ul>
                        </li>
                    @endcan

                    @can('SafetyTours')
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                               aria-expanded="false">
                                <img src="{{asset('ico/reporte-de-negocios.png')}}"
                                     alt="" class="img-16 ml-2 mr-2">
                                <span class="hide-menu">Recorridos de Seguridad</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('SafetyTours.security')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-collage"></i><span class="hide-menu">Recorridos vigilancia</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('storagesafetytours.index')
                                                <li class="sidebar-item"><a href="{{route('storagesafetytours.index')}}"
                                                                            class="sidebar-link">
                                                        <i class="lni lni-list"></i>
                                                        <span class="hide-menu"> Recorridos</span></a>
                                                </li>
                                            @endcan

                                            @can('storagesafetytourtype.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storagesafetytourtype.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-lock"></i>
                                                        <span class="hide-menu"> Rutas </span></a>
                                                </li>
                                            @endcan

                                            @can('storagetags.index')
                                                <li class="sidebar-item"><a href="{{route('storagetags.index')}}"
                                                                            class="sidebar-link">
                                                        <i class="lni lni-tag"></i>
                                                        <span class="hide-menu"> Registrar Tag</span></a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan
                                @can('SafetyTours.operation')
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow waves-effect waves-dark"
                                           href="javascript:void(0)" aria-expanded="false"><i
                                                    class="mdi mdi-receipt"></i><span
                                                    class="hide-menu">Recorridos internos</span></a>
                                        <ul aria-expanded="false" class="collapse second-level">

                                            @can('storage-incidence-safeties.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storage-incidence-safeties.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-archive"></i>
                                                        <span class="hide-menu"> Incidencias</span></a>
                                                </li>
                                            @endcan
                                            @can('dashboard-incidents')
                                                <li class="sidebar-item"><a
                                                            href="{{route('dashboard-incidents')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-dashboard"></i>
                                                        <span class="hide-menu"> Dashboard</span></a>
                                                </li>
                                            @endcan
                                            @can('storage-category-safeties.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('storage-category-safeties.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-package"></i>
                                                        <span class="hide-menu"> Categorías y subcategorías</span></a>
                                                </li>
                                            @endcan
                                            @can('responsible-area-security.index')
                                                <li class="sidebar-item"><a
                                                            href="{{route('responsible-area-security.index')}}"
                                                            class="sidebar-link">
                                                        <i class="lni lni-layout"></i>
                                                        <span class="hide-menu"> Areas</span></a>
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
                                                href="{{route('activitylog.index')}}" aria-expanded="false"><i
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
