@extends('layouts.app')

@section('content')

    @if($orderDelivery->check)
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Número de embarque: <span
                            style="font-size: 16px;color: black;font-weight: 700;text-align: left;margin-right: 100px;color: red"> {{ $orderDelivery->shipmentid}}</span>
                        Cliente: <span
                            style="font-size: 13px;color: black;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->customer ? $orderDelivery->customer->name : ''}}</span>
                        Linea de transporte: <span
                            style="font-size: 13px;color: black;font-weight: 400;text-align: left;margin-right: 100px"> {{ $orderDelivery->carrier ? $orderDelivery->carrier->name : ''}}</span>
                        Estatus: <span
                            style="font-size: 13px;color: black;font-weight: 400;text-align:right;"> {{ $orderDelivery->status ? $orderDelivery->status->name : ''}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="{{ url()->previous() }}">
                                    @include('buttons.back')
                                </a>
                                @can('closecheckshipment')
                                    @if($orderDelivery->statusid == 'VF')
                                        <a href="" data-target="#modal-closeverific" data-toggle="modal">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                    id="closeverific">
                                                <img src="{{asset('/ico/closeverific.png')}}" alt="" class="img-16">
                                                <span style="font-size: 11px;"> Cerrar verificación</span>
                                            </button>
                                        </a>
                                        @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.closeverific')
                                    @endif
                                @endcan
                                @can('addpartnumbertoshipment')
                                    @if(empty($orderDelivery->check->finishdate))
                                        <a href="" data-target="#modal-addpartnumber" data-toggle="modal">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                    id="closeverific">
                                                <img src="{{asset('/ico/window_add_12815.png')}}" alt="" class="img-16">
                                                <span style="font-size: 11px;"> Agregar pedido</span>
                                            </button>
                                        </a>
                                        @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.addpartnumber')
                                    @endif
                                @endcan
                                @can('opencount')
                                    @if($orderDelivery->statusid == 'VF' )
                                        <a href="">
                                            <button type="submit" id="opencount"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/changelocation.png')}}" alt=""
                                                     class="img-16">
                                                <span style="font-size: 11px;"> Reabrir conteo</span>
                                            </button>
                                        </a>
                                    @endif
                                @endcan
                                <a href="" data-target="#modal-verificacion" data-toggle="modal">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md"
                                            id="closeverific">
                                        <img src="{{asset('/ico/verificacion.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Datos de verificación</span>
                                    </button>
                                </a>
                                @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.verificacion')
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
                        <div class="row m-t-4">
                            <div class="col-lg-2 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="m-r-10">
                                        <span class="text-orange display-5">
                                           <img src="{{asset('/ico/daños.png')}}" alt="" style="width: 40px;height: 40px">
                                        </span>
                                    </div>
                                    <div><span>Total de pallets</span>
                                        <h3 class="font-medium m-b-0">
                                            {!! number_format( $orderDelivery->header->totalcontainers) !!}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="m-r-10">
                                        <span class="text-cyan display-5">
                                              <img src="{{asset('/ico/reportincidence.png')}}" alt="" style="width: 40px;height: 40px">
                                        </span>
                                    </div>
                                    <div><span>Total de cajas</span>
                                        <h3 class="font-medium m-b-0">
                                            {!! number_format( $orderDelivery->header->totalpieces) !!}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="m-r-10"><span class="text-info display-5">
                                             <span class="text-cyan display-5">
                                              <img src="{{asset('/ico/peso.png')}}" alt="" style="width: 40px;height: 40px">
                                        </span>
                                        </span></div>
                                    <div><span>Peso Total</span>
                                        <h3 class="font-medium m-b-0">
                                            {!! number_format( $orderDelivery->header->totalweight) !!} Kg
                                        </h3></div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="m-r-10"><span class="text-primary display-5">
                                              <img src="{{asset('/ico/graphics_statistics_20922.png')}}" alt="" style="width: 40px;height: 40px">
                                        </span></div>
                                    <div><span>% de Avance</span>
                                        <h3 class="font-medium m-b-0">
                                            @if(!is_null($total))
                                                @if($total->verificado != 0)
                                                    {!! number_format(intval ((100 / (int) $total->totalpedido) * (int) $total->verificado))  !!}
                                                    %
                                                @endif
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="m-r-10"><span class="text-primary display-5">
                                        <i class="mdi mdi-shape-square-rounded-plus"></i></span></div>
                                    <div><span>Embarque</span>
                                        <h3 class="font-medium m-b-0">
                                            {!! DNS2D::getBarcodeHTML($orderDelivery->shipmentid, 'QRCODE',4,4) !!}
                                        </h3>
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
                    <div class="card-header">
                        Detalle de verificación
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                                    href="#home" role="tab"><span class="hidden-sm-up">
                                                        <i class="ti-home"></i></span> <span class="hidden-xs-down">Detalle del avance</span></a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#lpns"
                                                                    role="tab"><span class="hidden-sm-up">
                                                        <i class="ti-list"></i></span> <span class="hidden-xs-down">Lpns del embarque</span></a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                                    href="#detailslpns" role="tab"><span
                                                        class="hidden-sm-up">
                                                        <i class="ti-list"></i></span> <span class="hidden-xs-down">Detalle de lpns</span></a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view01"
                                                                    role="tab"><span class="hidden-sm-up">
                                                        <i class="ti-list"></i></span> <span class="hidden-xs-down">Relación entre Lpns</span></a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02"
                                                                    role="tab"><span class="hidden-sm-up">
                                                        <i class="ti-list"></i></span> <span class="hidden-xs-down">Kardex de consolidación</span></a>
                                            </li>
                                        </ul>
                                        <div class="tab-content tabcontent-border">
                                            @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.Tabs.home')
                                            @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.Tabs.lpns')
                                            @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.Tabs.detailslpns')
                                            @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.Tabs.view01')
                                            @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.Tabs.view02')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.Tabs.backhome')
    @endif

