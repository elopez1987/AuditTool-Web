@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Subir facturas
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('orderdeliveries.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="hr-line">
                    <div class="row mt-3 mb-4">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Embarque</label>
                                <input type="text" class="form-control" readonly value="{{$orderDelivery->shipmentid}}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Cliente</label>
                                <input type="text" class="form-control" readonly
                                       value="{{$orderDelivery->customer->name}}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Línea de Transporte</label>
                                <input type="text" class="form-control" readonly
                                       value="{{$orderDelivery->carrier->name}}">
                            </div>
                        </div>
                    </div>
                    <hr class="hr-line">
                    <div class="row">
                        <div class="col-lg-8">
                            <form method="POST" action="{{url('orderdeliveriesadddelivery/' . $orderDelivery->id )}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-lg-3">
                                        <a href="">
                                            <button type="submit"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md btn-block">
                                                <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                                <span style="font-size: 0.675rem "> Guardar</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                <hr class="mt-3">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="billingdocument">Número de factura
                                                <span style="color: red">*</span>
                                                @error("billingdocument")
                                                <span class="message-error">{{$message}}</span>
                                                @enderror
                                            </label>
                                            <input type="text"
                                                   class="form-control numeric @error('billingdocument') is-invalid @enderror"
                                                   id="billingdocument"
                                                   name="billingdocument"
                                                   required
                                                   onKeyUp=this.value=this.value.toUpperCase();
                                                   value="{{old('billingdocument')}}"
                                            >
                                            @error('billingdocument')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-lg-8">
                            <table class="table table-condensed table-bordered table-hover table-striped tablegeneral">
                                <thead>
                                <th>Embarque</th>
                                <th>Cliente</th>
                                <th>Factura</th>
                                </thead>
                                <tbody>
                                @foreach($orderDelivery->billings as $billing)
                                    <tr>
                                        <td>{{$billing->order ? $billing->order->shipmentid : ''}}</td>
                                        <td>{{$billing->order->customer ? $billing->order->customer->name : ''}}</td>
                                        <td>{{$billing->billingdocument}}</td>
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

@section('css')

@endsection

@section('script')
    <script>
        var oTable =   $(".tablegeneral").DataTable({
            "paging"      : true,
            "searching"  : true,
            "ordering"    : true,
            "serverSide" :false,
            'colReorder': true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
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
