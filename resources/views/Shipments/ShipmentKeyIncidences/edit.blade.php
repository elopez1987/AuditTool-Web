@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Número de embarque: <span
                        style="font-size: 18px;color: red;font-weight: 700;text-align: left;margin-right: 100px"> {{ $order->shipmentid}}</span>
                    Cliente: <span
                        style="font-size: 13px;color: red;font-weight: 400;text-align: left;margin-right: 100px"> {{ $order->customer ? $order->customer->name : ''}}</span>
                </div>
                <div class="card-body ">
                    <form method="POST" action="{{url('storagekeyincidencesopetations/' . $order->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagekeyincidencesopetations.index')}}" class="ml-4">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-2">
                        <div class="card-input">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table width="100%" class="table table-condensed table-bordered table-striped tablegeneral">
                                        <thead>
                                        <th class="hide"></th>
                                        <th>Factura</th>
                                        <th>IDH</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Unidad de medida</th>
                                        <th>Motivo</th>
                                        <th>Concepto</th>
                                        <th>Comentario</th>
                                        <th>Estatus</th>
                                        <th>Responsable</th>
                                        <th>Motivo de Firma</th>
                                        </thead>
                                        <tbody>
                                        @foreach($details as $detail)
                                            <tr>
                                                <td class="hide">
                                                    <input type="number" value="{{$detail->id}}" name="id[]">
                                                </td>
                                                <td>{{$detail->delivery}}</td>
                                                <td>{{$detail->partnumberid}}</td>
                                                <td>{{$detail->description}}</td>
                                                <td>{{$detail->quantity}}</td>
                                                <td>{{$detail->unitmeasure}}</td>
                                                <td>{{$detail->motivo}}</td>
                                                <td>{{$detail->code}}</td>
                                                <td>{{$detail->comment}}</td>
                                                <td>{{$detail->status}}</td>
                                                <td>{{$detail->responsable}}</td>
                                                <td>
                                                    <select name="key[]" class="form-control select2">
                                                        @foreach($motivos as $item)
                                                            <option
                                                                value="{{$item->categoryincdetailid}}">{{$item->description}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var oTable = $(".tablegeneral").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "sScrollY": "350px",
            "sScrollX": true,
            "processing": true,
            "serverSide": false,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "dom": '<"top"B>lrt<"bottom"p><"clear"i>',
            "lengthMenu": [[5, 10, 30, 50, 100, 150, 200, 250, -1], [5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],

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
            "columnDefs": [
                {"width": "100px", "targets": 0},
                {"width": "150px", "targets": 1},
                {"width": "150px", "targets": 2},
                {"width": "280px", "targets": 3},
                {"width": "120px", "targets": 4},
                {"width": "120px", "targets": 5},
                {"width": "120px", "targets": 6},
                {"width": "120px", "targets": 7},
                {"width": "180px", "targets": 8},
                {"width": "150px", "targets": 9},
                {"width": "150px", "targets": 10},
                {"width": "150px", "targets": 11},
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

