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
                            <a href="{{route('receiptasn.index')}}">
                                @include('buttons.back')
                            </a>
                            @if($receiptasn->statusid != 'TR')
                                @if($receiptasn->statusid != 'CA')
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
                            @if($receiptasn->statusid != 'TR')
                                @if($receiptasn->statusid != 'CA')
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
                            @if($receiptasn->statusid != 'TR')
                                @if($receiptasn->statusid != 'CA')
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

                                    @can('goodissuedatereceipt')
                                        @if ($receiptasn->unloadfinishdate != null)
                                            <a href="" data-target="#modal-goodissuedate-{{$receiptasn->id}}"
                                               data-toggle="modal">
                                                <button type="button"
                                                        class="btn waves-effect waves-light btn-outline-info btn-md">
                                                    <img src="{{asset('/ico/closeverific.png')}}" alt="" class="img-16">
                                                    <span class="text-button"> Cerrar Recibo</span>
                                                </button>
                                            </a>
                                            @include('Receipt.ScheduleReceipt.options.goodissuedate')
                                        @endif
                                    @endcan

                                @endif
                            @endif


                            @if ($receiptasn->statusid == 'TR')
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

                            @if ($receiptasn->statusid != 'TR')
                                <a href="" data-target="#modal-addnote-{{$receiptasn->id}}" data-toggle="modal">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/note.png')}}" alt="" class="img-16 ml-1">
                                        <span class="text-button"> Agregar comentario</span>
                                    </button>
                                </a>
                                @include('Receipt.ScheduleReceipt.options.addnote')
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
                                    <h6 class="font-weight-light">Ruta:</h6>
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
                                    <h6 class="font-weight-light">Inicio de descarga:</h6>
                                    <span class="m-b-15 d-block font-12 font-12"> {{$receiptasn->unloadstartdate}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Fin de descarga:</h6>
                                    <span
                                            class="m-b-15 d-block font-12"> {{$receiptasn->unloadfinishdate}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Inicio de verificación:</h6>
                                    <span class="m-b-15 d-block font-12 font-12"> {{$receiptasn->verifystartdate}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="d-flex flex-row comment-row m-t-0">
                                <div class="comment-text w-100">
                                    <h6 class="font-weight-light">Fin de verificación:</h6>
                                    <span
                                            class="m-b-15 d-block font-12"> {{$receiptasn->verifyfinishdate}}</span>
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
                                        class="hidden-sm-up"><i class="ti-home"></i></span> <span
                                        class="hidden-xs-down">Programado</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02"
                                                role="tab"><span
                                        class="hidden-sm-up"><i class="ti-home"></i></span> <span
                                        class="hidden-xs-down">Verificado</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view03" role="tab"><span
                                        class="hidden-sm-up"><i class="ti-camera"></i></span> <span
                                        class="hidden-xs-down">Evidencia</span></a>
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
                                                    <table class="table table-bordered table-condensed tablegeneral">
                                                        <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th>SKU</th>
                                                            <th>Descripción</th>
                                                            <th>Unidad de medida</th>
                                                            <th>Cantidad</th>
                                                            <th>Rotativa</th>
                                                            <th>Definitiva</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($expectedProducts as $product)
                                                            <tr>
                                                                <td>{{$product['Source']}}</td>
                                                                <td>{{$product['PartNumberCode']}}</td>
                                                                <td>{{$product['Description']}}</td>
                                                                <td>{{$product['UoMBase']}}</td>
                                                                <td>{{$product['ExpectedTotal']}}</td>
                                                                <td>{{$product['ExpectedRotary']}}</td>
                                                                <td>{{$product['ExpectedDefinitive']}}</td>
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

                        <div class="tab-pane" id="view02" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Detalle de material
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-3">
                                                <div class="col-lg-12">
                                                    <table class="table table-bordered table-condensed tablegeneral">
                                                        <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th>SKU</th>
                                                            <th>Descripción</th>
                                                            <th>Unidad de medida</th>
                                                            <th>Cantidad esperada</th>
                                                            <th>Cantidad verificada</th>
                                                            <th>Rotativa</th>
                                                            <th>Definitiva</th>
                                                            <th>Pendiente</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($verificationLines as $line)
                                                            <tr>
                                                                <td>{{$line['Source']}}</td>
                                                                <td>{{$line['PartNumberCode']}}</td>
                                                                <td>{{$line['Description']}}</td>
                                                                <td>{{$line['UoMBase']}}</td>
                                                                <td>{{$line['ExpectedTotal']}}</td>
                                                                <td>{{$line['VerifiedTotal']}}</td>
                                                                <td>{{$line['VerifiedRotary']}}</td>
                                                                <td>{{$line['VerifiedDefinitive']}}</td>
                                                                <td>{{$line['RemainingTotal']}}</td>
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
                        <div class="tab-pane p-20" id="view03" role="tabpanel">
                            <div class="row">
                                @forelse($definitiveVerificationEvidences as $verification)
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="el-card-item">
                                                <div class="el-card-avatar el-overlay-1">
                                                    <img src="{{asset('storage/' . $verification->evidence_path)}}"
                                                         alt="Evidencia de {{$verification->inventorypartnumber->prtnum}}"
                                                         style="height: 200px; max-height: 200px"/>
                                                </div>
                                                <div class="el-card-content">
                                                    <h4 class="m-b-0 font-10 font-weight-light">
                                                        {{$verification->inventorypartnumber->prtnum}}
                                                    </h4>
                                                    <span class="text-muted">
                                                        {{$verification->inventorypartnumber->lngdsc}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="mb-0 text-muted">No hay evidencias de productos definitivos.</p>
                                    </div>
                                @endforelse
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
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/magnific-popup/dist/magnific-popup.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/meg.init.js')}}"></script>

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

@endsection