@endsection


@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
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
                "dom": '<"top">fBlrt<"bottom"p><"clear"i>',
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
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#opencount').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        shipmentid: `{!! $orderDelivery->shipmentid !!}`,
                    },
                    url: '{!! route('openCount') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        Swal.fire({
                            type: `${data.CodeResponse}`,
                            text: `${data.Message}`,
                            width: 350,
                        })
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    },
                });
            });
        });
    </script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#closeverificprocess').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        shipmentid: `{!! $orderDelivery->shipmentid !!}`,
                    },
                    url: '{!! route('closeverificprocess') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        $("#opencount").hide();
                        $("#closeverific").hide();
                        Swal.fire({
                            type: `${data.CodeResponse}`,
                            text: `${data.Message}`,
                            width: 400,
                            confirmButtonText: 'Aceptar',
                        })
                        $('#modal-closeverific').modal('hide')
                    },
                });
            });
        });
    </script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#addsku').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        shipmentid: `{!! $orderDelivery->shipmentid !!}`,
                        partnumberid: $("#partnumberid").val(),
                        quantity: $("#quantity").val(),
                    },
                    url: '{!! route('addpartnumbertoshipment') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {

                        Swal.fire({
                            type: `${data.CodeResponse}`,
                            text: `${data.Message}`,
                            width: 400,
                            confirmButtonText: 'Aceptar',
                        })
                        $('#modal-addpartnumber').modal('hide')
                        document.getElementById("partnumberid").value = "";
                        document.getElementById("quantity").value = "";
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    },
                });
            });
        });
    </script>
    <script>
        function deleteLpn($id) {
            Swal.fire({
                title: '¿Estas seguro?',
                text: "El siguiente evento eliminara el lpn del embarque!",
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
                        url: '{!! route('deletelpnfromshipment') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                type: 'success',
                                text: `${data.value02}`,
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
