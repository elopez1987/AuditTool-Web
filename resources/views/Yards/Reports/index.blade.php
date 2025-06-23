@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Reportes del módulo de patios
                </div>
                <div class="card-body">
                    <form method="GET" action="{{url('exportreportyards')}}">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <a href="" class=" m-1">
                                    <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md "
                                            data-toggle="tooltip" data-placement="top"
                                            title="Regresar a la pagina principal"
                                    >
                                        <img src="{{asset('/ico/adexportar.png')}}" alt="" class="img-16">
                                        <span class="text-button "> Exportar</span>
                                    </button>
                                </a>
                                <a href="{{route('menuyard')}}" class=" m-1">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-0">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
                                    <select name="centerid" id="centerid" class="form-control select2">
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}"> {{$center->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="type">Seleccione reporte a exportar:</label>
                                    <select name="type" class="form-control select2" required>
                                        <option value="" selected></option>
                                        @can('reportunittimes')
                                            <option value="RTE">Reporte de unidades</option>
                                        @endcan
                                        @can('reportmonitortravel')
                                            <option value="RCI">Reporte de circuito</option>
                                        @endcan
                                        @can('reportunitevidences')
                                            <option value="REV">Reporte de evidencias</option>
                                        @endcan
                                        @can('reportunitor')
                                            <option value="RCP">Reporte de entradas OR</option>
                                        @endcan
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workdayin">F.Inicio</label>
                                    <input type="text" class="form-control workday"  required id="workdayin" name="workdayin">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workdayout">F.Final</label>
                                    <input type="text" class="form-control workday" required  id="workdayout" name="workdayout">
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
