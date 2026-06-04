@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Inventario - {{$inventoryhead->id}} - {{$inventoryhead->name}}</h4>
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
                                    <span class="text-button ml-3 mr-3"> Validación</span>
                                </button>
                            </a>
                            <a href="{{url('inventoryheads')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/importdata.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Tareas 3er Conteo</span>
                                </button>
                            </a>
                            <a href="{{url('inventoryheads')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/clouddown_icon-icons.com_54405.png')}}" alt=""
                                         class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Descargar Cédulas</span>
                                </button>
                            </a>
                            <a href="{{url('inventoryheads')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/authorizetransportentry.png')}}" alt=""
                                         class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Crear Facilitador</span>
                                </button>
                            </a>
                            <a href="{{url('inventoryheads')}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/adduser.png')}}" alt=""
                                         class="img-16 ml-1">
                                    <span class="text-button ml-3 mr-3"> Crear Contadores</span>
                                </button>
                            </a>
                            <a href="{{url('inventoryheads')}}">
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
                    <h4>DETALLE GENERAL DEL INVENTARIO</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card border-info">
                                <div class="card-header bg-info">
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
                                <div class="card-header bg-info">
                                    <h4 class="m-b-0 text-black-50">Cedulas</h4></div>
                                <div class="card-body">
                                    <h4 class="card-title">Total de cédulas</h4>
                                    <h2 class="text-red" id="totalCards" data-id="{{ $inventoryhead->id }}"></h2>
                                    <a href="{{url('inventoryhead-cards/' . $inventoryhead->id)}}"
                                       class="btn btn-inverse">Ver Detalle</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card border-info">
                                <div class="card-header bg-info">
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
                                <div class="card-header bg-info">
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
                                    <a href="javascript:void(0)" class="btn btn-inverse" style="margin-top: 6px">Ver Detalle</a>
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
                    <h4>DETALLE DE CONTEO</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="inventoryDetailTable" class="table table-bordered" WIDTH="100%">
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
                                <div class="card-body">
                                    <ul class="list-style-none">
                                        <li class="m-t-20">
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-apps display-5 text-muted"></i>
                                                <div class="m-l-10">
                                                    <h5 class="m-b-0">Avance del primer conteo</h5>
                                                    <span class="font-20">5297 Ubicaciones --  100 %</span>
                                                </div>
                                            </div>
                                            <div class="progress" style="height: 40px !important;">
                                                <div class="progress-bar bg-success p-4" role="progressbar"
                                                     style="width: 100%;height: 40px !important;"
                                                     aria-valuenow="100"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="m-t-20 m-b-20">
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-apps display-5 text-muted"></i>
                                                <div class="m-l-10">
                                                    <h5 class="m-b-0">Avance del segundo conteo</h5>
                                                    <span class="font-20">5297 Ubicaciones -- 100 %</span>
                                                </div>
                                            </div>
                                            <div class="progress" style="height: 40px !important;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width: 100%;height: 40px !important;"
                                                     aria-valuenow="100"
                                                     aria-valuemin="0" aria-valuemax="100">
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-header">
                            <h4>ERRORES DE CONTEO</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-condensed" id="inventoryDetailTableErrors"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Pasillo</th>
                                    <th>Cedula</th>
                                    <th>Ubciación</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function () {
            $.get('{{ route("getTotalAisles", $inventoryhead->id) }}')
                .done(function (data) {
                    $('#totalAisles').text(data.totalAisles);
                })
                .fail(function (error) {
                    $('#totalAisles').text('-');
                });

            $.get('{{ route("getTotalCards", $inventoryhead->id) }}')
                .done(function (data) {
                    $('#totalCards').text(data.totalCards);
                })
                .fail(function (error) {
                    $('#totalCards').text('-');
                });

            $.when(
                $.get('{{ route("getTotalLocations", $inventoryhead->id) }}'),
                $.get('{{ route("getTotalErrors", $inventoryhead->id) }}')
            ).done(function (locationsRes, errorsRes) {

                const totalLocations = locationsRes[0].totalLocations;
                const totalErrors = errorsRes[0].totalErrors;

                $('#totalLocations').text(totalLocations);
                $('#totalLocations2').text(totalLocations);
                $('#totalErrors').text(totalErrors);

                const percentage = totalLocations > 0
                    ? ((totalErrors / totalLocations) * 100).toFixed(2)
                    : 0;

                $('#errorPercentage').text(`${percentage}%`);

            }).fail(function () {
                $('#totalLocations, #totalLocations2, #totalErrors, #errorPercentage')
                    .text('-');
            });

        });
    </script>

    @include('Inventory.InventoryHead.partials.table-details-aisles')
    @include('Inventory.InventoryHead.partials.table-locations-errors')

@endsection
