@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Número de embarque: <span
                        style="font-size: 13px;font-weight: 700;text-align: left;margin-right: 100px"> {{ $orderDelivery->shipmentid}}</span>
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
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('monitoranalistatrafico.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-input">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="containerseal01">Número de sello 01 <span
                                                                style="color: red">*</span></label>
                                                        <input type="text" class="form-control" id="containerseal01"
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
                                                        <input type="text" class="form-control" id="containerseal02"
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
                                                        <input type="text" class="form-control" id="containerseal03"
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
                                                        <input type="text" class="form-control" id="receivername"
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
                            <div class="col-lg-6">
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
                                                                <input type="text" readonly class="form-control  numeric"
                                                                       id="totalpieces" name="totalpieces"
                                                                       value="{{$orderDelivery->header->totalpieces}}"
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="totalpiecesfac">Cajas facturadas <span
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
                                                                <label for="totalcontainers">Pallets solicitados <span
                                                                        style="color: red">*</span></label>
                                                                <input type="text" readonly class="form-control numeric"
                                                                       id="totalcontainers" name="totalcontainers"
                                                                       value="{{$orderDelivery->header->totalcontainers}}"
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="totalcontainersfac">Pallets facturados <span
                                                                        style="color: red">*</span></label>
                                                                <input type="text" class="form-control numeric"
                                                                       id="totalcontainersfac" name="totalcontainersfac"
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
                    </form>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-input">
                                <table class="table table-condensed  table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Inicio de facturación</th>
                                        <th>Fin de facturación</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-center font-weight-bold">
                                            @if ($orderDelivery->operation->senddocument != '')
                                                @if ($orderDelivery->header->startgoodissuedate == null)
                                                    <a href=""
                                                       data-target="#modal-addstartgoodissuedate-{{$orderDelivery->id}}"
                                                       data-toggle="modal">
                                                        <button  class="btn waves-effect waves-light btn-outline-info btn-md btn-block" >
                                                            <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16">
                                                            <span style="font-size: 0.675rem "> Iniciar Facturación</span>
                                                        </button>
                                                    </a>
                                                    @include('Shipments.OrderMonitor.AnalystTraffic.partials.startgoodissuedate')
                                                @else
                                                  <span class="text-center font-14" > {{$orderDelivery->header->startgoodissuedate}}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center font-weight-bold">
                                            @if ($orderDelivery->header->startgoodissuedate != '')
                                                @if ($orderDelivery->header->goodissuedate == null)
                                                    <a href=""
                                                       data-target="#modal-goodissuedate-{{$orderDelivery->id}}"
                                                       data-toggle="modal">
                                                        <button  class="btn waves-effect waves-light btn-outline-info btn-md btn-block" >
                                                            <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16">
                                                            <span style="font-size: 0.675rem "> Finalizar Facturación</span>
                                                        </button>
                                                    </a>
                                                    @include('Shipments.OrderMonitor.AnalystTraffic.partials.goodissuedate')
                                                @else
                                                    <span class="text-center font-14" > {{$orderDelivery->header->goodissuedate}} </span>
                                                @endif
                                            @endif
                                        </td>
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

