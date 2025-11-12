@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Kardex de unidad
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{route('tracereceiptunit.index')}}">
                                @include('buttons.cancel')
                            </a>
                            <a href="{{url('generateinvoicekardex/' . $receiptunit->id)}}" >
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button"> Generar Kardex</span>
                                </button>
                            </a>
                            @can('receiptunits.princhecklistbrid')
                                <a href="{{url('printchecklistbrind/' . $receiptunit->id)}}" >
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16 ml-1">
                                        <span class="text-button"> Generar Check List Bridgestone</span>
                                    </button>
                                </a>
                            @endcan
                            @can('receiptunits.calidadbridgestone')
                                <a href="{{url('printqualitybridge/' . $receiptunit->id)}}" >
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/papeleta.png')}}" alt="" class="img-16 ml-1">
                                        <span class="text-button"> Generar Check List Calidad</span>
                                    </button>
                                </a>
                            @endcan
                            @if($receiptunit->statusid == 'TR')
                                @can('receiptunits.realodunit')
                                    <a onclick="reloadunit( {!! $receiptunit->id!!} )">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/go-back256_24856.png')}}" alt="" class="img-16">
                                            <span class="text-button"> Revivir Folio</span>
                                        </button>
                                    </a>
                                @endcan
                            @endif
                            @can('receiptunits.downloadfiles')
                                <a href="{{url('downloadfilesunit/' . $receiptunit->id)}}" >
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/galeria-de-fotos.png')}}" alt=""
                                             class="img-16 ml-1">
                                        <span class="text-button">Descargar Evidencias</span>
                                    </button>
                                </a>
                            @endcan
                            @can('receiptunits.ticket')
                                <a href="{{url('downloadticket/' . $receiptunit->id)}}" >
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/papeleta.png')}}" alt=""
                                             class="img-16 ml-1">
                                        <span class="text-button">Descargar Ticket de bascula</span>
                                    </button>
                                </a>
                            @endcan
                            @can('generateinvoicetransport')
                                <a href="{{url('generateinvoicetransport/' . $receiptunit->id)}}" >
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/papeleta.png')}}" alt=""
                                             class="img-16 ml-1">
                                        <span class="text-button">Descargar Folio</span>
                                    </button>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <hr class="mt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-home"></i></span> <span class="hidden-xs-down">Detalle de movimientos</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span class="hidden-xs-down">Check list y Evidencia de Entrada</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span class="hidden-xs-down">Evidencia de Proceso</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view04"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span class="hidden-xs-down">Check list y Evidencia de Calidad</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view05"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span class="hidden-xs-down">Check list y Evidencia de Salida</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view07"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span
                                                class="hidden-xs-down">Sellos</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view08"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span
                                                class="hidden-xs-down">Pesos de unidad</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view09"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span
                                                class="hidden-xs-down">Sellos LOTO</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view06"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span
                                                class="hidden-xs-down">Comentarios</span></a>
                                </li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view10"
                                                        role="tab"><span class="hidden-sm-up"><i
                                                    class="ti-list"></i></span> <span
                                                class="hidden-xs-down">Firmas</span></a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="view01" role="tabpanel">
                                    <div class="p-20">
                                        @include('Yards.KardexReceiptUnit.tabletrace')
                                        <hr class="mt-3">
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Timeline de Eventos </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="timeline-horizontal">
                                                            <ul class="list-unstyled d-flex flex-wrap justify-content-between align-items-start position-relative">
                                                                @foreach($traces as $index => $trace)
                                                                    <li class="timeline-item text-center" style="min-width: 150px; max-width: 200px; position: relative;">
                                                                        <div class="timeline-badge bg-info rounded-circle mx-auto" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                                                            <i class="mdi mdi-check text-white"></i>
                                                                        </div>
                                                                        <div class="timeline-panel mt-2">
                                                                            <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">{{ $trace->event ? $trace->event->name : 'Sin evento' }}</h6>
                                                                            <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($trace->created_at)->format('d/m/Y') }}</p>
                                                                            <p class="text-muted" style="font-size: 9px;">{{ \Carbon\Carbon::parse($trace->created_at)->format('H:i') }}</p>
                                                                        </div>
                                                                        @if($index < count($traces) - 1)
                                                                            @php
                                                                                $currentTime = \Carbon\Carbon::parse($trace->created_at);
                                                                                $nextTime = \Carbon\Carbon::parse($traces[$index + 1]->created_at);
                                                                                $diff = $currentTime->diff($nextTime);
                                                                                $hours = $diff->h + ($diff->days * 24);
                                                                                $minutes = $diff->i;
                                                                            @endphp
                                                                            <div class="timeline-duration" style="position: absolute; top: -20px; left: 100%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                                                                {{ $hours }}h {{ $minutes }}m
                                                                            </div>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                            @if(count($traces) > 0)
                                                                @php
                                                                    $firstTime = \Carbon\Carbon::parse($traces->first()->created_at);
                                                                    $lastTime = \Carbon\Carbon::parse($traces->last()->created_at);
                                                                    $totalDiff = $firstTime->diff($lastTime);
                                                                    $totalHours = $totalDiff->h + ($totalDiff->days * 24);
                                                                    $totalMinutes = $totalDiff->i;
                                                                @endphp
                                                                <div class="row mt-4">
                                                                    <div class="col-12 d-flex justify-content-center">
                                                                        <div class="card bg-cyan text-white" style="max-width: 300px;">
                                                                            <div class="card-body text-center py-3">
                                                                                <h5 class="card-title mb-1" style="font-size: 13px; font-weight: bold;">Tiempo Total</h5>
                                                                                <p class="card-text mb-0" style="font-size: 18px; font-weight: bold;">{{ $totalHours }}h {{ $totalMinutes }}m</p>
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
                                    </div>
                                </div>
                                <div class="tab-pane" id="view02" role="tabpanel">
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <table width="100%"
                                                   class="table table-condensed table-bordered ">
                                                <thead>
                                                <tr>
                                                    <th>Concepto</th>
                                                    <th>Estatus</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($checklisttransports as $check)
                                                    @if($check->type == 'IN')
                                                        <tr>
                                                            <td>{{$check->checklistdetail ? $check->checklistdetail->description : ''}}</td>
                                                            <td class="bg-{{$check->verified == 'NO' ? 'red text-white' : ''}}">{{$check->verified}}</td>
                                                        </tr>
                                                @endif
                                                @endforeach
                                            </table>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-header"></div>
                                                <div class="card-body">
                                                    @if (!empty($evidences))
                                                        <div class="row el-element-overlay">
                                                            @foreach($evidences as $photo)
                                                                @if ($photo->auxiliary01 == 'IN')
                                                                    <div class="col-lg-3 col-md-6">
                                                                        <div class="card">
                                                                            <div class="el-card-item">
                                                                                <div
                                                                                        class="el-card-avatar el-overlay-1">
                                                                                    <img
                                                                                            src="{{asset('storage/' . $photo->path)}}"
                                                                                            alt="user"
                                                                                            style="height: 300px;max-height: 400px"/>
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
                                                                                            @can('destroyphotocheckin')
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
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="view03" role="tabpanel">
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header"></div>
                                                <div class="card-body">
                                                    @if (!empty($evidences))
                                                        <div class="row el-element-overlay">
                                                            @foreach($evidences as $photo)
                                                                @if ($photo->auxiliary01 == 'IP')
                                                                    <div class="col-lg-3 col-md-6">
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                {{$photo->tipo}}
                                                                            </div>
                                                                            <div class="el-card-item">
                                                                                <div
                                                                                        class="el-card-avatar el-overlay-1">
                                                                                    <img
                                                                                            src="{{asset('storage/' . $photo->path)}}"
                                                                                            alt="user"
                                                                                            style="height: 300px;max-height: 400px"/>
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
                                                                                            @can('destroyphotocheckin')
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
                                                    @endif
                                                    @if (!empty($evidencesTiros))
                                                        <div class="row el-element-overlay">
                                                            @foreach($evidencesTiros as $photo)
                                                                <div class="col-lg-3 col-md-6">
                                                                    <div class="card">
                                                                        <div class="card-header">
                                                                            {{$photo->description}}
                                                                        </div>
                                                                        <div class="el-card-item">
                                                                            <div
                                                                                    class="el-card-avatar el-overlay-1">
                                                                                <img
                                                                                        src="{{asset('storage/' . $photo->path)}}"
                                                                                        alt="user"
                                                                                        style="height: 300px;max-height: 400px"/>
                                                                                <div class="el-overlay">
                                                                                    <ul class="list-style-none el-info">
                                                                                        <li class="el-item">
                                                                                            <a href="{{url('downloadPhotoTireProcess/' . $photo->id)}}"
                                                                                               data-togggle="tooltip"
                                                                                               title="Descargar"
                                                                                               class="btn default btn-outline el-link">
                                                                                                <i class="mdi mdi-download"></i>
                                                                                            </a>
                                                                                        </li>
                                                                                        @can('destroyphotocheckin')
                                                                                            <li class="el-item">
                                                                                                <a href="{{url('destroyPhotoTireProcess/' . $photo->id)}}"
                                                                                                   class="btn default btn-outline el-link">
                                                                                                    <i class="mdi mdi-delete"></i>
                                                                                                </a>
                                                                                            </li>
                                                                                        @endcan
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                            <div class="el-card-content">
                                                                                <h4 class="m-b-0 font-10 font-weight-light"> {{$photo->user ? $photo->user->name : ''}} </h4>
                                                                                <span
                                                                                        class="text-muted">{{$photo->updated_at}}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-20" id="view04" role="tabpanel">
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <table width="100%"
                                                   class="table table-condensed table-bordered ">
                                                <thead>
                                                <tr>
                                                    <th>Concepto</th>
                                                    <th>Estatus</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($checklisttransports as $check)
                                                    @if($check->type == 'QI')
                                                        <tr>
                                                            <td>{{$check->checklistdetail ? $check->checklistdetail->description : ''}}</td>
                                                            <td class="bg-{{$check->verified == 'NO' ? 'red text-white' : ''}}">{{$check->verified}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-header"></div>
                                                <div class="card-body">
                                                    @if (!empty($evidences))
                                                        <div class="row el-element-overlay">
                                                            @foreach($evidences as $photo)
                                                                @if ($photo->auxiliary01 == 'QI')
                                                                    <div class="col-lg-3 col-md-6">
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                {{$photo->auxiliary05}}
                                                                            </div>
                                                                            <div class="el-card-item">
                                                                                <div
                                                                                        class="el-card-avatar el-overlay-1">
                                                                                    <img
                                                                                            src="{{asset('storage/' . $photo->path)}}"
                                                                                            alt="user"
                                                                                            style="height: 300px;max-height: 400px"/>
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
                                                                                            @can('destroyphotocheckin')
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
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="view05" role="tabpanel">
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <table width="100%"
                                                   class="table table-condensed table-bordered ">
                                                <thead>
                                                <tr>
                                                    <th>Concepto</th>
                                                    <th>Estatus</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($checklisttransports as $check)
                                                    @if($check->type == 'OUT')
                                                        <tr>
                                                            <td>{{$check->checklistdetail ? $check->checklistdetail->description : ''}}</td>
                                                            <td class="bg-{{$check->verified == 'NO' ? 'red text-white' : ''}}">{{$check->verified}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-header"></div>
                                                <div class="card-body">
                                                    @if (!empty($evidences))
                                                        <div class="row el-element-overlay">
                                                            @foreach($evidences as $photo)
                                                                @if ($photo->auxiliary01 == 'OUT')
                                                                    <div class="col-lg-3 col-md-6">
                                                                        <div class="card">
                                                                            <div class="el-card-item">
                                                                                <div
                                                                                        class="el-card-avatar el-overlay-1">
                                                                                    <img
                                                                                            src="{{asset('storage/' . $photo->path)}}"
                                                                                            alt="user"
                                                                                            style="height: 300px;max-height: 400px"/>
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
                                                                                            @can('destroyphotocheckin')
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
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-20" id="view06" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-12" style="height: 500px;overflow-y: scroll;">
                                            <div class="card">
                                                <div class="card-header">
                                                    Historico
                                                </div>
                                                <div class="card-body">
                                                    @foreach($comments as $comment)
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="p-2"><img
                                                                            src="{{asset('/ico/edilogs.png')}}"
                                                                            alt="user" width="50"
                                                                            class="rounded-circle">{{$comment->user->name}}
                                                                </div>
                                                                <div class="comment-text active w-100">
                                                                    <h6 class="font-medium"></h6>
                                                                    <span
                                                                            class="m-b-15 d-block">{{$comment->comment}} </span>
                                                                    <div class="comment-footer ">
                                                                <span
                                                                        class="text-muted float-right">{{$comment->created_at}}</span>
                                                                        <span
                                                                                class="label label-success label-rounded">Approved</span>
                                                                        <span class="action-icons active">
                                                                     </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="view07" role="tabpanel">
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <table width="100%"
                                                                   class="table table-condensed table-bordered  table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th>Tipo</th>
                                                                    <th>Sello 01</th>
                                                                    <th>Sello 02</th>
                                                                    <th>Sello 03</th>
                                                                    <th>Fecha de creación</th>
                                                                    <th>Usuario</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($seals as $seal)
                                                                    <tr>
                                                                        <td>{{$seal->value01}}</td>
                                                                        <td>{{$seal->seal01}}</td>
                                                                        <td>{{$seal->seal02}}</td>
                                                                        <td>{{$seal->seal03}}</td>
                                                                        <td>{{$seal->created_at}}</td>
                                                                        <td>{{$seal->user->name}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-header"></div>
                                                <div class="card-body">
                                                    @if (!empty($evidences))
                                                        <div class="row el-element-overlay">
                                                            @foreach($evidences as $photo)
                                                                @if ($photo->auxiliary01 == 'SE')
                                                                    <div class="col-lg-3 col-md-6">
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                {{$photo->auxiliary05}}
                                                                            </div>
                                                                            <div class="el-card-item">
                                                                                <div
                                                                                        class="el-card-avatar el-overlay-1">
                                                                                    <img
                                                                                            src="{{asset('storage/' . $photo->path)}}"
                                                                                            alt="user"
                                                                                            style="height: 300px;max-height: 400px"/>
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
                                                                                            @can('destroyphotocheckin')
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
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="view08" role="tabpanel">
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <table width="100%"
                                                           class="table table-condensed table-bordered  table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Folio</th>
                                                            <th>Número de ticket</th>
                                                            <th>Peso de entrada kilogramos</th>
                                                            <th>Peso de entrada libras</th>
                                                            <th>Usuario de entrada</th>
                                                            <th>Fecha de entrada</th>
                                                            <th>Peso de salida kilogramos</th>
                                                            <th>Peso de salida libras</th>
                                                            <th>Peso de material kilogramos</th>
                                                            <th>Peso de material libras</th>
                                                            <th>Peso de SAP libras</th>
                                                            <th>Usuario de salida</th>
                                                            <th>Fecha de salida</th>
                                                            <th>% de diferencia</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if(!empty($pesos))
                                                            @foreach($pesos as $peso)
                                                                <tr>
                                                                    <td>{{$peso->folionumber}}</td>
                                                                    <td>{{$peso->ticket}}</td>
                                                                    <td>{{$peso->weightin}} Kg</td>
                                                                    <td>{{ round($peso->weightin * 2.20462) }} lbs</td>
                                                                    <td>{{$peso->usercreate ? $peso->usercreate->name : ''}}</td>
                                                                    <td>{{$peso->createddate}}</td>
                                                                    <td>{{$peso->weightout}} Kg</td>
                                                                    <td>{{ round($peso->weightout * 2.20462)}} lbs</td>
                                                                    <td>{{$peso->weightout - $peso->weightin}} Kg</td>
                                                                    <td>{{ round( ($peso->weightout - $peso->weightin) * 2.20462)}} lbs </td>
                                                                    <td>{{$peso->weightoutsap}} lbs</td> {{-- Peso de SAP libras--}}
                                                                    <td>{{$peso->userupdate ? $peso->userupdate->name : ''}}</td>
                                                                    <td>{{$peso->updateddate}}</td>
                                                                    <td>{{$peso->value01}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="view09" role="tabpanel">
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <table width="100%"
                                                           class="table table-condensed table-bordered table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Folio</th>
                                                            <th>Número de LOTO</th>
                                                            <th>Evento</th>
                                                            <th>Usuario</th>
                                                            <th>Fecha de asignación</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($lotos as $loto)
                                                            <tr>
                                                                <td>{{$loto->id}}</td>
                                                                <td>{{$loto->loto ? $loto->loto->description : ''}}</td>
                                                                <td>{{$loto->event ? $loto->event->name : ''}}</td>
                                                                <td>{{$loto->user ? $loto->user->name : ''}}</td>
                                                                <td>{{$loto->created_at}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="view10" role="tabpanel">
                                    <div class="row mt-4">
                                        <div class="col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <table width="100%"
                                                           class="table table-condensed table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Firma de vigilante de entrada</th>
                                                            <th>Frima de operador de entrada</th>
                                                            <th>Firma de vigilante de salida</th>
                                                            <th>Firma de operador de salida</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            @if($receiptunit->key)
                                                                <td style="height: 150px">
                                                                    <img
                                                                            src="{{asset('storage/' . $receiptunit->key->keyguard01)}}"
                                                                            alt="Firma"
                                                                            style="height: 100px;max-height: 200px"/>
                                                                </td>
                                                                <td>
                                                                    <img
                                                                            src="{{asset('storage/' . $receiptunit->key->keyoperator01 )}}"
                                                                            alt="Firma"
                                                                            style="height: 100px;max-height: 200px"/>
                                                                </td>
                                                                <td>
                                                                    <img
                                                                            src="{{asset('storage/' . $receiptunit->key->keyguard03 )}}"
                                                                            alt="Firma"
                                                                            style="height: 100px;max-height: 200px"/>
                                                                </td>
                                                                <td>
                                                                    <img
                                                                            src="{{asset('storage/' . $receiptunit->key->keyoperator03)}}"
                                                                            alt="Firma"
                                                                            style="height: 100px;max-height: 200px"/>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Kardex de Unidad
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
    <style>
        .timeline-horizontal {
            overflow-x: auto;
            padding: 20px 0;
        }
        .timeline-horizontal ul {
            position: relative;
            margin: 0;
            padding: 0;
        }
        .timeline-horizontal ul::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 0;
        }
        .timeline-item {
            flex: 0 0 auto;
        }
        .timeline-badge {
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
@endsection



@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    {{--    <script>--}}
    {{--        let oTable = $("#table-detailkardex").DataTable({--}}
    {{--            bDestroy: true,--}}
    {{--            "paging": true,--}}
    {{--            "searching": true,--}}
    {{--            "ordering": true,--}}
    {{--            "sScrollY": "350px",--}}
    {{--            "sScrollX": true,--}}
    {{--            "processing": true,--}}
    {{--            "responsive": true,--}}
    {{--            "colReorder": true,--}}
    {{--            "stateSave": true,--}}
    {{--            "stateDuration": 0,--}}
    {{--            "columnDefs": [--}}
    {{--                {"width": "100px", "targets": 0},--}}
    {{--                {"width": "280px", "targets": 1},--}}
    {{--                {"width": "120px", "targets": 2},--}}
    {{--                {"width": "150px", "targets": 3},--}}
    {{--                {"width": "150px", "targets": 4},--}}
    {{--                {"width": "150px", "targets": 5},--}}
    {{--                {"width": "250px", "targets": 6},--}}
    {{--                {"width": "100px", "targets": 7},--}}
    {{--                {"width": "100px", "targets": 8},--}}
    {{--                {"width": "120px", "targets": 9},--}}
    {{--                {"width": "120px", "targets": 10},--}}
    {{--                {"width": "280px", "targets": 11},--}}
    {{--                {"width": "120px", "targets": 12},--}}
    {{--                {"width": "280px", "targets": 13},--}}
    {{--            ],--}}
    {{--            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',--}}
    {{--            "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],--}}
    {{--            buttons: [--}}
    {{--                {--}}
    {{--                    "extend": "colvis",--}}
    {{--                    "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",--}}
    {{--                    "className": "btn btn-outline-info btn-md  btn-bold",--}}
    {{--                    columns: ':not(:first):not(:last)'--}}
    {{--                },--}}
    {{--                {--}}
    {{--                    "extend": "csv",--}}
    {{--                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 11px;'> Csv</span>",--}}
    {{--                    "className": "btn btn-outline-info btn-md  btn-bold",--}}
    {{--                },--}}
    {{--                {--}}
    {{--                    "extend": "excel",--}}
    {{--                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 11px;'> Excel</span>",--}}
    {{--                    "className": "btn btn-outline-info btn-md  btn-bold",--}}
    {{--                }--}}
    {{--            ],--}}
    {{--            language: {--}}
    {{--                "decimal": "",--}}
    {{--                "emptyTable": "No hay información",--}}
    {{--                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",--}}
    {{--                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",--}}
    {{--                "infoFiltered": "(Filtrado de _MAX_ total entradas)",--}}
    {{--                "infoPostFix": "",--}}
    {{--                "thousands": ",",--}}
    {{--                "lengthMenu": "Mostrar _MENU_ Entradas",--}}
    {{--                "loadingRecords": "Cargando...",--}}
    {{--                "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Loading...</span> ',--}}
    {{--                "search": "Buscar:",--}}
    {{--                "zeroRecords": "Sin resultados encontrados",--}}
    {{--                "paginate": {--}}
    {{--                    "first": "Primero",--}}
    {{--                    "last": "Ultimo",--}}
    {{--                    "next": "Siguiente",--}}
    {{--                    "previous": "Anterior"--}}
    {{--                }--}}
    {{--            },--}}
    {{--        });--}}
    {{--    </script>--}}

    <script>
        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
                $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
            });
            $('table.table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "250px",
                "sScrollX": true,
                "processing": true,
                "serverSide": false,
                "responsive": true,
                "colReorder": true,
                "stateSave": true,
                "stateDuration": 0,
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
                buttons: [
                    {
                        "extend": "csv",
                        "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 11px;'> Csv</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                    },
                    {
                        "extend": "excel",
                        "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 11px;'> Excel</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
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
        });
    </script>
    <script>
        function reloadunit($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Revivir folio de unidad!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, reingresar el folio a la existencia!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        data: {
                            id: $id,
                        },
                        url: '{!! route('reloadunit') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Evento realizado con exito`,
                                width: 350,
                            })
                            setTimeout(function () {
                                window.location.reload()
                            }, 2000)
                        },
                    })

                }
            })
        }
    </script>
@endsection
