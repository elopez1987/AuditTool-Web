@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Inventario  - {{$inventoryhead->name}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{url('inventoryheads')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/back.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Salir</span>
                                </button>
                            </a>
                            <a href="{{url('inventoryheads')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/sap.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Existencia SAP</span>
                                </button>
                            </a>
                            <a href="{{url('inventoryheads')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/wms.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Existencia WMS</span>
                                </button>
                            </a>
                            <a href="{{url('inventoryheads')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/reportshipments.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Conciliación</span>
                                </button>
                            </a>
{{--                            <a href="{{url('inventoryheads')}}">--}}
{{--                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">--}}
{{--                                    <img src="{{asset('/ico/importdata.png')}}" alt="" class="img-16 ml-1">--}}
{{--                                    <span class="text-button ml-3 mr-3"> Tareas 3er Conteo</span>--}}
{{--                                </button>--}}
{{--                            </a>--}}

                            <a href="{{url('inventory-supervisors/' . $inventoryhead->inventory_uuid)}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/adduser.png')}}" alt=""
                                         class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Crear Facilitador y Contadores</span>
                                </button>
                            </a>

                            <a href="{{url('inventory-accountants-cards/' . $inventoryhead->inventory_uuid)}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/user_inventory.png')}}" alt=""
                                         class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Asignar Cédula</span>
                                </button>
                            </a>
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
                    <h6>DETALLE GENERAL DEL INVENTARIO</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h5 class="m-b-0 text-black-50">Pasillos</h5></div>
                                <div class="card-body">
                                    <h4 class="card-title">Total de Pasillos</h4>
                                    <h2 class="text-red" id="totalAisles" data-id="{{ $inventoryhead->id }}"></h2>
                                    <a href="{{url('inventoryhead-aisles/' . $inventoryhead->id)}}"
                                       class="btn btn-inverse">Ver Detalle</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h4 class="m-b-0 text-black-50">Cedulas</h4></div>
                                <div class="card-body">
                                    <h4 class="card-title">Total de cédulas</h4>
                                    <h2 class="text-red" id="totalCards" data-id="{{ $inventoryhead->id }}"></h2>
                                    <a href="{{url('inventoryhead-cards/' . $inventoryhead->inventory_uuid)}}"
                                       class="btn btn-inverse">Ver Detalle</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h4 class="m-b-0 text-black-50">Ubicaciones</h4>
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">Total de ubicaciones</h4>
                                    <h2 class="text-red" id="totalLocations" data-id="{{ $inventoryhead->id }}"></h2>
                                    <a href="javascript:void(0)" class="btn btn-inverse">Ver Detalle</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h4 class="m-b-0 text-black-50">Errores</h4>
                                </div>
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="card-title">Errores de conteo</h4>
                                            <h2 class="text-danger font-bold m-b-0" id="totalErrors" data-id="{{ $inventoryhead->id }}"></h2>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="card-title">% de Error</h4>
                                            <h2 class="text-danger font-bold m-b-0" id="errorPercentage" data-id="{{ $inventoryhead->id }}"></h2>
                                        </div>
                                    </div>
                                    <a href="{{url('inventoryhead-errors/' . $inventoryhead->inventory_uuid)}}" class="btn btn-inverse" style="margin-top: 6px">Ver Detalle</a>
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
                <div class="card-header d-flex align-items-center gap-2">
                    <h6 class="mb-0">DETALLE DE CONTEO</h6>
                    <button onclick="reloadtableAisles()" class="btn waves-effect waves-light btn-outline-info btn-md">
                        <img src="{{ asset('/ico/reloadpage.png') }}" alt="" class="img-16 ml-1">
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="inventoryDetailTable" class="table table-bordered table-hover table-condensed" WIDTH="100%">
                                            <thead>
                                            <tr>
                                                <th>Pasillo</th>
                                                <th>Total de Cedulas</th>
                                                <th>Total de Ubicaciones</th>
                                                <th>Ubicaciones Contadas 01</th>
                                                <th>Ubicaciones Faltantes 01</th>
                                                <th>% de Avance 01</th>
                                                <th>Ubicaciones Contadas 02</th>
                                                <th>Ubicaciones Faltantes</th>
                                                <th>% de Avance 02</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <ul class="list-style-none">
                                        <li class="m-t-20">
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-apps display-5 text-muted"></i>
                                                <div class="m-l-10">
                                                    <h5 class="m-b-0">Avance del segundo conteo</h5>
                                                    <span class="font-20">
                                                    <span id="totalCount01Display">0</span> Ubicaciones -
                                                    <span id="progressCount01Percentage">0</span>%
                                                </span>
                                                </div>
                                            </div>
                                            <div class="progress" style="height: 40px !important;" >
                                                <div class="progress" style="height: 40px;">
                                                    <div class="progress-bar bg-success p-4"
                                                         role="progressbar"
                                                         id="progressCount01"
                                                         style="width: 0%; height: 40px !important;"
                                                         aria-valuenow="0"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                        0%
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="m-t-20 m-b-20">
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-apps display-5 text-muted"></i>
                                                <div class="m-l-10">
                                                    <h5 class="m-b-0">Avance del segundo conteo</h5>
                                                    <span class="font-20">
                                                    <span id="totalCount02Display">0</span> Ubicaciones -
                                                    <span id="progressCount02Percentage">0</span>%
                                                </span>
                                                </div>
                                            </div>
                                            <div class="progress" style="height: 40px !important;" >
                                                <div class="progress" style="height: 40px;">
                                                    <div class="progress-bar bg-success p-4"
                                                         role="progressbar"
                                                         id="progressCount02"
                                                         style="width: 0%; height: 40px !important;"
                                                         aria-valuenow="0"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                        0%
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('script')

    <script>
        function loadDashboardStats() {
            $.get('{{ route("getTotalAisles", $inventoryhead->id) }}')
                .done(function (data) {
                    $('#totalAisles').text(data.totalAisles);
                })
                .fail(function () {
                    $('#totalAisles').text('-');
                });

            $.get('{{ route("getTotalCards", $inventoryhead->id) }}')
                .done(function (data) {
                    $('#totalCards').text(data.totalCards);
                })
                .fail(function () {
                    $('#totalCards').text('-');
                });

            $.when(
                $.get('{{ route("getTotalLocations", $inventoryhead->id) }}'),
                $.get('{{ route("getTotalErrors", $inventoryhead->id) }}'),
                $.get('{{ route("getTotalCount01", $inventoryhead->id) }}'),
                $.get('{{ route("getTotalCount02", $inventoryhead->id) }}')
            ).done(function (locationsRes, errorsRes, totalCount01Res, totalCount02Res) {

                const totalLocations = locationsRes[0].totalLocations;
                const totalErrors    = errorsRes[0].totalErrors;
                const totalCount01   = totalCount01Res[0].totalCount01;
                const totalCount02   = totalCount02Res[0].totalCount02;

                // console.log(totalCount01, totalCount02);

                $('#totalLocations').text(totalLocations);
                $('#totalLocations2').text(totalLocations);
                $('#totalErrors').text(totalErrors);

                const percentage = totalLocations > 0
                    ? ((totalErrors / totalLocations) * 100).toFixed(2)
                    : 0;

                const progressCount01 = totalLocations > 0
                    ? ((totalCount01 / totalLocations) * 100).toFixed(2)
                    : 0;
                $('#totalCount01Display').text(totalCount01);
                $('#progressCount01Percentage').text(progressCount01);

                const progressCount02 = totalLocations > 0
                    ? ((totalCount02 / totalLocations) * 100).toFixed(2)
                    : 0;
                $('#totalCount02Display').text(totalCount02);
                $('#progressCount02Percentage').text(progressCount02);

                $('#errorPercentage').text(`${percentage}%`);

                $('#progressCount01')
                    .css('width', `${progressCount01}%`)
                    .attr('aria-valuenow', progressCount01)
                    .text(`${progressCount01}%`);

                $('#progressCount02')
                    .css('width', `${progressCount02}%`)
                    .attr('aria-valuenow', progressCount02)
                    .text(`${progressCount02}%`);

            }).fail(function () {
                $('#totalLocations, #totalLocations2, #totalErrors, #errorPercentage')
                    .text('-');
            });
        }

        function reloadtableAisles() {
            $('#inventoryDetailTable').DataTable().ajax.reload();
            loadDashboardStats();
        }

        $(document).ready(function () {
            loadDashboardStats();
        });
    </script>

    @include('Inventory.InventoryHead.partials.table-details-aisles')


@endsection
