@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Número de embarque: <span style="font-size: 16px;color:red;font-weight: 700;text-align: left;margin-right: 100px"> {{ $orderDelivery->shipmentid}}</span>
                    Cliente: <span
                        style="font-size: 13px;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->customer ? $orderDelivery->customer->name : ''}}</span>
                    Fecha de trabajo: <span
                        style="font-size: 13px;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->workday}}</span>
                    Turno: <span
                        style="font-size: 13px;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->turns ? $orderDelivery->turns->name : ''}}</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('monitoranalistatrafico/' . $orderDelivery->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{ url()->previous() }}">
                                    @include('buttons.back')
                                </a>

                                @can('orderdeliveries.show')
                                    <a href="{{url('orderdeliveries/' . $orderDelivery->id)}}" target="_blank">
                                        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16">
                                            <span style="font-size: 0.675rem "> Ver Kardex</span>
                                        </button>
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <hr class="mt-3"></hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-input">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="containerseal01">Número de sello 01 <span
                                                                        style="color: red">*</span></label>
                                                                <input type="text" class="form-control"
                                                                       id="containerseal01"
                                                                       name="containerseal01"
                                                                       value="{{$orderDelivery->seal->containerseal01}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="containerseal02">Número de sello 02 <span
                                                                        style="color: red">*</span></label>
                                                                <input type="text" class="form-control"
                                                                       id="containerseal02"
                                                                       name="containerseal02"
                                                                       value="{{$orderDelivery->seal->containerseal02}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="containerseal03">Número de sello 03 <span
                                                                        style="color: red">*</span></label>
                                                                <input type="text" class="form-control"
                                                                       id="containerseal03"
                                                                       name="containerseal03"
                                                                       value="{{$orderDelivery->seal->containerseal03}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="receivername">Entregado a <span
                                                                        style="color: red">*</span></label>
                                                                <input type="text" class="form-control"
                                                                       id="receivername"
                                                                       name="receivername"
                                                                       value="{{$orderDelivery->seal->receivername}}"
                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-input">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="totalweight">Peso total <span
                                                                                style="color: red">*</span></label>
                                                                        <input type="text" class="form-control" readonly
                                                                               id="totalweight"
                                                                               name="totalweight"
                                                                               value="{{$orderDelivery->header->totalweight}}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="totalvolume">Volumen total <span
                                                                                style="color: red">*</span></label>
                                                                        <input type="text" class="form-control numeric"
                                                                               id="totalvolume" name="totalvolume"
                                                                               value="{{$orderDelivery->header->totalvolume}}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="totalpieces">Cajas solicitadas <span
                                                                                style="color: red">*</span></label>
                                                                        <input type="text" readonly
                                                                               class="form-control  numeric"
                                                                               id="totalpieces" name="totalpieces"
                                                                               value="{{$orderDelivery->header->totalpieces}}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="totalpiecesfac">Cajas facturadas
                                                                            <span
                                                                                style="color: red">*</span></label>
                                                                        <input type="text" class="form-control numeric"
                                                                               id="totalpiecesfac" name="totalpiecesfac"
                                                                               value="{{$orderDelivery->header->totalpiecesfac}}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="totalcontainers">Pallets solicitados
                                                                            <span
                                                                                style="color: red">*</span></label>
                                                                        <input type="text" readonly
                                                                               class="form-control numeric"
                                                                               id="totalcontainers"
                                                                               name="totalcontainers"
                                                                               value="{{$orderDelivery->header->totalcontainers}}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="totalcontainersfac">Pallets
                                                                            facturados <span
                                                                                style="color: red">*</span></label>
                                                                        <input type="text" class="form-control numeric"
                                                                               id="totalcontainersfac"
                                                                               name="totalcontainersfac"
                                                                               value="{{$orderDelivery->header->totalcontainersfac}}"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <div class="form-group">
                                                        <label for="comment">Comentario de facturación</label>
                                                        <textarea class="form-control" rows="5" id="comment"
                                                                  name="comment"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-input">
                                    <div class="comment-widgets scrollable" style="height:560px;">
                                        @foreach($comments as $comment)
                                            <div class="d-flex flex-row comment-row m-t-0">
                                                <div><img src="{{asset('ico/notepad_bloc_notes_15549.png')}}" alt="user"
                                                          width="50"
                                                          class="rounded-circle"></div>
                                                <div class="comment-text w-100">
                                                    <h6 class="font-medium">{{$comment->user ? $comment->user->name : ''}}</h6>
                                                    <span class="m-b-15 d-block">{{$comment->comment}}</span>
                                                    <div class="comment-footer">
                                                        <span
                                                            class="text-muted float-right">{{$comment->created_at}}</span>
                                                        <span
                                                            class="label label-rounded label-primary">Creado</span>
                                                        <span
                                                            class="action-icons">
                                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-input">
                                <table class="table table-condensed  table-bordered">
                                    <tr>
                                        <th style="font-size: 11px;width: 50%">Inicio de Facturación</th>
                                        <th>
                                            @if ($orderDelivery->operation->senddocument != '')
                                                @if ($orderDelivery->header->startgoodissuedate == '')
                                                    <a href=""
                                                       data-target="#modal-addstartgoodissuedate-{{$orderDelivery->id}}"
                                                       data-toggle="modal">
                                                        <button
                                                            class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                            <img src="{{asset('/ico/priority.png')}}"
                                                                 alt="" class="img-16 ml-2 mr-2">
                                                            <span
                                                                style="font-size: 0.675rem "> Iniciar</span>
                                                        </button>
                                                    </a>
                                                    @include('Shipments.OrderMonitor.AnalystTraffic.partials.startgoodissuedate')
                                                @else
                                                    <span
                                                        style="font-size: 11px;">{{$orderDelivery->header->startgoodissuedate}}</span>
                                                @endif
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 11px">Fin de Facturación
                                        </th>
                                        <th>
                                            @if ($orderDelivery->header->startgoodissuedate != '')
                                                @if ($orderDelivery->header->goodissuedate  == '')
                                                    <a href=""
                                                       data-target="#modal-goodissuedate-{{$orderDelivery->id}}"
                                                       data-toggle="modal">
                                                        <button
                                                            class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                            <img src="{{asset('/ico/priority.png')}}"
                                                                 alt="" class="img-16 ml-2 mr-2">
                                                            <span
                                                                style="font-size: 0.675rem "> Iniciar</span>
                                                        </button>
                                                    </a>
                                                    @include('Shipments.OrderMonitor.AnalystTraffic.partials.goodissuedate')
                                                @else
                                                    <span
                                                        style="font-size: 11px "> {{$orderDelivery->header->goodissuedate}} </span>
                                                @endif
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 11px">Solicitud de Carta Porte
                                        </th>
                                        <th>
                                            @if($orderDelivery->header->startgoodissuedate != '')
                                                @if ($orderDelivery->operation->requestletter == '')
                                                    <a href=""
                                                       data-target="#modal-requestletter-{{$orderDelivery->id}}"
                                                       data-toggle="modal">
                                                        <button
                                                            class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                            <img src="{{asset('/ico/priority.png')}}"
                                                                 alt="" class="img-16 ml-2 mr-2">
                                                            <span
                                                                style="font-size: 0.675rem "> Iniciar</span>
                                                        </button>
                                                    </a>
                                                    @include('Shipments.OrderMonitor.MonitorOperation.Events.requestletter')
                                                @else
                                                    <span
                                                        style="font-size: 11px;">{{$orderDelivery->operation->requestletter}}</span>
                                                @endif
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 11px">Receipción de Carta Porte
                                        </th>
                                        <th>
                                            @if ($orderDelivery->operation->requestletter != null)
                                                @if ($orderDelivery->operation->responseletter == '')
                                                    <a href=""
                                                       data-target="#modal-responseletter-{{$orderDelivery->id}}"
                                                       data-toggle="modal">
                                                        <button
                                                            class="btn waves-effect waves-light btn-outline-info btn-sm btn-block">
                                                            <img src="{{asset('/ico/priority.png')}}"
                                                                 alt="" class="img-16 ml-2 mr-2">
                                                            <span
                                                                style="font-size: 0.675rem "> Iniciar</span>
                                                        </button>
                                                    </a>
                                                    @include('Shipments.OrderMonitor.MonitorOperation.Events.responseletter')
                                                @else
                                                    <span style="font-size: 11px;">{{$orderDelivery->operation->responseletter}}</span>
                                                @endif
                                            @endif
                                        </th>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
    <script>
        var quill = new Quill('#comment', {
            theme: 'snow'
        });
    </script>
@endsection

