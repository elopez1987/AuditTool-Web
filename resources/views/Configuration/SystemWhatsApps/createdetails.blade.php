@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar detalle de WhatsApp
                </div>
                <div class="card-body">

                    <form method="POST" action="{{url('storenotificationdetail/' . $systemwhatsapp->whatsappid)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @can('systemwhatsapps.savedetailwth')
                                    <a href="">
                                        @include('buttons.save')
                                    </a>
                                @endcan
                                <a href="{{route('systemwhatsapps.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Descripción </label>
                                    <input type="text" class="form-control" disabled id="description" name="description"
                                           maxlength="100"
                                           aria-describedby="description" value="{{$systemwhatsapp->description}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centros de distribución <span
                                            style="color: red">*</span></label>
                                    <select name="centerid" class="form-control select2" required>
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }} > {{$center->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Bodega<span style="color: red">*</span></label>
                                    <select name="warehouseid" class="form-control select2">
                                        @foreach($warehouses as $model)
                                            <option value="{{$model->warehouseid}}" {{ old('warehouseid') == $model->warehouseid ? 'selected' : '' }} > {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="namecontact">Nombre de Contacto o grupo</label>
                                    <input type="text" class="form-control @error('namecontact') is-invalid @enderror" required id="namecontact" name="namecontact"
                                           maxlength="100"
                                           aria-describedby="namecontact" value="{{old('namecontact')}}">
                                    @error('namecontact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="phonenumber">Teléfono</label>
                                    <input type="text" class="form-control numeric @error('phonenumber') is-invalid @enderror" required id="phonenumber"
                                           name="phonenumber" maxlength="10" minlength="10"
                                           aria-describedby="phonenumber" value="{{old('phonenumber')}}">
                                    @error('phonenumber')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="phonenumber01">Valor 01</label>
                                    <input type="text" class="form-control @error('phonenumber01') is-invalid @enderror"  id="phonenumber01"
                                           name="phonenumber01" maxlength="10"
                                           aria-describedby="phonenumber01" value="{{old('phonenumber01')}}">
                                    @error('phonenumber01')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>


                    <div class="row mt-4">
                        <div class="col-10">
                            <table class="table table-condensed table-bordered table-striped tablegeneral">
                                <thead>
                                <tr>
                                    <th width="5%" class="bg-inverse text-white">Secuencia</th>
                                    <th width="20%" class="bg-inverse text-white">C. Distribución</th>
                                    <th width="20%" class="bg-inverse text-white">Bodega</th>
                                    <th width="30" class="bg-inverse text-white">Nombre de Contacto o grupo</th>
                                    <th width="20" class="bg-inverse text-white">Teléfono</th>
                                    <th width="20" class="bg-inverse text-white">Valor 01</th>
                                    <th width="5%" class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$detail->center->name}}</td>
                                        <td>{{$detail->warehouse->name}}</td>
                                        <td>{{$detail->namecontact}}</td>
                                        <td>{{$detail->phonenumber}}</td>
                                        <td>{{$detail->phonenumber01}}</td>
                                        <td>
                                            @can('systemwhatsapps.deletedetailwth')
                                                <a href="" data-target="#modal-delete-{{$detail->recordid}}"
                                                   data-toggle="modal">
                                                    <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16">
                                                </a>
                                                @include('Configuration.SystemWhatsApps.partials.deletewhatsappcontact')
                                            @endcan
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
    @include('layouts.Partials.Errors')
@endsection

@section('script')
    <script>
        var oTable =   $(".tablegeneral").DataTable({
            "paging"      : true,
            "searching"  : true,
            "ordering"    : true,
            "serverSide" :false,
            'colReorder': true,
            "dom": '<"top"Bf>lrt<"bottom"p><"clear"i>',
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

