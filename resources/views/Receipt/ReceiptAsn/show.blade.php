@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de recibo
                </div>
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <a href="{{ url()->previous() }}">
                                @include('buttons.back')
                            </a>
                            @if($receiptasn->statusid != 'IG')
                                @if($receiptasn->statusid != 'CE')
                                    @can('cancellationReceipt')
                                        <a href="" data-target="#modal-cancellationreceipt-{{$receiptasn->id}}"
                                           data-toggle="modal">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                                                <span class="text-button"> Cancelar Recibo</span>
                                            </button>
                                        </a>
                                        @include('Receipt.ReceiptAsn.options.cancellationreceipt')
                                    @endcan
                                @endif
                            @endif
                            @if($receiptasn->statusid != 'IG')
                                @if($receiptasn->statusid != 'CE')
                                    @can('receiptasn.destroy')
                                        <a href="" data-target="#modal-destroy-{{$receiptasn->id}}" data-toggle="modal">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/destroy.png')}}" alt="" class="img-16">
                                                <span class="text-button"> Eliminar Recibo</span>
                                            </button>
                                        </a>
                                        @include('Receipt.ReceiptAsn.destroy')
                                    @endcan
                                @endif
                            @endif
                            @if($receiptasn->statusid != 'IG')
                                @if($receiptasn->statusid != 'CE')
                                    @can('receiptasn.closereceipt')
                                        <a href="" data-target="#modal-closereceipt-{{$receiptasn->id}}"
                                           data-toggle="modal">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/closeverific.png')}}" alt="" class="img-16">
                                                <span class="text-button"> Terminar Recibo</span>
                                            </button>
                                        </a>
                                        @include('Receipt.ReceiptAsn.closereceipt')
                                    @endcan
                                @endif
                            @endif
                            @if ($receiptasn->statusid == 'IG')
                                @can('receiptasn.kardex')
                                    <a href="{{url('receiptasn/' . $receiptasn->id . '/kardex')}}">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-1">
                                            <span class="text-button"> Imprimir PDF</span>
                                        </button>
                                    </a>
                                @endcan
                            @endif
                            @if ($receiptasn->statusid != 'IG')
                                <a href="" data-target="#modal-addnote-{{$receiptasn->id}}" data-toggle="modal">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/note.png')}}" alt="" class="img-16 ml-1">
                                        <span class="text-button"> Agregar comentario</span>
                                    </button>
                                </a>
                                @include('Receipt.ScheduleReceipt.options.addnote')


                                @if(!empty($receiptasn->folionumber))
                                    @if($receiptasn->unloadstartdate == null)
                                        @can('receiptasn.startprocess')
                                            <a href="" data-target="#modal-startprocess-{{$receiptasn->id}}"
                                               data-toggle="modal">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-outline-info btn-md">
                                                    <img src="{{asset('/ico/storageturns.png')}}" alt=""
                                                         class="img-16 ml-1">
                                                    <span class="text-button"> Iniciar Proceso</span>
                                                </button>
                                            </a>
                                            @include('Receipt.SchedulleV2.Events.startprocess')
                                        @endif
                                    @endif
                                @endif
                                @if(!empty($receiptasn->folionumber))
                                    @if($receiptasn->unloadfinishdate == null)
                                        @can('receiptasn.finishprocess')
                                            <a href="" data-target="#modal-finishprocess-{{$receiptasn->id}}"
                                               data-toggle="modal">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-outline-info btn-md">
                                                    <img src="{{asset('/ico/storageturns.png')}}" alt=""
                                                         class="img-16 ml-1">
                                                    <span class="text-button"> Finalizar Proceso</span>
                                                </button>
                                            </a>
                                            @include('Receipt.SchedulleV2.Events.finishprocess')
                                        @endif
                                    @endif
                                @endif


                            @endcan
                            @if($receiptasn->folionumber != null)
                                <a href="{{url('tracereceiptunit/' . $receiptasn->folionumber)}}" target="_blank">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img
                                            src="{{asset('/ico/data_hosting_information_internet_security_server_storage_icon_127051.png')}}"
                                            alt="" class="img-16 ml-2 mr-2">
                                        <span class="text-button"> Karde de Folio</span>
                                    </button>
                                </a>
                            @endif



                        </div>
                    </div>
                    <hr class="m-t-1">

                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Centro de distribución:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->center ? $receiptasn->center->description :''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Bodega:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->warehouse ? $receiptasn->warehouse->name : ''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Categoría:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->inventorystock ? $receiptasn->inventorystock->description : ''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Evento:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->reason ? $receiptasn->reason->name : ''}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Embarque:</h6>
                                    <span class="m-b-15 d-block font-12"> {{$receiptasn->shipmentid}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Factura:</h6>
                                    <span class="m-b-15 d-block font-12"> {{$receiptasn->billoflading}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Orden de compra:</h6>
                                    <span class="m-b-15 d-block font-12"> {{$receiptasn->oc}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">PO:</h6>
                                    <span class="m-b-15 d-block font-12"> {{$receiptasn->po}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Proveedor:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->provider ? $receiptasn->provider->name : ''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                              <span style="margin: auto;display: block;text-align: left">
                                    {!! DNS2D::getBarcodeHTML((string)$receiptasn->shipmentid, 'QRCODE',4,4) !!}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Inicio de verificación:</h6>
                                    <span class="m-b-15 d-block font-12 font-12"> {{$receiptasn->unloadstartdate}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Fin de verificación:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->unloadfinishdate}}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Folio de control de patios:</h6>
                                    <span class="m-b-15 d-block font-12 font-12"> {{$receiptasn->folionumber}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Línea de transporte:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->folio ? $receiptasn->carrier->name : ''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Placa de caja:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->folio ? $receiptasn->folio->boxnumber : ''}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Operador:</h6>
                                    <span
                                        class="m-b-15 d-block font-12"> {{$receiptasn->folio ? $receiptasn->folio->tracto->operatorname : ''}}</span>
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
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                role="tab"><span
                                    class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Detalle</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-camera"></i></span> <span class="hidden-xs-down">Evidencia</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-list"></i></span> <span class="hidden-xs-down">Incidencias</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view04" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-comment"></i></span> <span class="hidden-xs-down">Comentarios</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view05" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-comment"></i></span> <span class="hidden-xs-down">Detalle de contenedores</span></a>
                        </li>

                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">

                        <div class="tab-pane active" id="view01" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Detalle de material
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-3">
                                                <div class="col-lg-12">
                                                    <table
                                                        class="table table-bordered table-condensed table-striped tablegeneral ">
                                                        <thead>
                                                        <tr>
                                                            <th>Material</th>
                                                            <th>Description</th>
                                                            <th>Lote</th>
                                                            <th>Cantidad esperada (Piezas)</th>
                                                            <th>Cantidad escaneada (Piezas)</th>
                                                            <th>Opciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($stocks as $stock)
                                                            <tr>
                                                                <td>{{$stock->prtnum}}</td>
                                                                <td>{{$stock->lngdsc}}</td>
                                                                <td>{{$stock->expirecode}}</td>
                                                                <td>{{$stock->quantitytrue}}</td>
                                                                <td>
                                                                    @if(!empty($stock->totalescaneado))
                                                                        @if($stock->quantitytrue != $stock->totalescaneado)
                                                                            <span class="badge badge-danger"
                                                                                  style="padding: 5px 10px">
                                                                                {{$stock->totalescaneado}}
                                                                            </span>
                                                                        @else
                                                                            <span class="badge badge-success"
                                                                                  style="padding: 5px 10px">
                                                                                {{$stock->totalescaneado}}
                                                                            </span>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($receiptasn->statusid != 'IG')
                                                                        <a href=""
                                                                           data-target="#modal-updatequantity-{{$stock->id}}"
                                                                           data-toggle="modal" data-placement="top">
                                                                            <button type="button"
                                                                                    class="btn waves-effect waves-light btn-outline-info btn-sm">
                                                                                <img src="{{asset('/ico/adedit.png')}}"
                                                                                     alt=""
                                                                                     class="img-16">
                                                                            </button>
                                                                        </a>
                                                                        @include('Receipt.ReceiptAsn.editquantity')
                                                                        <a href=""
                                                                           data-target="#modal-deletestock-{{$stock->id}}"
                                                                           data-toggle="modal" data-placement="top">
                                                                            <button type="button"
                                                                                    class="btn waves-effect waves-light btn-outline-info btn-sm">
                                                                                <img
                                                                                    src="{{asset('/ico/closemodal.png')}}"
                                                                                    alt=""
                                                                                    class="img-16">
                                                                            </button>
                                                                        </a>
                                                                        @include('Receipt.ReceiptAsn.deletestock')
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane  p-20" id="view02" role="tabpanel">
                            @if (!empty($evidences))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row el-element-overlay">
                                                    @foreach($evidences as $photo)
                                                        @if($photo->auxiliary01 == 'DES')
                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="card">
                                                                    <div class="el-card-item">
                                                                        <div
                                                                            class="el-card-avatar el-overlay-1">
                                                                            <img
                                                                                src="{{asset('storage/' . $photo->path)}}"
                                                                                alt="Evidencia"
                                                                                style="height: 200px;max-height: 200px"/>
                                                                            <div class="el-overlay">
                                                                                <ul class="list-style-none el-info">
                                                                                    <li class="el-item">
                                                                                        <a href="{{url('downloadphotocheckin/' . $photo->id)}}"
                                                                                           data-togggle="tooltip"
                                                                                           title="Descargar"
                                                                                           class="btn default btn-outline el-link">
                                                                                            <i class="mdi mdi-download"></i>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="el-card-content">
                                                                            <h4 class="m-b-0 font-10 font-weight-light"> {{$photo->user->name}} </h4>
                                                                            <span
                                                                                class="text-muted">{{$photo->created_at}}
                                                                        </span>
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
                        <div class="tab-pane " id="view03" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table id="table-incidencedetails"
                                           class="table table-condensed table-bordered table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Número de parte</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <th>Categoría</th>
                                            <th>Sub Categoría</th>
                                            <th>Verificador</th>
                                            <th>Fecha de creación</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incidences as $incidence)
                                            <tr>
                                                <td>{{$incidence->partnumber ? $incidence->partnumber->prtnum : ''}}</td>
                                                <td>{{$incidence->partnumber ? $incidence->partnumber->lngdsc : ''}}</td>
                                                <td>{{$incidence->quantity}}</td>
                                                <td>{{$incidence->categoryincidence ? $incidence->categoryincidence->description : ''}}</td>
                                                <td>{{$incidence->subcategoryincidence ? $incidence->subcategoryincidence->description : ''}}</td>
                                                <td>{{$incidence->user ? $incidence->user->name : ''}}</td>
                                                <td>{{$incidence->created_at}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if (!empty($evidences))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row el-element-overlay">
                                                    @foreach($evidences as $photo)
                                                        @if($photo->auxiliary01 == 'INC')
                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="card">
                                                                    <div class="el-card-item">
                                                                        <div
                                                                            class="el-card-avatar el-overlay-1">
                                                                            <img
                                                                                src="{{asset('storage/' . $photo->path)}}"
                                                                                alt="Evidencia"
                                                                                style="height: 200px;max-height: 200px"/>
                                                                            <div class="el-overlay">
                                                                                <ul class="list-style-none el-info">
                                                                                    <li class="el-item">
                                                                                        <a href="{{url('downloadphotocheckin/' . $photo->id)}}"
                                                                                           data-togggle="tooltip"
                                                                                           title="Descargar"
                                                                                           class="btn default btn-outline el-link">
                                                                                            <i class="mdi mdi-download"></i>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="el-card-content">
                                                                            <h4 class="m-b-0 font-10 font-weight-light"> {{$photo->user->name}} </h4>
                                                                            <span
                                                                                class="text-muted">{{$photo->created_at}}
                                                                        </span>
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
                        <div class="tab-pane " id="view04" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            @foreach($coments as $item)
                                                <div class="d-flex flex-row comment-row">
                                                    <div class="comment-text active w-100">
                                                        <h6 class="font-weight-light">
                                                            {{$item->user->name}}
                                                        </h6>
                                                        <span
                                                            class="text-muted float-right">{{$item->created_at}}</span>
                                                        <span class="m-b-15 d-block">
                                                            {{$item->comment}}
                                                        </span>
                                                        <div class="comment-footer ">
                                                            <hr class="mt-2">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="view05" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <table
                                                class="table table-condensed table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Número de parte</th>
                                                    <th>Descripción</th>
                                                    <th>Cantidad cajas</th>
                                                    <th>Cantidad piezas</th>
                                                    <th>Lpn</th>
                                                    <th>Lote</th>
                                                    <th>Fecha de caducidad</th>
                                                    <th>¿Corresponde en EAN?</th>
                                                    <th>¿Corresponde la F.Caducidad?</th>
                                                    <th>Evidencia</th>
                                                    <th>Usuario</th>
                                                    <th>Fecha de creación</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($totalContainers as $container)
                                                    <tr>
                                                        <td>{{$container->partnumber ? $container->partnumber->prtnum : ''}}</td>
                                                        <td>{{$container->partnumber ? $container->partnumber->lngdsc : ''}}</td>
                                                        <td>{{$container->quantity }}</td>
                                                        <td>{{$container->quantity * $container->partnumber->untcas}}</td>
                                                        <td>{{$container->lpnid}}</td>
                                                        <td>{{$container->batch}}</td>
                                                        <td>{{$container->expiredate}}</td>
                                                        <td>
                                                            @if($container->eanpz == $container->eanbox)
                                                                <span class="badge badge-success"
                                                                      style="padding: 5px 10px">SI</span>
                                                            @else
                                                                <span class="badge badge-danger"
                                                                      style="padding: 5px 10px">NO</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($container->auxiliary01 == 'SI' )
                                                                <span class="badge badge-success"
                                                                      style="padding: 5px 10px">SI</span>
                                                            @else
                                                                <span class="badge badge-danger"
                                                                      style="padding: 5px 10px">NO</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($container->photo))
                                                                <div class="el-card-item">
                                                                    <div class="el-card-avatar el-overlay-1">
                                                                        <div class="el-overlay">
                                                                            <ul class="list-style-none el-info">
                                                                                <li class="el-item bg-grey">
                                                                                    <a class="btn default btn-outline image-popup-vertical-fit el-link"
                                                                                       href="{{asset('storage/' . $container->photo)}}">
                                                                                        Ver
                                                                                        <i class="icon-magnifier"></i>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                            @endif
                                                        </td>
                                                        <td>{{$container->user ? $container->user->name : ''}}</td>
                                                        <td>{{$container->created_at}}</td>
                                                        <td>
                                                            @if($receiptasn->statusid != 'IG')
                                                                <button class="btn btn-sm btn-outline-info"
                                                                        onclick="deleteLpnOfReceipt( {!! $container->recordid!!} )">
                                                                    Eliminar
                                                                </button>

                                                                <a href=""
                                                                   data-target="#modal-updatequantity-{{$container->recordid}}"
                                                                   data-toggle="modal" data-placement="top">
                                                                    <button type="button"
                                                                            class="btn waves-effect waves-light btn-outline-info btn-sm ml-2">
                                                                        {{--                                                                        <img src="{{asset('/ico/closemodal.png')}}" alt="" class="img-16">--}}
                                                                        Modificar
                                                                    </button>
                                                                </a>
                                                                @include('Receipt.ReceiptAsn.editquantitycontainer')
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
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
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id="map"></div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <style>
        #map {
            height: 900px;
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/magnific-popup/dist/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/meg.init.js')}}"></script>

    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
            });
            $("table.table").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "400px",
                "serverSide": false,
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
                buttons: [
                    {
                        "extend": "colvis",
                        "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                        columns: ':not(:first):not(:last)'
                    },
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
        })
    </script>
    <script>
        function deleteLpnOfReceipt($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "El siguiente evento eliminara el numero de lpn  seleccionado!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar Lpn!'
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
                        url: '{!! route('deleteLpnOfReceipt') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                title: `Evento procesado correctamente`,
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
