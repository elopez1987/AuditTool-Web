@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Número de embarque: <span style="font-size: 16px;color:red;font-weight: 700;text-align: left;margin-right: 100px"> {{ $orderDelivery->shipmentid}}</span>
                    Cliente: <span style="font-size: 13px;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->customer ? $orderDelivery->customer->name : ''}}</span>
                    Fecha de trabajo: <span style="font-size: 13px;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->workday}}</span>
                    Turno: <span style="font-size: 13px;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->turns ? $orderDelivery->turns->name : ''}}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ url()->previous() }}">
                                @include('buttons.back')
                            </a>
                            @can('orderdeliveries.show')
                                <a href="{{url('orderdeliveries/' . $orderDelivery->id)}}"
                                   target="_blank">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/viewdetail.png')}}" alt=""
                                             class="img-16">
                                        <span style="font-size: 0.675rem "> Ver Kardex</span>
                                    </button>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <hr class="mt-3 mb-3"></hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card-input">
                                        <table class="table table-condensed  table-bordered table-hover">
                                            <tr>
                                                <th>Evento</th>
                                                <th>Fecha y Hora</th>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 11px;width: 50%">Inicio de Facturación</td>
                                                <td>
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 11px">Fin de Facturación
                                                </td>
                                                <td>
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 11px">Solicitud de Carta Porte
                                                </td>
                                                <td>
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 11px">Receipción de Carta Porte
                                                </td>
                                                <td>
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
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3"
                                role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab01" role="tab">
                                        <span class="hidden-sm-up"><i class="ti-home font-12"></i></span> <span
                                                class="hidden-xs-down ml-3 mr-3">Pedido</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab02" role="tab">
                                        <span class="hidden-sm-up"><i class="ti-close font-12"></i></span> <span
                                                class="hidden-xs-down ml-3 mr-3">Sellos</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab03" role="tab">
                                        <span class="hidden-sm-up"><i class="ti-comment font-12"></i></span> <span
                                                class="hidden-xs-down ml-3 mr-3">Comentarios</span>
                                    </a>
                                </li>

                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab01" role="tabpanel">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-checkbox-multiple-blank-fill text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <form method="POST"
                                                  action="{{url('monitoranalistatrafico/' . $orderDelivery->id)}}"
                                                  enctype="multipart/form-data">
                                                {{ method_field('PUT') }}
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                                        <button type="submit"
                                                                class="btn btn-block btn-block btn-outline-success btn-block">
                                                            <i class="fas fa-check mr-2"></i>
                                                            Guardar datos
                                                        </button>
                                                    </div>
                                                </div>

                                                <hr class="mt-3"></hr>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="totalpieces">Cajas
                                                                                solicitadas
                                                                                <span
                                                                                        style="color: red">*</span></label>
                                                                            <input type="text"
                                                                                   readonly
                                                                                   class="form-control  numeric"
                                                                                   value="{{$orderDelivery->header->totalpieces}}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="totalpiecesfac">Cajas
                                                                                facturadas
                                                                                <span
                                                                                        style="color: red">*</span></label>
                                                                            <input type="text"
                                                                                   required
                                                                                   class="form-control numeric"
                                                                                   id="totalpiecesfac"
                                                                                   name="totalpiecesfac"
                                                                                   value="{{$orderDelivery->header->totalpiecesfac}}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="totalcontainers">Pallets
                                                                                solicitados
                                                                                <span
                                                                                        style="color: red">*</span></label>
                                                                            <input type="text"
                                                                                   readonly
                                                                                   class="form-control numeric"
                                                                                   value="{{$orderDelivery->header->totalcontainers}}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="totalcontainersfac">Pallets
                                                                                facturados
                                                                                <span
                                                                                        style="color: red">*</span></label>
                                                                            <input type="text"
                                                                                   required
                                                                                   class="form-control numeric"
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
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab02" role="tabpanel">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-checkbox-multiple-blank-fill text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            {{--Sellos del embarque--}}
                                            <hr class="mt-3"></hr>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card-input">
                                                        <form method="POST"
                                                              action="{{url('savesealstoshipment/' . $orderDelivery->id)}}"
                                                              enctype="multipart/form-data">
                                                            {{ method_field('PUT') }}
                                                            @csrf
                                                            <div class="row mt-3 mb-3">
                                                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                                                    <button type="submit"
                                                                            class="btn btn-block btn-block btn-outline-success btn-block">
                                                                        <i class="fas fa-check mr-2"></i>
                                                                        Guardar sellos
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="containerseal01">Número
                                                                                    de
                                                                                    sello 01
                                                                                    <span
                                                                                            style="color: red">*</span></label>
                                                                                <input type="text" required
                                                                                       class="form-control"
                                                                                       id="containerseal01"
                                                                                       name="containerseal01"
                                                                                       value="{{$orderDelivery->seal->containerseal01}}"
                                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                                >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="containerseal02">Número
                                                                                    de
                                                                                    sello 02
                                                                                    <span
                                                                                            style="color: red">*</span></label>
                                                                                <input type="text"
                                                                                       class="form-control"
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
                                                                                <label for="containerseal03">Número
                                                                                    de
                                                                                    sello 03
                                                                                    <span
                                                                                            style="color: red">*</span></label>
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       id="containerseal03"
                                                                                       name="containerseal03"
                                                                                       value="{{$orderDelivery->seal->containerseal03}}"
                                                                                       onKeyUp=this.value=this.value.toUpperCase()
                                                                                >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="receivername">Entregado
                                                                                    a
                                                                                    <span
                                                                                            style="color: red">*</span></label>
                                                                                <input type="text" required
                                                                                       class="form-control"
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
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab03" role="tabpanel">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-checkbox-multiple-blank-fill text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            {{--Comentarios--}}
                                            <hr class="mt-3 mb-3"></hr>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="card-input">
                                                        <form method="POST"
                                                              action="{{url('savecommenttoshipment/' . $orderDelivery->id)}}"
                                                              enctype="multipart/form-data">
                                                            {{ method_field('PUT') }}
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="comment">Comentario
                                                                    de facturación</label>
                                                                <textarea class="form-control"
                                                                          rows="5" id="comment"
                                                                          name="comment"
                                                                          required></textarea>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                    <button type="submit"
                                                                            class="btn btn-block btn-block btn-outline-success btn-block">
                                                                        <i class="fas fa-check mr-2 text-button"></i>
                                                                        Guardar
                                                                        comentario
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="card-input">
                                                        <div class="comment-widgets scrollable"
                                                             style="height:350px;">
                                                            @foreach($comments as $comment)
                                                                <div class="d-flex flex-row comment-row m-t-0">
                                                                    <div>
                                                                        <img src="{{asset('ico/notepad_bloc_notes_15549.png')}}"
                                                                             alt="user"
                                                                             width="30"
                                                                             class="rounded-circle">
                                                                    </div>
                                                                    <div class="comment-text w-100">
                                                                        <h6 class="font-10">{{$comment->user ? $comment->user->name : ''}}</h6>
                                                                        <span class="m-b-15 d-block font-14">{{$comment->comment}}</span>
                                                                        <div class="comment-footer">
                                                                            <span class="text-muted float-right">{{$comment->created_at}}</span>
                                                                            <span class="label label-rounded label-primary">Creado</span>
                                                                            <span class="action-icons"></span>
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
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
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

