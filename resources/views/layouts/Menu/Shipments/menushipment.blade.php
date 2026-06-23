@extends('layouts.app')

@section('content')

    <main>
        <h5 class="name-menu"> Monitoreo</h5>
        <div class="container__box">
            @can('orderdeliveries.index')
                <a href="{{route('orderdeliveries.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Modulo para administrar los embarques, permite ver el Kardex de eventos, evidencias fotograficas y documental.">
                        <i class="lni lni-calendar"></i>
                        <h5>Planeación</h5>
                        <h4>Planeación</h4>
                    </div>
                </a>
            @endcan
            @can('monitoroperationbrid')
                <a href="{{route('monitoroperationbrid')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Modulo para administrar los embarques, permite ver el Kardex de eventos, evidencias fotograficas y documental.">
                        <i class="lni lni-calendar"></i>
                        <h5>Horario de Carga</h5>
                        <h4>Horario de Carga</h4>
                    </div>
                </a>
            @endcan
            @can('monitoranalistatrafico.index')
                <a href="{{route('monitoranalistatrafico.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Realice su seguimiento de unidades, tiempos y eventos de facturación en este modulo.">
                        <i class="lni lni-display-alt"></i>
                        <h5>Monitor Trafico</h5>
                        <h4>Monitor Trafico</h4>
                    </div>
                </a>
            @endcan
            @can('monitoroperation.index')
                <a href="{{route('monitoroperation.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Realice su seguimiento de eventos logisticos de cada embarque, proceso de verificación,etc.">
                        <i class="lni lni-display-alt"></i>
                        <h5>Monitor Operativo</h5>
                        <h4>Monitor Operativo</h4>
                    </div>
                </a>
            @endcan
            @can('monitoranalistatrafico.index')
                <a href="{{route('monitor-customer-service')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Realice su seguimiento de unidades, tiempos y eventos de facturación en este modulo.">
                        <i class="lni lni-display-alt"></i>
                        <h5>Customer Service</h5>
                        <h4>Customer Service</h4>
                    </div>
                </a>
            @endcan
            @can('monitoranalistatrafico.index')
                <a href="{{route('monitor-backlog')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Realice su seguimiento de unidades, tiempos y eventos de facturación en este modulo.">
                        <i class="lni lni-display-alt"></i>
                        <h5>BackLog</h5>
                        <h4>BackLog</h4>
                    </div>
                </a>
            @endcan


            @can('ordermonitor.index')
                <a href="{{route('ordermonitor.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Semáforo de embarques para asegurar la ejecución de los eventos logísticos en tiempo.">
                        <i class="lni lni-graph"></i>
                        <h5>M. General</h5>
                        <h4>M. General</h4>
                    </div>
                </a>
            @endcan
            @can('shipmentjourney')
                <a href="{{route('shipmentjourney.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Permite llevar un seguimiento de cada embarque desde que sale del centro de distibución hasta que entrega
                    al cliente final (PMV).">
                        <i class="lni lni-laptop-phone"></i>
                        <h5>Seg. de Viaje</h5>
                        <h4>Seg. de Viaje</h4>
                    </div>
                </a>
            @endcan
            @can('datatransport.index')
                <a href="{{route('datatransport.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Muestra los embarques asignados a las lineas de transporte y permite capturar la información
                    del transporte y operador que se presentara a cargar en el centro distribución.">
                        <i class="lni lni-target-customer"></i>
                        <h5>Asignar viaje</h5>
                        <h4>Asignar viaje</h4>
                    </div>
                </a>
            @endcan
            @can('reporteshipments')
                <a href="{{route('reporteshipments')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Módulo generar reportes de proceso de embarques, sellos, cajas, etc.">
                        <i class="lni lni-library"></i>
                        <h5>Reportes</h5>
                        <h4>Reportes</h4>
                    </div>
                </a>
            @endcan

            @can('edilogs.index')
                <a href="{{route('edilogs.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Módulo para revisar los errores al momento de realizar procesos de importación de embarques.">
                        <i class="lni lni-database"></i>
                        <h5>Logs Importación</h5>
                        <h4>Logs Importación</h4>
                    </div>
                </a>
            @endcan


            @can('shipmentstatustransport')
                <a href="{{route('shipmentstatustransport')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Valide el estatus de su transporte en base a su cita de carga en el centro de distribución.">
                        <i class="lni lni-dashboard"></i>
                        <h5>Estatus Embarques</h5>
                        <h4>Estatus Embarques</h4>
                    </div>
                </a>
            @endcan
            @can('statusshipmentchek')
                <a href="{{route('statusshipmentchek')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Valide el estatus de su transporte en base a su cita de carga en el centro de distribución.">
                        <i class="lni lni-dashboard"></i>
                        <h5>Mon. Verificación</h5>
                        <h4>Mon. Verificación</h4>
                    </div>
                </a>
            @endcan
            @can('monitortrafico')
                <a href="{{route('monitortrafico')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Valide el estatus de su transporte en base a su cita de carga en el centro de distribución.">
                        <i class="lni lni-dashboard"></i>
                        <h5>Monitor de arribos</h5>
                        <h4>Monitor de arribos</h4>
                    </div>
                </a>
            @endcan
            @can('monitoroperationhenkel')
                <a href="{{route('monitoroperationhenkel')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Valide el estatus de su transporte en base a su cita de carga en el centro de distribución.">
                        <i class="lni lni-dashboard"></i>
                        <h5>Mon. De Oper</h5>
                        <h4>Mon. De Oper</h4>
                    </div>
                </a>
            @endcan
            @can('layoutyard')
                <a href="{{route('layout-stages')}}" target="_blank">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Muestra el plano digital del patio con información
                     de las unidades, estatus de la ubicación y capacidad de patio.">
                        <i class="lni lni-microsoft"></i>
                        <h5>LayOut</h5>
                        <h4>LayOut</h4>
                    </div>
                </a>
            @endcan
            @can('storageshipmentfolios.index')
                <a href="{{route('storageshipmentfolios.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="">
                        <i class="lni lni-microsoft"></i>
                        <h5>Folios de conteo</h5>
                        <h4>Folios de conteo</h4>
                    </div>
                </a>
            @endcan


        </div>

        @can('storageevidenceprocess.index')
            <h5 class="name-menu"> Embarques</h5>
            <div class="container__box">
                @can('storageevidenceprocess.index')
                    <a href="{{route('storageevidenceprocess.index')}}">
                        <div class="box">
                            <i class="lni lni-travel"></i>
                            <h5>Embarques</h5>
                            <h4>Embarques</h4>
                        </div>
                    </a>
                @endcan
                @can('storageevidenceprocess.dashboard')
                    <a href="{{route('storageevidenceprocess.dashboard')}}">
                        <div class="box">
                            <i class="lni lni-dashboard"></i>
                            <h5>Seguimiento Emb.</h5>
                            <h4>Seguimiento Emb.</h4>
                        </div>
                    </a>
                @endcan

                @can('reportevidence.index')
                    <a href="{{route('reportevidence.index')}}">
                        <div class="box">
                            <i class="lni lni-select"></i>
                            <h5>Reporte Embarque</h5>
                            <h4>Reporte Embarque</h4>
                        </div>
                    </a>
                @endcan

                @can('reportauditshipments.index')
                    <a href="{{route('reportauditshipments.index')}}">
                        <div class="box">
                            <i class="lni lni-search"></i>
                            <h5>Reporte Auditoria</h5>
                            <h4>Reporte Auditoria</h4>
                        </div>
                    </a>
                @endcan

                @can('reportauditshipments.dashboard')
                    <a href="{{route('reportdashboard')}}">
                        <div class="box">
                            <i class="lni lni-layout"></i>
                            <h5>Seguimiento DC</h5>
                            <h4>Seguimiento DC</h4>
                        </div>
                    </a>
                @endcan
            </div>
        @endcan


        <h5 class="name-menu"> Logistica Inversa</h5>
        <div class="container__box">

            @can('storageincidencesjourneys.index')
                <a href="{{route('storageincidencesjourneys.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Módulo para registrar las incidencias en sus entregas y
                     generar el folio de devolución al centro de distribución.">
                        <i class="lni lni-delivery"></i>
                        <h5>Reportar Inc.</h5>
                        <h4>Reportar Inc.</h4>
                    </div>
                </a>
            @endcan
            @can('followupofincidences.index')
                <a href="{{route('followupofincidences.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Módulo para dar seguimiento a las incidencias reportadas por las lineas de transporte, podrá generar su
                    folio de entrega de la devolución.">
                        <i class="lni lni-chromecast"></i>
                        <h5>Seguimiento Inc.</h5>
                        <h4>Seguimiento Inc.</h4>
                    </div>
                </a>
            @endcan
            @can('monitorpods.index')
                <a href="{{route('monitorpods.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Módulo para subir sus evidencias de entrega con el cliente final.">
                        <i class="lni lni-invest-monitor"></i>
                        <h5>Monitor PODs</h5>
                        <h4>Monitor PODs</h4>
                    </div>
                </a>
            @endcan

            @can('storagekeyincidencesopetations.index')
                <a href="{{route('storagekeyincidencesopetations.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Módulo para dar seguimiento a las incidencias reportadas por las lineas de transporte, podrá generar su
                    folio de entrega de la devolución.">
                        <i class="lni lni-key"></i>
                        <h5>Firmas Almacen</h5>
                        <h4>Firmas Almacen</h4>
                    </div>
                </a>
            @endcan
            @can('storagekeyincidencestransport.index')
                <a href="{{route('storagekeyincidencestransport.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Módulo para dar seguimiento a las incidencias reportadas por las lineas de transporte, podrá generar su
                    folio de entrega de la devolución.">
                        <i class="lni lni-files"></i>
                        <h5>Firmas Transporte</h5>
                        <h4>Firmas Transporte</h4>
                    </div>
                </a>
            @endcan
            @can('storagescheduleinverse.index')
                <a href="{{route('storagescheduleinverse.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Realice el seguimiento de cada embarque con incidencia registrada, desde la generación de la cita hasta la entrega
                    del produto por parte de la linea de transporte (PMV).">
                        <i class="lni lni-travel"></i>
                        <h5>Logistica Inversa</h5>
                        <h4>Logistica Inversa</h4>
                    </div>
                </a>
            @endcan
            @can('scheduleinverse.index')
                <a href="{{route('scheduleinverse.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Creación de periodos de horario de devoluciones.">
                        <i class="lni lni-dashboard"></i>
                        <h5>Horario</h5>
                        <h4>Horario</h4>
                    </div>
                </a>
            @endcan
            @can('reserveschedulleinverse.index')
                <a href="{{route('reserveschedulleinverse.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Creación de periodos de horario de devoluciones.">
                        <i class="lni lni-calendar"></i>
                        <h5>Generar Cita</h5>
                        <h4>Generar Cita</h4>
                    </div>
                </a>
            @endcan
        </div>

        <h5 class="name-menu"> Catalogos</h5>
        <div class="container__box">
            @can('ordercustomers.index')
                <a href="{{route('ordercustomers.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catálogo de clientes y sus datos generales.">
                        <i class="lni lni-customer"></i>
                        <h5>Clientes</h5>
                        <h4>Clientes</h4>
                    </div>
                </a>
            @endcan
            @can('shipmenttypes.index')
                <a href="{{route('shipmenttypes.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Catálogo de tipos de embarques.">
                        <i class="lni lni-menu"></i>
                        <h5>Tipos de Emb.</h5>
                        <h4>Tipos de Emb.</h4>
                    </div>
                </a>
            @endcan
            @can('storagecategoryincidences.index')
                <a href="{{route('storagecategoryincidences.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catálogo de categorias de incidencia en los modulos de recibo y embarques.">
                        <i class="lni lni-menu"></i>
                        <h5>C. de Incidencia</h5>
                        <h4>C. de Incidencia</h4>
                    </div>
                </a>
            @endcan
            @can('storagedoorstage.index')
                <a href="{{route('storagedoorstage.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catálogo de categorias de incidencia en los modulos de recibo y embarques.">
                        <i class="lni lni-layers"></i>
                        <h5>Cortinas / Stages</h5>
                        <h4>Cortinas / Stages</h4>
                    </div>
                </a>
            @endcan

        </div>
    </main>

@endsection

