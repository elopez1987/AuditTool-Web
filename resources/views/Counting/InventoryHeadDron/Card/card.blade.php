@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de cédula -- {{$card->cardid}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{url('detailcardscount/' . $inventory->id)}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                                    <span style="font-size: 11px;"> Regresar</span>
                                </button>
                            </a>
                            @if(empty($card->finishdate02))
                                <a id="closeCedula">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/close.png')}}" alt="" class="img-16 ml-1">
                                        <span style="font-size: 0.675rem;"> Cerrar cedula</span>
                                    </button>
                                </a>
                            @endif
                        </div>
                    </div>
                    <hr class="mb-2">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="inventoryid">Número de inventario:</label>
                                <input type="text" class="form-control" readonly value="{{$inventory->id}}"
                                       id="inventoryid" name="inventoryid">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="description">Número de inventario:</label>
                                <input type="text" class="form-control" readonly value="{{$inventory->description}}"
                                       id="description" name="description">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="cardid">Cédula:</label>
                                <input type="text" class="form-control" readonly value="{{$card->cardid}}"
                                       id="cardid" name="cardid">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-striped table-condensed tablegeneral">
                                <thead>
                                <tr>
                                    <th>Area</th>
                                    <th>Pasill0</th>
                                    <th>Cédula</th>
                                    <th>Ubicacion</th>
                                    <th>Número de parte</th>
                                    <th>Descripción</th>
                                    <th>Cantidad DRON</th>
                                    <th>Existencia WMS</th>
                                    <th>Piloto</th>
                                    <th>Fecha de registro</th>
                                    <th>Ubicación correcta</th>
                                    <th>Estatus de ubicación</th>
                                    <th>Validar ubicación</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($locations as $location)
                                    <tr>
                                        <td>{{$location->areaid}}</td>
                                        <td>{{$location->aisleid}}</td>
                                        <td>{{$location->cardid}}</td>
                                        <td>{{$location->locationid}}</td>
                                        <td>
                                            @if(!empty($location->partnumberid) || $location->partnumberid != '00')
                                                <label class="label label-{{$location->partnumberid == $location->partnumberstock ? 'green text-black' :'red text-white'}}">
                                                    {{$location->partnumberid}}
                                                </label>
                                            @endif
                                        </td>
                                        <td>{{$location->description}}</td>
                                        <td>{{round($location->quantity,3)}}</td>
                                        <td>
                                            @if(!empty($location->lpns))
                                                <label
                                                    class="label label-{{$location->quantity == $location->lpns ? 'green text-black' : 'red'}}">
                                                    {{ $location->lpns ? round($location->lpns,3) : ''}}
                                                </label>
                                             @endif
                                        </td>
                                        <td>{{$location->usercount}}</td>
                                        <td>{{$location->countdate}}</td>
                                        <td class="label label-{{$location->error == 'N' ? 'green text-black' : 'red text-white'}}">
                                            @if($location->error == 'N')
                                                <label class="label label-green text-black">CORRECTA</label>
                                            @elseif($location->error == 'S')
                                                <label class="label label-red text-white">ERROR</label>
                                            @endif
                                        </td>
                                        <td>
                                            <label
                                                class="label label-{{$location->statusid == 'C' ? 'green text-black' :'red text-white'}}">
                                                {{$location->status}}
                                            </label>
                                        </td>
                                        <td>
                                            <a href="{{url('detaillocationdronfiscal/' . $location->recordid )}}"
                                               data-toggle="tooltip" data-placement="top"
                                               title="Detalle de ubicación">
                                                <img src="{{asset('/ico/viewdetails.png')}}" alt="" class="img-16 ml-1">
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Area</th>
                                    <th>Pasill0</th>
                                    <th>Cédula</th>
                                    <th>Ubicacion</th>
                                    <th>Número de parte</th>
                                    <th>Descripción</th>
                                    <th>Cantidad DRON</th>
                                    <th>Cantidad WMS</th>
                                    <th>Piloto</th>
                                    <th>Fecha de registro</th>
                                    <th>Ubicación correcta</th>
                                    <th>Estatus de ubicación</th>
                                    <th>Validar ubicación</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Totales</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="m-r-10"><span class="text-green display-5"><i
                                            class="mdi mdi-check text-green"></i></span></div>
                                <div><span style="font-size: 20px;">Ubicaciones contadas</span>
                                    <h3 class="font-weight-bold m-b-0">
                                        @foreach($totalForStatus as $item)
                                            @if($item->name == 'CONTADO')
                                                {{$item->total}}
                                            @endif
                                        @endforeach
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="m-r-10"><span class="text-red display-5"><i
                                            class="mdi mdi-close"></i></span></div>
                                <div><span style="font-size: 20px">Ubicaciones Faltantes</span>
                                    <h3 class="font-bold m-b-0">
                                        @foreach($totalForStatus as $item)
                                            @if($item->name == 'FALTANTE')
                                                {{$item->total}}
                                            @endif
                                        @endforeach
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        function reloadtable() {
            $('#table-cards').DataTable().ajax.reload();
        }
    </script>

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#closeCedula').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        inventoryid: {{$inventory->id}},
                        cardid: "{{$card->cardid}}",
                    },
                    url: '{!! route('closeCardForInventoryDron') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        Swal.fire({
                            type: 'success',
                            title: `Cédula cerrada con exito.`,
                            width: 350,
                        });
                    },
                });
            });
        });
    </script>

    <script>
        var oTable = $(".tablegeneral").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "sScrollY": "450px",
            "sScrollX": true,
            "processing": true,
            "responsive": true,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[50, 100, 150, 200, 250, 300], [50, 100, 150, 200, 250, 300]],
            initComplete: function () {
                this.api()
                    .columns()
                    .every(function () {
                        let column = this;
                        let title = column.footer().textContent;
                        let input = document.createElement('input');
                        input.placeholder = title;
                        column.footer().replaceChildren(input);
                        input.addEventListener('keyup', () => {
                            if (column.search() !== this.value) {
                                column.search(input.value).draw();
                            }
                        });
                    });
            },
            "columnDefs": [
                {"width": "100px", "targets": 0},
                {"width": "100px", "targets": 1},
                {"width": "100px", "targets": 2},
                {"width": "100px", "targets": 3},
                {"width": "100px", "targets": 4},
                {"width": "250px", "targets": 5},
                {"width": "100px", "targets": 6},
                {"width": "100px", "targets": 7},
                {"width": "100px", "targets": 8},
                {"width": "120px", "targets": 9},
                {"width": "120px", "targets": 10},
                {"width": "120px", "targets": 11},
                {"width": "100px", "targets": 12},
            ],
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
    </script>

@endsection

