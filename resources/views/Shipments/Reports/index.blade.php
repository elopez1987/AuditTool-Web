@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Reportes del módulo de embarques
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('exportreportshipments')}}">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <a href="" class=" m-1">
                                    <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md "
                                            data-toggle="tooltip" data-placement="top"
                                            title="Regresar a la pagina principal"
                                    >
                                        <img src="{{asset('/ico/adexportar.png')}}" alt="" class="img-16">
                                        <span style="font-size: 0.675rem  !important;"> Exportar Reporte</span>
                                    </button>
                                </a>
                                <a href="{{route('menushipment')}}" class=" m-1">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-0">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centros de distribución <span
                                            style="color: red">*</span></label>
                                    <select name="centerid" id="centerid" class="form-control select2">
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}"> {{$center->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                                    <select name="warehouseid" id="warehouseid" class="form-control select2">
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="type">Seleccione reporte a exportar:</label>
                                    <select name="type" class="form-control select2" required>

                                        <option value="" selected></option>
                                        @can('reportgeneralshipments')
                                            <option value="RPG">Reporte de embarques</option>
                                        @endcan
                                        @can('reportshipmentevents')
                                            <option value="RPE">Reporte de eventos</option>
                                        @endcan
                                        @can('reportshipmentseals')
                                            <option value="RPS">Reporte de sellos</option>
                                        @endcan
                                        @can('reportshipmentsphotos')
                                            <option value="RPF">Reporte de fotos</option>
                                        @endcan
                                        @can('reportcheckinverse')
                                            <option value="RIP">Reingreso de producto</option>
                                        @endcan
                                        @can('reportbitacorainverse')
                                            <option value="BLI">Bitacora Log. Inversa</option>
                                        @endcan
                                        @can('reportlpncheckers')
                                            <option value="LPV">Lpns Verificados</option>
                                        @endcan
                                        @can('reportlpncheckersdetail')
                                            <option value="LDV">Detalle de verificación</option>
                                        @endcan
                                        @can('reportfacturas')
                                            <option value="REC">Reporte de facturas cargadas</option>
                                        @endcan
                                        @can('reportlpncheckersforshipments')
                                            <option value="RVS">Reporte de verificacón por embarque</option>
                                        @endcan
                                        @can('reportincidence')
                                            <option value="INC">Reporte de Incidencia</option>
                                        @endcan
                                        @can('reportpod')
                                            <option value="POD" selected>Reporte de PODs</option>
                                        @endcan
                                        @can('reportplt')
                                            <option value="PLT" selected>Reporte de Pallets</option>
                                        @endcan
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workdayin">F.Inicio</label>
                                    <input type="text" class="form-control" id="workdayin" name="workdayin" required>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workdayout">F.Final</label>
                                    <input type="text" class="form-control" id="workdayout" name="workdayout" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('script')
    <script>
        $('#workdayin').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
        $('#workdayout').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
    </script>
@endsection
