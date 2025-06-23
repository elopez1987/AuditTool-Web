@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Parametrizar Sku
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventorypartnumbersaddparameters/' . $model->id)}}" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventorypartnumbers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Sku</label>
                                    <input type="text" class="form-control" readonly id="name" name="name"
                                           value="{{$model->prtnum}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Descripción</label>
                                    <input type="text" class="form-control" readonly id="name" name="name"
                                           value="{{$model->lngdsc}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-striped table-bordered table-condensed tablegeneral">
                                    <thead>
                                    <tr>
                                        <th width="5%" class="bg-inverse text-white">Secuencia</th>
                                        <th class="bg-inverse text-white"></th>
                                        <th class="bg-inverse text-white">Parametro</th>
                                        <th width="50%" class="bg-inverse text-white">Descripción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($parameters as $parameter)
                                            <tr>
                                                <td width="5%">{{$loop->iteration}}</td>
                                                <td class="switch" style="width: 70px;">
                                                    <input
                                                        type="checkbox"
                                                        value="{{$parameter->statusid}}"
                                                        @if(!empty($model->configurationflag))
                                                            @foreach ($model->configurationflag as $check)
                                                                @if ($check == $parameter->statusid)
                                                                    checked
                                                                @endif
                                                          @endforeach
                                                        @endif
                                                        name="parameter[]"
                                                        data-size="mini" data-on-color="success" data-off-color="danger"
                                                        data-on-text="<i class='ti ti-check'></i>"
                                                        data-off-text="<i class='ti ti-close'></i>"
                                                    >
                                                </td>
                                                <td>{{$parameter->statusid}}</td>
                                                <td width="50%">{{$parameter->name}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
    <script>
        var oTable =   $(".tablegeneral").DataTable({
            "paging"      : true,
            "searching"  : true,
            "ordering"    : true,
            "serverSide" :false,
            'colReorder': true,
            "dom": '<"top"B>flrt<"bottom"p><"clear"i>',
            "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],

            buttons: [
                {
                    "extend": "colvis",
                    "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Mostar/Ocultar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                    columns: ':not(:first):not(:last)'
                },
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem !important;'> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Excel</span>",
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
    </script>


@endsection

