@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Reporte de descargas
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('generateFileToExportReceipt')}}">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <a href="" class=" m-1">
                                    <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md "
                                            data-toggle="tooltip" data-placement="top"
                                            title="Regresar a la pagina principal"
                                    >
                                        <img src="{{asset('/ico/adexportar.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Exportar</span>
                                    </button>
                                </a>
                                <a href="{{route('home')}}" class=" m-1">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-0">
                        <div class="row mt-4">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centro de distribución </label>
                                    <select name="centerid" class="form-control select2">
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}"> {{$center->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="warehouseid">Bodega </label>
                                    <select name="warehouseid" class="form-control select2">
                                        @foreach($warehouses as $model)
                                            <option value="{{$model->warehouseid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="type">Nombre de reporte <span style="color: red">*</span></label>
                                    <select name="type" class="form-control select2">
                                        <option value="RG">Reporte general de descargas</option>
                                        <option value="IN">Reporte de incidencia en descargas</option>
                                        <option value="PA">Reporte de Lpns en recibo</option>
                                        <option value="PR">Reporte de arribo de unidades</option>
                                        <option value="TR">Reporte de tarimas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workdayin">Fecha de inicio
                                        @error("workdayin")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('workdayin') is-invalid @enderror"
                                           required id="workdayin" name="workdayin"
                                           aria-describedby="workdayin" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('workdayin')}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workdayout">Fecha final
                                        @error("workdayout")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('workdayout') is-invalid @enderror"
                                           required id="workdayout" name="workdayout"
                                           aria-describedby="workdayout" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('workdayout')}}"
                                    >
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
