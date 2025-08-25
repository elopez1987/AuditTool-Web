@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header font-14 ">
                    Detalle de guía -- {{$orderdelivery->shipmentid}} -- <span>{{$orderdelivery->status->name}}</span>
                </div>
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                role="tab"><span class="hidden-sm-up">
                                     <img src="{{asset('ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
                                </span> <span class="hidden-xs-down">Detalle del embarque</span></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02" role="tab"><span
                                    class="hidden-sm-up">
                                    <img src="{{asset('ico/checklistin.png')}}" alt="" class="img-16 ml-2 mr-2">
                                </span> <span class="hidden-xs-down">Conteo en cortina</span></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03" role="tab"><span
                                    class="hidden-sm-up">
                                <img src="{{asset('ico/img.png')}}" alt="" class="img-16 ml-2 mr-2">
                                </span> <span class="hidden-xs-down">Fotos</span></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view04" role="tab"><span
                                    class="hidden-sm-up">
                                <img src="{{asset('ico/notepad_bloc_notes_15549.png')}}" alt=""
                                     class="img-16 ml-2 mr-2">
                                </span> <span class="hidden-xs-down">Comentarios</span></a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        {{--                        Detalle--}}
                        <div class="tab-pane active" id="view01" role="tabpanel">
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <a href="{{route('orderdeliveries.index')}}">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md"
                                                style="padding-right: 3px;"
                                                data-toggle="tooltip" data-placement="top" title="Regresar"
                                        >
                                            <img src="{{asset('/ico/back.png')}}" alt="" class="img-16"> Regresar
                                        </button>
                                    </a>
                                    @can('orderdeliveries.cancel')
                                        @if($orderdelivery->statusid != "DH"  )
                                            @if ($orderdelivery->statusid != "CA")
                                                <a href="" style="padding-left: 5px;padding-right: 5px;"
                                                   data-target="#modal-cancel-{{$orderdelivery->id}}"
                                                   data-toggle="modal"
                                                   data-placement="top" title="Cancelar embarque">
                                                    <button type="reset"
                                                            class="btn waves-effect waves-light btn-outline-info btn-md"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Cancelar Embarque">
                                                        <img src="{{asset('/ico/adcancelar.png')}}" alt=""
                                                             class="img-16">
                                                        Cancelar
                                                    </button>
                                                </a>
                                                @include('Shipments.OrderDeliveries.cancelorder')
                                            @endif
                                        @endif
                                    @endcan

                                    @can('debugshipment')
                                        @if($orderdelivery->statusid != "DH"  )
                                            @if ($orderdelivery->statusid != "CA")
                                                <a href="" style="padding-left: 5px;padding-right: 5px;"
                                                   data-target="#modal-debug-{{$orderdelivery->id}}" data-toggle="modal"
                                                   data-placement="top" title="Liberar embarque">
                                                    <button type="reset"
                                                            class="btn waves-effect waves-light btn-outline-info btn-md"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Liberar embarque">
                                                        <img src="{{asset('/ico/unitoutput.png')}}" alt=""
                                                             class="img-16">
                                                        Liberar
                                                    </button>
                                                </a>
                                                @include('Shipments.OrderDeliveries.debugshipment')
                                            @endif
                                        @endif
                                    @endcan
                                    @can('orderdeliveries.delete')
                                        @if($orderdelivery->statusid != 'DH')
                                            <a href="" style="padding-left: 5px;padding-right: 5px;"
                                               data-target="#modal-destroy-{{$orderdelivery->id}}" data-toggle="modal"
                                               data-placement="top" title="Eliminar embarque">
                                                <button type="reset"
                                                        class="btn waves-effect waves-light btn-outline-info btn-md"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Eliminar Registro">
                                                    <img src="{{asset('/ico/addestroy1.png')}}" alt="" class="img-16">
                                                    Eliminar
                                                </button>
                                            </a>
                                            @include('Shipments.OrderDeliveries.destroyorder')
                                        @endif
                                    @endcan
                                    <a href="" style="padding-left: 5px;padding-right: 5px;">
                                        <button type="reset"
                                                class="btn waves-effect waves-light btn-outline-info btn-md"
                                                data-toggle="tooltip" data-placement="top" title="Recargar la pagina">
                                            <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16"> Recargar
                                        </button>
                                    </a>
                                    @if (!empty($evidences))
                                        @can('downloadfilesdeliveries')
                                            <a href="{{url('downloadfilesdeliveries/' . $orderdelivery->id)}}">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-outline-info btn-md">
                                                    <img src="{{asset('/ico/files.png')}}" alt="" class="img-16">
                                                    <span style="font-size: 11px;"> Descargar Evidencias</span>
                                                </button>
                                            </a>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                            <hr class="m-t-1">
                            <div class="card-body border-top">
                                <div class="row m-b-0">
                                    <!-- col -->
                                    <div class="col-lg-3 col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="text-trax display-5"><i
                                                        class="mdi mdi-map"></i></span></div>
                                            <div><span class="font-14">Estatus de arribo</span>
                                                <h3 class="font-medium m-b-0">
                                                    @if($orderdelivery->folio ? $orderdelivery->folio->entrydate != null : '')
                                                        @if($orderdelivery->folio->entrydate < $orderdelivery->loadingdate)
                                                            <spam class="font-14 text-green"><i
                                                                    class="mdi mdi-check"></i> En
                                                                tiempo
                                                            </spam>
                                                        @else
                                                            <spam class="font-14 text-red"><i class="mdi mdi-alert"></i>
                                                                Fuera
                                                                de tiempo
                                                            </spam>
                                                        @endif
                                                    @endif

                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- col -->
                                    <!-- col -->
                                    <div class="col-lg-3 col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="text-trax display-5"><i
                                                        class="mdi mdi-map"></i></span></div>
                                            <div><span class="font-14">Estatus de despacho</span>
                                                <h3 class="font-medium m-b-0">
                                                    @if($orderdelivery->folio ? $orderdelivery->folio->outputdate != null : '')
                                                        @if($orderdelivery->folio->outputdate < $orderdelivery->monitor->outputdate)
                                                            <spam class="font-14 text-green"><i
                                                                    class="mdi mdi-check"></i> En
                                                                tiempo
                                                            </spam>
                                                        @else
                                                            <spam class="font-14 text-red"><i class="mdi mdi-alert"></i>
                                                                Fuera
                                                                de tiempo
                                                            </spam>
                                                        @endif
                                                    @endif
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- col -->
                                    <!-- col -->
                                    <div class="col-lg-3 col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="text-trax display-5"><i
                                                        class="mdi mdi-map"></i></span></div>
                                            <div><span class="font-14">Estatus de entrega</span>
                                                <h3 class="font-medium m-b-0">
                                                    @if($orderdelivery->journey ? $orderdelivery->journey->arrivedate != null : '')
                                                        @if($orderdelivery->downloaddate > $orderdelivery->journey->arrivedate)
                                                            <spam class="font-14 text-green"><i
                                                                    class="mdi mdi-check"></i>
                                                                En tiempo
                                                            </spam>
                                                        @else
                                                            <spam class="font-14 text-red"><i class="mdi mdi-alert"></i>
                                                                Fuera de tiempo
                                                            </spam>
                                                        @endif
                                                    @endif
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- col -->
                                    <!-- col -->
                                    <div class="col-lg-3 col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="text-trax display-5"><i
                                                        class="mdi mdi-clock"></i></span></div>
                                            <div><span class="font-14">Tiempo de estadia</span>
                                                @php
                                                    $entryDate = Carbon::parse($orderdelivery->folio ? $orderdelivery->folio->entrydate : null);
                                                    $outputDate = Carbon::parse($orderdelivery->folio ? $orderdelivery->folio->outputdate : null);
                                                    $differenceInMinutes = $entryDate->diffInMinutes($outputDate);
                                                    $hours = intdiv($differenceInMinutes, 60);
                                                    $minutes = $differenceInMinutes % 60;
                                                @endphp
                                                <h3 class="font-medium m-b-0">
                                                    <p class="font-14 text-danger"> {{ $hours }} horas y {{ $minutes }}
                                                        minutos</p>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- col -->
                                </div>
                            </div>
                            <hr class="m-t-1">
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
                                    <div class="card ">
                                        <div class="card-body" style="height: 235px;">
                                            <h6 class="font-weight-light bg-inverse text-white padding-3 ">Datos de la
                                                entrega</h6>
                                            <table class="table table-condensed table-striped table-bordered">
                                                <tr>
                                                    <td>Turno</td>
                                                    <td>{{$orderdelivery->turns->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Cliente</td>
                                                    <td>{{$orderdelivery->customer ? $orderdelivery->customer->name : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Destino</td>
                                                    <td>{{$orderdelivery->customer ? $orderdelivery->customer->city : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Cita de descarga</td>
                                                    <td>{{$orderdelivery->downloaddate}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tiempo de traslado</td>
                                                    <td>{{$orderdelivery->customer ? $orderdelivery->customer->timetransfer : ''}}
                                                        minutos
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Evidencia documental</td>
                                                    <td>
                                                        @if ($orderdelivery->evidencefile != null)
                                                            <a href="{{asset('storage/'. $orderdelivery->evidencefile)}}"
                                                               target="_blank">
                                                                <img src="{{asset('/ico/pdf.png')}}" alt=""
                                                                     class="img-16">
                                                                <span class="text-red"></span></a>
                                                        @else
                                                            <span class="text-danger font-12">Sin evidencia</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Evidencia POD</td>
                                                    <td>
                                                        <a href="{{url('monitorpods/'. $orderdelivery->id)}}"
                                                           target="_blank">
                                                            <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16">
                                                            <span class="text-red"></span></a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card ">
                                        <div class="card-body" style="height: 235px;">
                                            <div class="row">
                                                <div class="col-lg-10">
                                                    <h6 class="font-weight-light bg-inverse text-white padding-3">Datos
                                                        del
                                                        transporte - Folio : {{$orderdelivery->folionumber}} </h6>
                                                </div>
                                                <div class="col-lg-2">
                                                    <a href="" data-target="#modal-kardexfolio" data-toggle="modal"
                                                       data-placement="top">
                                                        <button type="button"
                                                                class="btn waves-effect waves-light btn-outline-secondary btn-sm float-right"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Ver detalle de folio">
                                                            <img src="{{asset('/ico/adbuscar.png')}}" alt=""
                                                                 class="img-16">
                                                        </button>
                                                    </a>
                                                    @include('Shipments.OrderDeliveries.kardexfolio')
                                                </div>
                                            </div>
                                            <table class="table table-condensed table-striped table-bordered">
                                                <tr>
                                                    <td>Linea de transporte</td>
                                                    <td>{{$orderdelivery->carrier ? $orderdelivery->carrier->name : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Placas de caja</td>
                                                    <td>{{$orderdelivery->folio ? $orderdelivery->folio->boxnumber :''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Placas de tracto</td>
                                                    <td>{{$orderdelivery->folio ? $orderdelivery->folio->trucknumber : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Nombre del operador</td>
                                                    <td>{{$orderdelivery->folio ? $orderdelivery->folio->operatorname : ''}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card">
                                        <div class="card-body" style="height: 235px;">
                                            <h6 class="font-weight-light bg-inverse text-white padding-3">Estatus del
                                                proceso</h6>
                                            <table class="table table-condensed table-striped table-bordered">
                                                <tr>
                                                    <td style="width: 30%">Arribo de la unidad</td>
                                                    <td style="width: 10%">
                                                        @if ($orderdelivery->folionumber != null)
                                                            <span>100%</span>
                                                        @else
                                                            <span>0%</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar
                                                    @if ($orderdelivery->folionumber != null)
                                                        bg-success
                                                            @else
                                                        bg-info
                                                            @endif
                                                        progress-bar-striped"
                                                                 aria-valuenow="100" aria-valuemin="0"
                                                                 aria-valuemax="100"
                                                                 style="width: 100%; height:20px;" role="progressbar">
                                                        <span
                                                            class="sr-only">100% Complete (success)
                                                        </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Enrampado</td>
                                                    <td style="width: 10%">

                                                        @if ($orderdelivery->folio ? $orderdelivery->folio->ensnare : null)
                                                            <span>100%</span>
                                                        @else
                                                            <span>0%</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar
                                                         @if ($orderdelivery->folio ? $orderdelivery->folio->ensnare : '')
                                                        bg-success
                                                        @else
                                                        bg-info
                                                        @endif
                                                        progress-bar-striped"
                                                                 aria-valuenow="100" aria-valuemin="0"
                                                                 aria-valuemax="100"
                                                                 style="width: 100%; height:20px;" role="progressbar">
                                                        <span
                                                            class="sr-only">100% Complete (success)
                                                        </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Cargado</td>
                                                    <td style="width: 10%">
                                                        @if ($orderdelivery->header ? $orderdelivery->header->finishdate : null)
                                                            <span>100%</span>
                                                        @else
                                                            <span>0%</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar
                                                        @if ($orderdelivery->header ? $orderdelivery->header->finishdate : '')
                                                        bg-success
                                                        @else
                                                        bg-info
                                                        @endif
                                                        progress-bar-striped"
                                                                 aria-valuenow="100" aria-valuemin="0"
                                                                 aria-valuemax="100"
                                                                 style="width: 100%; height:20px;" role="progressbar">
                                                        <span
                                                            class="sr-only">100% Complete (success)
                                                        </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Facturado</td>
                                                    <td style="width: 10%">
                                                        @if ($orderdelivery->header ? $orderdelivery->header->goodissuedate : '')
                                                            <span>100%</span>
                                                        @else
                                                            <span>0%</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar
                                                        @if ($orderdelivery->header ? $orderdelivery->header->goodissuedate : '')
                                                        bg-success
                                                        @else
                                                        bg-info
                                                        @endif
                                                        progress-bar-striped"
                                                                 aria-valuenow="100" aria-valuemin="0"
                                                                 aria-valuemax="100"
                                                                 style="width: 100%; height:20px;" role="progressbar">
                                                        <span
                                                            class="sr-only">100% Complete (success)
                                                        </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Salida Cedis</td>
                                                    <td style="width: 10%">
                                                        @if ($orderdelivery->folio ? $orderdelivery->folio->outputdate : '')
                                                            <span>100%</span>
                                                        @else
                                                            <span>0%</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar
                                                        @if ($orderdelivery->folio ? $orderdelivery->folio->outputdate : '')
                                                        bg-success
                                                        @else
                                                        bg-info
                                                        @endif
                                                        progress-bar-striped"
                                                                 aria-valuenow="100" aria-valuemin="0"
                                                                 aria-valuemax="100"
                                                                 style="width: 100%; height:20px;" role="progressbar">
                                                        <span
                                                            class="sr-only">100% Complete (success)
                                                        </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-t-0">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card ">
                                        <div class="card-body" style="height: 235px;">
                                            <h6 class="font-weight-light bg-inverse text-white padding-3 "> Tiempos del
                                                proceso</h6>
                                            <table class="table table-condensed table-striped table-bordered">
                                                <tr>
                                                    <td>Cita de carga</td>
                                                    <td>{{$orderdelivery->loadingdate}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Hora de llegada</td>
                                                    <td>{{$orderdelivery->folio ? $orderdelivery->folio->entrydate : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Hora de inicio de carga</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->startdate : null}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Hora de fin de carga</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->finishdate : null}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Hora de inicio de facturación</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->startgoodissuedate : null}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Hora de fin de facturación</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->goodissuedate : null}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card ">
                                        <div class="card-body" style="height: 235px;">
                                            <h6 class="font-weight-light bg-inverse text-white padding-3 "> Datos del
                                                embarque</h6>
                                            <table class="table table-condensed table-striped table-bordered">
                                                <tr>
                                                    <td>Peso total</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->totalweight : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Volumen total</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->totalvolume : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Caja solicitada</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->totalpieces : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Cajas facturadas</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->totalpiecesfac : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Pallets solicitados</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->totalcontainers : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Pallets facturados</td>
                                                    <td>{{$orderdelivery->header ? $orderdelivery->header->totalcontainersfac : ''}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card ">
                                        <div class="card-body" style="height: 235px;">
                                            <h6 class="font-weight-light bg-inverse text-white padding-3 "> Sellos del
                                                embarque</h6>
                                            <table class="table table-condensed table-striped table-bordered">
                                                <tr>
                                                    <td>Sello 01</td>
                                                    <td>{{$orderdelivery->seal ? $orderdelivery->seal->containerseal01 : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Sello 02</td>
                                                    <td>{{$orderdelivery->seal ? $orderdelivery->seal->containerseal02 : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Sello 03</td>
                                                    <td>{{$orderdelivery->seal ? $orderdelivery->seal->containerseal03 : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Entregado a</td>
                                                    <td>{{$orderdelivery->seal ? $orderdelivery->seal->receivername : ''}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card ">
                                        <div class="card-body" style="height: 235px;">
                                            <h6 class="font-weight-light bg-inverse text-white padding-3 "> Datos
                                                operativos</h6>
                                            <table class="table table-condensed table-striped table-bordered">
                                                <tr>
                                                    <td>Supervisor</td>
                                                    <th>{{$orderdelivery->operation ? $orderdelivery->operation->supervisor : ''}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Verificador</td>
                                                    <th>{{$orderdelivery->operation ? $orderdelivery->operation->checker : ''}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Montacarguista</td>
                                                    <th>{{$orderdelivery->operation ? $orderdelivery->operation->forklift : ''}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Analista Operativo</td>
                                                    <th>{{$orderdelivery->header->user ? $orderdelivery->header->user->name : ''}}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <table
                                                class="table table-condensed table-bordered table-striped">
                                                <thead>
                                                <tr class="bg-inverse text-white">
                                                    <td>Evento</td>
                                                    <td>Fecha y hora registrada</td>
                                                    <td>Usuario</td>
                                                </tr>
                                                <tr>
                                                    <td>Lanzamiento de ola :</td>
                                                    <td> {{$orderdelivery->operation ? $orderdelivery->operation->startshipment : ''}}</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Fin de preparación :</td>
                                                    <td> {{$orderdelivery->operation ? $orderdelivery->operation->endchecker : ''}}</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Inicio de carga :</td>
                                                    <td> {{$orderdelivery->operation ? $orderdelivery->operation->startload : ''}}</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Fin de carga :</td>
                                                    <td> {{$orderdelivery->operation ? $orderdelivery->operation->endload : ''}}</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Entrega de documentos :</td>
                                                    <td> {{$orderdelivery->operation ? $orderdelivery->operation->senddocument : ''}}</td>
                                                    <td>{{$orderdelivery->operation->user ? $orderdelivery->operation->user->name : ''}}</td>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card ">
                                        <div class="card-body" style="height: 235px;">
                                            <h6 class="font-weight-light bg-inverse text-white padding-3 "> Datos de
                                                entrega del
                                                transporte</h6>
                                            <table class="table table-condensed table-striped table-bordered">
                                                <tr>
                                                    <td>ETA</td>
                                                    <td>{{$orderdelivery->journey ? $orderdelivery->journey->eta : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Fecha de entrega</td>
                                                    <td>{{$orderdelivery->journey ? $orderdelivery->journey->arrivedate : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Estatus del transporte</td>
                                                    <td>{{$orderdelivery->journey ?  $orderdelivery->journey->auxiliary02 : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tipo de Registros</td>
                                                    <td>{{$orderdelivery->journey  ? $orderdelivery->journey->auxiliary01 : ''}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--                   Conteo de bridgestone--}}
                        <div class="tab-pane " id="view02" role="tabpanel">
                            @if(!empty($conteo))

                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <a href="{{url('printformatcountbridgestone/' . $orderdelivery->id)}}"
                                           target="_blank">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                    style="padding-right: 3px;">
                                                <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16">
                                                Imprimir Conteo Anden
                                            </button>
                                        </a>

                                        <a href="{{url('printformatcountaptbridgestone/' . $orderdelivery->id)}}"
                                           target="_blank">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                    style="padding-right: 3px;">
                                                <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16">
                                                Imprimir Conteo APT
                                            </button>
                                        </a>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Total Izquierdo</label>
                                            <input type="text" class="form-control " value="{{$conteo->totalizquierdo}}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Total Derecho</label>
                                            <input type="text" class="form-control " value="{{$conteo->totalderecho}}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Total</label>
                                            <input type="text" class="form-control " value="{{$conteo->totalfinal}}"
                                                   disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Comlemento</label>
                                            <input type="text" class="form-control " value="{{$conteo->complemento}}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Contador</label>
                                            <input type="text" class="form-control " value="{{$conteo->name}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered  table-condensed mt-5">
                                    <thead>
                                    <th class="text-center font-16">Lado Izquierdo</th>
                                    <th class="text-center font-16">Lado Derecho</th>
                                    </thead>
                                    <tbody>
                                    @foreach($conteos as $conteo)
                                        <td width="50%">
                                            <!-- Códigos Izquierdo -->
                                            <div class="mb-3">
                                                <h6 class="font-weight-bold">Códigos Izquierdo</h6>
                                                <table class="table table-bordered mt-1">
                                                    <tr>
                                                        @if(isset($conteo->codigoIzq))
                                                            @foreach($conteo->codigoIzq as $key => $codigo)
                                                                <td style="width: 80px; height: 30px; border: 2px solid; font-size: 12px; text-align: center; background-color: #f8f9fa;">
                                                                    <strong>{{ $key }}</strong><br>{{ $codigo }}
                                                                </td>
                                                                @if(($loop->index + 1) % 5 == 0)
                                                    </tr>
                                                    <tr>
                                                        @endif
                                                        @endforeach
                                                        @endif
                                                    </tr>
                                                </table>
                                            </div>

                                            <!-- Canal Izquierdo -->
                                            <div class="mb-3">
                                                <h6 class="font-weight-bold">Canal Izquierdo</h6>
                                                <table class="table table-bordered mt-1">
                                                    <tr>
                                                        @if(isset($conteo->canalizquierdo))
                                                            @foreach($conteo->canalizquierdo as $key => $canal)
                                                                <td style="width: 80px; height: 30px; border: 2px solid; font-size: 12px; text-align: center; background-color: #e3f2fd;">
                                                                    <strong>{{ $key }}</strong><br>{{ $canal }}
                                                                </td>
                                                                @if(($loop->index + 1) % 5 == 0)
                                                    </tr>
                                                    <tr>
                                                        @endif
                                                        @endforeach
                                                        @endif
                                                    </tr>
                                                </table>
                                            </div>

                                            <!-- Detalle Izquierdo -->
                                            <div class="mb-3">
                                                <h6 class="font-weight-bold">Detalle Izquierdo</h6>
                                                <table class="table table-bordered mt-1">
                                                    @if(isset($conteo->detalleizquierdo))
                                                        @foreach(array_chunk((array)$conteo->detalleizquierdo, 10) as $fila)
                                                            <tr>
                                                                @foreach($fila as $key => $item)
                                                                    <td style="width: 20px; height: 20px; border: 2px solid; font-size: 15px; text-align: center;"
                                                                        class="{{ $item !== '0' ? 'bg-inverse text-white font-16' : 'bg-light' }}">
                                                                        {{ $item }}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </div>

                                        </td>
                                        <td width="50%">

                                        </td>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        {{--                        Fotos--}}
                        <div class="tab-pane  " id="view03" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">Evidencia de carga</div>
                                        <div class="card-body">
                                            @if (!empty($evidences))
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row el-element-overlay">
                                                                    @foreach($evidences as $photo)
                                                                        @if ($photo->auxiliary01 == 'SH')
                                                                            <div class="col-lg-2 col-md-6">
                                                                                <div class="card">
                                                                                    <div class="el-card-item">
                                                                                        <div
                                                                                            class="el-card-avatar el-overlay-1">
                                                                                            <img
                                                                                                src="{{asset('storage/' . $photo->path)}}"
                                                                                                alt="user"
                                                                                                style="max-height: 200px"/>
                                                                                            <div class="el-overlay">
                                                                                                <ul class="list-style-none el-info">
                                                                                                    <li class="el-item">
                                                                                                        <a href="{{url('downloadphoto/' . $photo->id)}}"
                                                                                                           data-togggle="tooltip"
                                                                                                           title="Descargar"
                                                                                                           class="btn default btn-outline el-link">
                                                                                                            <i class="mdi mdi-download"></i>
                                                                                                        </a>
                                                                                                    </li>
                                                                                                    @can('destroyphotoshipment')
                                                                                                        <li class="el-item">
                                                                                                            <a href="{{url('destroyphoto/' . $photo->id)}}"
                                                                                                               class="btn default btn-outline el-link">
                                                                                                                <i class="mdi mdi-delete"></i>
                                                                                                            </a>
                                                                                                        </li>
                                                                                                    @endcan
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="el-card-content">
                                                                                            <h4 class="m-b-0 font-10 font-weight-light"> {{$photo->user->name}} </h4>
                                                                                            <span
                                                                                                class="text-muted">{{$photo->created_at}}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--                        Comentarios--}}
                        <div class="tab-pane  " id="view04" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-lg-6">
                                                <div class="card-input">
                                                    <div class="comment-widgets scrollable" style="height:560px;">
                                                        @foreach($comments as $comment)
                                                            <div class="d-flex flex-row comment-row m-t-0">
                                                                <div><img
                                                                        src="{{asset('ico/notepad_bloc_notes_15549.png')}}"
                                                                        alt="user" width="50" class="rounded-circle">
                                                                </div>
                                                                <div class="comment-text w-100">
                                                                    <h6 class="font-medium">{{$comment->user ? $comment->user->name : ''}}</h6>
                                                                    <span
                                                                        class="m-b-15 d-block">{{$comment->comment}}</span>
                                                                    <div class="comment-footer">
                                                                        <span
                                                                            class="text-muted float-right">{{$comment->created_at}}</span>
                                                                        <span
                                                                            class="label label-rounded label-primary">Creado</span>
                                                                        <span class="action-icons">
                                                                    </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".table-list").DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "100px",
                "dom": '<"top"B>lrt<"bottom"p><"clear"i>',
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                buttons: [
                    {
                        "extend": "copy",
                        "text": "<i class='fa fa-copy bigger-110 text-blue'></i> <span  style='font-size: 10px'>Copiar</span>",
                        "className": "btn btn-white btn-sm btn-bold"
                    },
                    {
                        "extend": "excel",
                        "text": "<i class='fa fa-file-excel-o text-green'></i> <span  style='font-size: 10px'> Excel</span>",
                        "className": "btn btn-white btn-sm btn-bold"
                    }
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            });
        })
    </script>
@endsection
