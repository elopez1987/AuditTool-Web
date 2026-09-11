@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Parametrizar Centro de distribución
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('systemparametersapp/' . $center->centerid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @can('systemcenterdistributions.parametrizarapp')
                                    <a href="">
                                        @include('buttons.save')
                                    </a>
                                @endcan
                                <a href="{{route('systemcenterdistributions.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Centro de distribución </label>
                                    <input type="text" class="form-control" id="name" name="name" maxlength="100"
                                           aria-describedby="auxiliary05" value="{{$center->name}}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Esquema</label>
                                    <input type="text" class="form-control"  value="APP" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre de parametro</label>
                                    <input type="text" class="form-control" value="PARAMETROS DE APP" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-10">
                                <table class="table  table-bordered table-condensed tabledata">
                                    <thead>
                                    <tr>
                                        <th >Secuencia</th>
                                        <th >Activar/Desactivar</th>
                                        <th>Parametro</th>
                                        <th>Descripción</th>
                                        <th>Comentario</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($parameters as $parameter)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td style="width: 100px;" class="switch">
                                                <input
                                                    type="checkbox"
                                                    value="{{$parameter->statusid}}"
                                                    @if(!empty($center->auxiliary05))
                                                        @foreach ($center->auxiliary05 as $check)
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
                                            <td>{{$parameter->name}}</td>
                                            <td>{{$parameter->comment}}</td>
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
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".tabledata").DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "700px",
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[100, 150, 200, 250, -1], [100, 150, 200, 250, "Todos"]],
                buttons: [
                    {
                        "extend": "colvis",
                        "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Mostar/Ocultar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                        columns: ':not(:first):not(:last)'
                    },
                    {
                        "extend": "csv",
                        "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Csv</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                    },
                    {
                        "extend": "excel",
                        "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Excel</span>",
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
