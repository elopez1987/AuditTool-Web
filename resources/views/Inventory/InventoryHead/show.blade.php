@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Inventario - {{$inventoryhead->id}} - {{$inventoryhead->name}}</h4>
                </div>
                <div class="card-body">
                    <a href="{{url('inventoryheads')}}">
                        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                            <img src="{{asset('/ico/back.png')}}" alt="" class="img-16 ml-1">
                            <span class="text-button ml-3 mr-3"> Salir</span>
                        </button>
                    </a>
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
                            <div class="card border-warning">
                                <div class="card-header bg-warning">
                                    <h4 class="m-b-0 text-black-50">Pasillos</h4></div>
                                <div class="card-body">
                                    <h3 class="card-title">Total de Pasillos</h3> <h2 class="text-red">1,900</h2>
                                    <a href="{{url('inventoryhead-aisles/' . $inventoryhead->id)}}" class="btn btn-inverse">Ver Detalle</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning">
                                    <h4 class="m-b-0 text-black-50">Cedulas</h4></div>
                                <div class="card-body">
                                    <h3 class="card-title">Total de cédulas</h3> <h2 class="text-red">1,900</h2>
                                    <a href="{{url('inventoryhead-cards/' . $inventoryhead->id)}}" class="btn btn-inverse">Ver Detalle</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning">
                                    <h4 class="m-b-0 text-black-50">Ubicaciones</h4></div>
                                <div class="card-body">
                                    <h3 class="card-title">Total de ubicaciones</h3> <h2 class="text-red">1,900</h2>
                                    <a href="javascript:void(0)" class="btn btn-inverse">Ver Detalle</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning">
                                    <h4 class="m-b-0 text-black-50">Existencia de Inventario</h4></div>
                                <div class="card-body">
                                    <h3 class="card-title">Validación entre sistemas</h3>
{{--                                    <h2 class="text-red">1,900</h2>--}}
                                    <a href="javascript:void(0)" class="btn btn-inverse">Ver Detalle</a>
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
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Pasillo</th>
                                                <th>Total de Cedulas</th>
                                                <th>Total de Ubicaciones</th>
                                                <th>Ubicaciones Contadas</th>
                                                <th>Ubicaciones Faltantes</th>
                                                <th>% de Avance</th>
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
                                                    <h5 class="m-b-0">Total de ubicaciones</h5>
                                                    <span class="font-20">5297 Ubicaciones</span></div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width: 100%"
                                                     aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li class="m-t-20">
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-apps display-5 text-muted"></i>
                                                <div class="m-l-10">
                                                    <h5 class="m-b-0">Avance del primer conteo</h5>
                                                    <span class="font-20">5297 Ubicaciones --  100 %</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-orange" role="progressbar"
                                                     style="width: 100%"
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
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                     style="width: 100%"
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
                    <div class="col-lg-8">
                        <div class="card-header">
                            <h4>ERRORES DE CONTEO</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>Pasillo</th>
                                    <th>Cedula</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Descripción</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-header">
                            <h4>TOTAL DE ERRORES</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="w-75 c-cost">
                                    <div class="d-inline-block align-middle">
                                        <i class="mdi mdi-wallet text-success display-5 "></i>
                                    </div>
                                    <div class="d-inline-block align-middle m-l-10">
                                        <span class="text-muted font-14">Errores de conteo</span>
                                        <h2 class="text-danger font-bold m-b-0">0</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-40">
                                <div class="w-75 c-cost">
                                    <div class="d-inline-block align-middle">
                                        <i class="mdi mdi-wallet text-success display-5 "></i>
                                    </div>
                                    <div class="d-inline-block align-middle m-l-10">
                                        <span class="text-muted font-14">% de Error</span>
                                        <h2 class="text-danger font-bold m-b-0">
                                            0.00
                                            %</h2>
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

@section('script')

@endsection
