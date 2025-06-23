@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Estatus
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storeStatus/' . $systemschema->schemaid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('systemschemas.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="schema">Esquema </label>
                                    <input type="text" class="form-control" readonly id="schema" name="schema"
                                           maxlength="15"
                                           aria-describedby="schema" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{$systemschema->schemaid}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="nameschema">Nombre Esquema </label>
                                    <input type="text" class="form-control" readonly id="nameschema" name="nameschema"
                                           maxlength="15"
                                           aria-describedby="nameschema" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{$systemschema->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centros de distribución </label>
                                    <select name="centerid" id="centerid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}"> {{$center->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="warehouseid">Bodega </label>
                                    <select name="warehouseid" id="warehouseid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus
                                        @error("statusid")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="statusid" name="statusid"
                                           maxlength="15"
                                           aria-describedby="statusid" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('statusid')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="namestatus">Nombre de Estatus <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="namestatus" name="namestatus"
                                           aria-describedby="namestatus" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('namestatus')}}">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="comment">Comentario </label>
                                    <input type="text" class="form-control" id="comment" name="comment"
                                           aria-describedby="comment" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('comment')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary01">Auxiliar 01</label>
                                    <input type="text" class="form-control" id="auxiliary01" name="auxiliary01"
                                           aria-describedby="auxiliary01" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{old('auxiliary01')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary04">Auxiliary 04</label>
                                    <input type="text" class="form-control" id="auxiliary04" name="auxiliary04"
                                           aria-describedby="auxiliary04" value="{{old('auxiliary04')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="auxiliary05">Auxiliary 05 </label>
                                    <input type="text" class="form-control" id="auxiliary05" name="auxiliary05"
                                           aria-describedby="auxiliary05"
                                           value="{{old('auxiliary05')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-10 col-md-6">
                            <table class="table table-condensed table-bordered table-striped table-list">
                                <thead>
                                    <tr>
                                        <th>Centro de distribución</th>
                                        <th>Bodega</th>
                                        <th>Estatus</th>
                                        <th>Nombre</th>
                                        <th>Comentario</th>
                                        <th>Auxiliar 01</th>
                                        <th>Auxiliar 04</th>
                                        <th>Auxiliar 05</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statusList as $statu)
                                        <tr>
                                            <td>{{$statu->auxiliary02}}</td>
                                            <td>{{$statu->auxiliary03}}</td>
                                            <td>{{$statu->statusid}}</td>
                                            <td>{{$statu->name}}</td>
                                            <td>{{$statu->comment}}</td>
                                            <td>{{$statu->auxiliary01}}</td>
                                            <td>{{$statu->auxiliary04}}</td>
                                            <td>{{$statu->auxiliary05}}</td>
                                            <td>
                                                {{-- Modificar estatus--}}
                                                <a href="" data-target="#modal-updatestatus-{{$statu->statusid}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/typemovements.png')}}" alt=""
                                                         class="img-16 ml-2">
                                                </a>
                                                @include('Configuration.SystemSchemas.updatestatus')
                                                {{-- Eliminar estatus--}}
                                                <a href="" data-target="#modal-deletestatus-{{$statu->statusid}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/destroy2.png')}}" alt=""
                                                         class="img-16 ml-2">
                                                </a>
                                                @include('Configuration.SystemSchemas.deletestatus')
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $(".table-list").DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "400px",
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],
                buttons: [
                    {
                        "extend": "colvis",
                        "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                        columns: ':not(:first):not(:last)'
                    },
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
        })
    </script>
@endsection

