@extends('layouts.app')

@section('content')

    <main>
        {{--        <h1 class="name-menu"> Yard</h1>--}}
        <div class="container__box">
            @can('receiptunits.index')
                <a href="{{route('receiptunits.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Revise la existencia de las unidades registradas en sistema, imprima formato de folio,
                     etiqueta y registre nuevas unidades que ingresan su patio.">
                        <i class="lni lni-display"></i>
                        <h5>Exist. de cajas</h5>
                        <h4>Exist. de cajas</h4>
                    </div>
                </a>
            @endcan
            @can('receipttrucks.index')
                <a href="{{route('receipttrucks.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Revise la existencia de tractos registradas en sistema, imprima formato de folio,
                     etiqueta y registre nuevos tractos que ingresan su patio.">
                        <i class="lni lni-delivery"></i>
                        <h5>Exist. Tractos</h5>
                        <h4>Exist. Tractos</h4>
                    </div>
                </a>
            @endcan
            @can('authorizetransportentry.index')
                <a href="{{route('authorizetransportentry.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Muestra la unidades que estan pendientes de realizar
                            check list de entrada al activarse el parametro de esta función.">
                        <i class="lni lni-alarm-clock"></i>
                        <h5>Pend. De Entrada</h5>
                        <h4>Pend. De Entrada</h4>
                    </div>
                </a>
            @endcan
            @can('reportauthorizedoutputs')
                <a href="{{route('reportauthorizedoutputs')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Permite ver las unidades que estan autorizadas
                     para salir del patio y el tiempo de estadia despues de la
                    autorización.">
                        <i class="lni lni-exit-up"></i>
                        <h5>Salidas Pendientes</h5>
                        <h4>Salidas Pendientes</h4>
                    </div>
                </a>
            @endcan
            @can('monitorcircuitplant')
                <a href="{{route('monitorcircuitplant')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Muestra información del seguimiento del circuito, los tiempos de cada proceso de la unidad segun
                    la ubicación donde se encuentre (Planta / Trayecto / Cedis ).">
                        <i class="lni lni-display-alt"></i>
                        <h5>Mont. Circuito</h5>
                        <h4>Mont. Circuito</h4>
                    </div>
                </a>
            @endcan


            @can('monitorunitsyard')
                <a href="{{route('monitorunitsyard')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Permite darle seguimiento a los tiempos de procesos operativos a cada unidad registrada en sistema.">
                        <i class="lni lni-layout"></i>
                        <h5>Monit. Cajas</h5>
                        <h4>Monit. Cajas</h4>
                    </div>
                </a>
            @endcan
            @can('generateaccesswarehouse.index')
                <a href="{{route('generateaccesswarehouse.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Modulo para generar código QR para los modulos del circuito entre planta de producción y Cedis.">
                        <i class="lni lni-protection"></i>
                        <h5>QR Circuito</h5>
                        <h4>QR Circuito</h4>
                    </div>
                </a>
            @endcan
        </div>

        <div class="container__box mt-4">
            @can('dashboardyard')
                <a href="{{route('dashboardyard')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Visualice el uso de su patio.">
                        <i class="lni lni-dashboard"></i>
                        <h5>Dashboard Yard</h5>
                        <h4>Dashboard Yard</h4>
                    </div>
                </a>
            @endcan
            @can('patiohenkeltoluca')
                <a href="{{route('patiohenkeltoluca')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Visualice el uso de su patio.">
                        <i class="lni lni-dashboard"></i>
                        <h5>Yard</h5>
                        <h4>Yard</h4>
                    </div>
                </a>
            @endcan
            @can('tracereceiptunit.index')
                <a href="{{route('tracereceiptunit.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Revise la  información relacionada al folio de cada unidad, en este modulo podra revisar los eventos,
                    evidencia fotografica de entrada, calidad, salida y check list de cada proceso.">
                        <i class="lni lni-zoom-in"></i>
                        <h5>Kardex Folio</h5>
                        <h4>Kardex Folio</h4>
                    </div>
                </a>
            @endcan
            @can('receipttrucktrace.index')
                <a href="{{route('receipttrucktrace.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Muestra la información de cuantas veces una placa de tracto ingreso a nuestro centro de distribución.">
                        <i class="lni lni-funnel"></i>
                        <h5>Kardex tracto</h5>
                        <h4>Kardex traco</h4>
                    </div>
                </a>
            @endcan
            @can('layoutcapacitycenter')
                <a href="{{route('layoutcapacitycenter')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Muestra la información de la ocupación de nuestro patio de maniobras dividido por zonas.">
                        <i class="lni lni-layers"></i>
                        <h5>Ocupación</h5>
                        <h4>Ocupación</h4>
                    </div>
                </a>
            @endcan

            @can('reportstays')
                <a href="{{route('reportstays')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Lleva el control de tus estadias de cada unidad registrada desde que ingresa al patio.">
                        <i class="lni lni-timer"></i>
                        <h5>Repor. Estadia</h5>
                        <h4>Repor. Estadia</h4>
                    </div>
                </a>
            @endcan
            @can('reportsyards')
                <a href="{{route('reportsyards')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Muestra los diferentes reportes del sistema, todos se exportan directamente a Excel.">
                        <i class="lni lni-bar-chart"></i>
                        <h5>Reportes</h5>
                        <h4>Reportes</h4>
                    </div>
                </a>
            @endcan
        </div>

        <div class="container__box mt-4">
            @can('assignshipment')
                <a href="{{route('assignshipment')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Permite asignar el número de embarque a un número de folio existente en sistema.">
                        <i class="lni lni-folder"></i>
                        <h5>Asigna Complementos</h5>
                        <h4>Asigna Complementos</h4>
                    </div>
                </a>
            @endcan
            @can('storagejointrucktobox.index')
                <a href="{{route('storagejointrucktobox.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Realiza el emparejamiento de tracto y cajas que se encuentran en existencia dentro del patio de maniobras.">
                        <i class="lni lni-caravan"></i>
                        <h5>Emp. remolque</h5>
                        <h4>Emp. remolque</h4>
                    </div>
                </a>
            @endcan
            @can('receiptunits.autorizeunit')
                <a href="{{route('receiptunits.autorizeunit')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Permite reutilizar un folio con concepto de entrada
                        de Recibo para utilizarlo en el modulo de embarques, el proceso se realizar de manera semi automatica y genera un nuevo folio de entrada basado en el anterior.">
                        <i class="lni lni-gallery"></i>
                        <h5>Reutilizar folio</h5>
                        <h4>Reutilizar folio</h4>
                    </div>
                </a>
            @endcan
            @can('inventorybox.index')
                <a href="{{route('inventorybox.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Crea y dale seguimiento a tus inventarios de unidades mediante equipo movil.">
                        <i class="lni lni-layout"></i>
                        <h5>Programar Inv.</h5>
                        <h4>Programar Inv.</h4>
                    </div>
                </a>
            @endcan
            @can('storagetask.index')
                <a href="{{route('storagetask.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Modulo para crear tareas de enrampe, permite notificar al operador o patiero la ubicación origen y destino.">
                        <i class="lni lni-cogs"></i>
                        <h5>Tareas</h5>
                        <h4>Tareas</h4>
                    </div>
                </a>
            @endcan
            @can('storagesealloto.index')
                <a href="{{route('storagesealloto.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de etiquetas de cajas y tractos registrados en sistema.">
                        <i class="lni lni-lock"></i>
                        <h5>Candados LOTO</h5>
                        <h4>Candados LOTO</h4>
                    </div>
                </a>
            @endcan
        </div>

        <div class="container__box mt-4">
            @can('movebox')
                <a href="{{route('movebox')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Modulo para mover unidades registradas en sistema de unida ubicación a otra.">
                        <i class="lni lni-shuffle"></i>
                        <h5>Reubicar caja</h5>
                        <h4>Reubicar caja</h4>
                    </div>
                </a>
            @endcan
            @can('viewmovementsofbox')
                <a href="{{route('viewmovementsofbox')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Permite registrar el evento de llegada de la unidad a mesa de control al entregar su documentación.">
                        <i class="lni lni-arrow-down-circle"></i>
                        <h5>Registrar llegada</h5>
                        <h4>Registrar llegada</h4>
                    </div>
                </a>
            @endcan
            @can('autorizeoutput')
                <a href="{{route('autorizeoutput')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Permite autorizar la salida de unidad">
                        <i class="lni lni-share"></i>
                        <h5>Autorizar salida</h5>
                        <h4>Autorizar salida</h4>
                    </div>
                </a>
            @endcan
            @can('refuseunit')
                <a href="{{route('refuseunit')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Rechaza una unidad y desvincula el registro en los modulos de embarque o recibo según su concepto de entrada.">
                        <i class="lni lni-cross-circle"></i>
                        <h5>Rechazar caja</h5>
                        <h4>Rechazar caja</h4>
                    </div>
                </a>
            @endcan
            @can('registercheckout')
                <a href="{{route('registercheckout')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Registre su salida de unidad realizando un check list de la documentación y evidencia fotografica.">
                        <i class="lni lni-pointer"></i>
                        <h5>Salida de caja <span style="color: red">*</span></h5>
                        <h4>Salida de caja </h4>
                    </div>
                </a>
            @endcan
            @can('inventoryboxcount.index')
                <a href="{{route('inventoryboxcount.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Modulo para la ejecución del inventario de cajas desde el dispositivo movil.">
                        <i class="lni lni-laptop-phone"></i>
                        <h5>Inventario <span style="color: red">*</span></h5>
                        <h4>Inventario</h4>
                    </div>
                </a>
            @endcan
            @can('adjustmentbox')
                <a href="{{route('adjustmentbox')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Realiza la salida de unidad por ajuste de inventario de su patio.">
                        <i class="lni lni-shift-right"></i>
                        <h5>Salida directa</h5>
                        <h4>Salida directa</h4>
                    </div>
                </a>
            @endcan
            @can('receiptunits.adjustmentunit')
                <a href="{{route('adjustmentunit')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Actualice valores de las unidades registradas.">
                        <i class="lni lni-files"></i>
                        <h5>Ajuste de unidad</h5>
                        <h4>Ajuste de unidad</h4>
                    </div>
                </a>
            @endcan
        </div>

        <div class="container__box mt-4">
            @can('shipmentcarriers.index')
                <a href="{{route('shipmentcarriers.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de lineas de transporte, permite la alta,baja,actualización y parametrización de cada registro.">
                        <i class="lni lni-layout"></i>
                        <h5>Lineas de Trans.</h5>
                        <h4>Lineas de Trans.</h4>
                    </div>
                </a>
            @endcan
            @can('shipmenttruckcategories.index')
                <a href="{{route('shipmenttruckcategories.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de categorias de transporte (Tipos), permite la alta,baja,actualización y parametrización de cada registro.">
                        <i class="lni lni-notepad"></i>
                        <h5>Categ. de Trans.</h5>
                        <h4>Categ. de Trans.</h4>
                    </div>
                </a>
            @endcan
            @can('storagezones.index')
                <a href="{{route('storagezones.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de zonas en las cuales se encuentra dividio el patio de maniobras, cortinas, patios, precargas, etc.">
                        <i class="lni lni-grid"></i>
                        <h5>Zonas.</h5>
                        <h4>Zonas.</h4>
                    </div>
                </a>
            @endcan
            @can('storagelocations.index')
                <a href="{{route('storagelocations.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de ubicaciones del patio de maniobras.">
                        <i class="lni lni-stop"></i>
                        <h5>Ubicaciones</h5>
                        <h4>Ubicaciones</h4>
                    </div>
                </a>
            @endcan
            @can('storageunitstatus.index')
                <a href="{{route('storageunitstatus.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de estatus de unidades.">
                        <i class="lni lni-radio-button"></i>
                        <h5>Est. De Unidad</h5>
                        <h4>Est. De Unidad</h4>
                    </div>
                </a>
            @endcan
            @can('shipmenttrucks.index')
                <a href="{{route('shipmenttrucks.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de tipos de unidad (Capacidad)">
                        <i class="lni lni-library"></i>
                        <h5>Tipos de Unidad</h5>
                        <h4>Tipos de Unidad</h4>
                    </div>
                </a>
            @endcan
            @can('storageoperators.index')
                <a href="{{route('storageoperators.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de operadores registrados en sistema.">
                        <i class="lni lni-layout"></i>
                        <h5>Operadores</h5>
                        <h4>Operadores</h4>
                    </div>
                </a>
            @endcan
            @can('receiptproviders.index')
                <a href="{{route('receiptproviders.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de proveedores para el modulo de recibo de material.">
                        <i class="lni lni-users"></i>
                        <h5>Proveedores</h5>
                        <h4>Proveedores</h4>
                    </div>
                </a>
            @endcan
            @can('storagelabeltrucks.index')
                <a href="{{route('storagelabeltrucks.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de etiquetas de cajas y tractos registrados en sistema.">
                        <i class="lni lni-ticket"></i>
                        <h5>Etq. De caja</h5>
                        <h4>Etq. De cajas</h4>
                    </div>
                </a>
            @endcan
            @can('storagechecklists.index')
                <a href="{{route('storagechecklists.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de los diferentes tipos de check list que se usan en los diferentes modulos del sistema.">
                        <i class="lni lni-text-align-left "></i>
                        <h5>Check List</h5>
                        <h4>Check List</h4>
                    </div>
                </a>
            @endcan
            @can('printerlabel')
                <a href="{{route('printlabelstock')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de los diferentes tipos de check list que se usan en los diferentes modulos del sistema.">
                        <i class="lni lni-text-align-left"></i>
                        <h5>Generar Etiquetas</h5>
                        <h4>Generar Etiquetas</h4>
                    </div>
                </a>
            @endcan
            @can('storageareas.index')
                <a href="{{route('storageareas.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de los diferentes tipos de check list que se usan en los diferentes modulos del sistema.">
                        <i class="lni lni-text-align-left"></i>
                        <h5>Areas</h5>
                        <h4>Areas</h4>
                    </div>
                </a>
            @endcan
                @can('storageunittypes.index')
                <a href="{{route('storageunittypes.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Catalogo de los diferentes tipos de check list que se usan en los diferentes modulos del sistema.">
                        <i class="lni lni-text-align-left"></i>
                        <h5>Origenes</h5>
                        <h4>Origenes</h4>
                    </div>
                </a>
            @endcan
        </div>

        <div class="container__box mt-4">
            @can('webservicelog')
                <a href="{{route('webservicelog')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top"
                         title="Muestra la información de los eventos de conexión con otros sistemas externos.">
                        <i class="lni lni-website"></i>
                        <h5>Web Services</h5>
                        <h4>Web Services</h4>
                    </div>
                </a>
            @endcan
            @can('edishipments.index')
                <a href="{{route('edishipments.index')}}">
                    <div class="box" data-toggle="tooltip" data-placement="top" title="Permite ver las interfaces que se encuentran pendientes
                    de enviar a sistemas externos o los errores que estos
                    tienen al momento de realizar la conexión.">
                        <i class="lni lni-cloud-sync"></i>
                        <h5>Monit. Interfaces</h5>
                        <h4>Monit. Interfaces</h4>
                    </div>
                </a>
            @endcan
        </div>

    </main>

@endsection

