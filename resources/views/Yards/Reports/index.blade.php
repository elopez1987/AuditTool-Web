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
                                    <label for="type">Seleccione reporte a exportar:</label>
                                    <select name="type" class="form-control select2" required>
                                        <option value="" selected></option>
                                        @can('reportunittimes')
                                            <option value="RTE">Reporte de unidades</option>
                                        @endcan
                                        @can('reportunitevidences')
                                            <option value="REV">Reporte de evidencias</option>
                                        @endcan
                                        @can('reportmonitortravel')
                                            <option value="RCI">Reporte de circuito</option>
                                        @endcan
                                        @can('reportunitor')
                                            <option value="RCP">Reporte de entradas planta</option>
                                        @endcan
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-4">
                               <span class="font-12">Lineas de transporte</span>
                                <select multiple="multiple" size="15" class="duallistbox-with-filter" name="carrierid[]" required>
                                    @foreach($carriers as $carrier)
                                        <option value="{{$carrier->id}}"> {{$carrier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-4">
                                <span class="font-12">Conceptos de entrada</span>
                                <select multiple="multiple" size="15" class="duallistbox-with-filter" name="reasonid[]" required>
                                    @foreach($reasons as $model)
                                        <option value="{{$model->id}}"> {{$model->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-4">
                                <span class="font-12">Origen de entrada</span>
                                <select multiple="multiple" size="15" class="duallistbox-with-filter" name="unittypeid[]" required>
                                    @foreach($origins as $model)
                                        <option value="{{$model->id}}"> {{$model->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workdayin">F.Inicio</label>
                                    <input type="text" class="form-control workday" required id="workdayin"
                                           name="workdayin">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workdayout">F.Final</label>
                                    <input type="text" class="form-control workday" required id="workdayout"
                                           name="workdayout">
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
    <link rel="stylesheet" href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script>
        $('.duallistbox-with-filter').bootstrapDualListbox({
            nonSelectedListLabel: 'No hay registros selecionados:',
            selectedListLabel: 'Seleccionado',
            preserveSelectionOnMove: 'Mover',
            moveOnSelect: false,
            nonSelectedListLabel: 'Disponibles',
            selectedListLabel: 'Seleccionados',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            moveAllLabel: 'Mover todos',
            removeAllLabel: 'Remover todos',
            infoText: 'Mostrando todo {0}',
            infoTextEmpty: 'Lista vacía',
            infoTextFiltered: '<span class="label label-warning">Filtrado</span> {0} de {1}',
            filterPlaceHolder: 'Filtrar',
            filterTextClear: 'Mostrar todo'
        });
        $(function () {
            $('.moveall i').removeClass().addClass('fas fa-angle-right');
            $('.removeall i').removeClass().addClass('fas fa-angle-left');
            $('.move i').removeClass().addClass('fas fa-angle-right');
            $('.remove i').removeClass().addClass('fas fa-angle-left');
        });
    </script>

@endsection
