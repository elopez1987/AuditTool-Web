@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Horario de descarga
                </div>
                <div class="card-body">

                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-10">
                                        @can('receiptasn.create')
                                            <a href="{{route('receiptasn.create')}}">
                                                @include('buttons.add')
                                            </a>
                                        @endcan
                                        <a href="">
                                            @include('buttons.search')
                                        </a>
                                        <a>
                                            @include('buttons.reloadajax')
                                        </a>
                                        @can('receiptasn.addreceipt')
                                            <a href="" data-target="#modal-add" data-toggle="modal">
                                                <button type="submit"
                                                        class="btn waves-effect waves-light btn-outline-info btn-md">
                                                    <img src="{{asset('/ico/calendar.png')}}" alt=""
                                                         class="img-16 ml-1">
                                                    <span style="font-size:0.675rem !important;;"> Programar</span>
                                                </button>
                                            </a>
                                            @include('Receipt.ScheduleReceipt.addreceipt')
                                        @endcan
                                        <a href="{{route('menureceipt')}}">
                                            @include('buttons.cancel')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mt-2">
                                <div class="card-input">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="shipmentid">Recibo</label>
                                                <input type="text" class="form-control"  id="shipmentid" name="shipmentid">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="delivery">Factura</label>
                                                <input type="text" class="form-control"  id="delivery" name="delivery">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="folionumber">Folio de patios</label>
                                                <input type="text" class="form-control"  id="folionumber" name="folionumber">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="statusid">Estatus <span style="color: red">*</span></label>
                                                <select name="statusid" class="form-control select2">
                                                    <option value="" selected>Seleccione un valor</option>
                                                    @foreach($status as $model)
                                                        <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="providerid">Proveedor<span style="color: red">*</span></label>
                                                <select name="providerid" id='providerid'  class="form-control select2">
                                                    <option value="" selected>Seleccione un valor</option>
                                                    @foreach($providers as $model)
                                                        <option value="{{$model->id}}"> {{$model->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card-input">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <table id="tablereceipt" class="table table-condensed table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Embarque</th>
                                <th>Factura</th>
                                <th>Proveedor</th>
                                <th>Tipo</th>
                                <th>Folio Patios</th>
                                <th>Linea de transporte</th>
                                <th>Placa</th>
                                <th>Ubicación</th>
                                <th>Sellos</th>
                                <th>Cita de descarga</th>
                                <th>Hora de registro</th>
                                <th>Mesa de control</th>
                                <th>Enrampe</th>
                                <th>Inicio de descarga</th>
                                <th>Fin de descarga</th>
                                <th>Autorización de salida</th>
                                <th>¿Ingresado?</th>
                                <th>Estadia (Min)</th>
                                <th>Estatus del recibo</th>
                            </tr>
                            </thead>
                        </table>
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
    @include('Receipt.ScheduleReceipt.partials.table')
    <script>
        function reloadtable() {
            $('#tablereceipt').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#providerid,#statusid").change(function () {
            reloadtable();
        });
    </script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#saveReceipt').click(function (e) {
                e.preventDefault();

                // Validar campos
                let centerid = $("#centeridadd").val();
                let warehouseid = $("#warehouseidadd").val();
                let providerid = $("#provideridadd").val();
                let carrierid = $("#carrierid").val();
                let razonstatusid = $("#razonstatusid").val();
                let addasndate = $("#addasndate").val();

                // Verificar campos vacíos
                if (!centerid || !warehouseid || !providerid || !carrierid || !razonstatusid || !addasndate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Todos los campos son obligatorios. Por favor, llene todos los campos antes de continuar.',
                        confirmButtonText: 'Aceptar'
                    });
                    return; // No continuar si hay campos vacíos
                }


                $.ajax({
                    data: {
                        centerid: $("#centeridadd").val(),
                        warehouseid: $("#warehouseidadd").val(),
                        providerid: $("#provideridadd").val(),
                        carrierid: $("#carrierid").val(),
                        razonstatusid: $("#razonstatusid").val(),
                        addasndate: $("#addasndate").val(),
                    },
                    url: '{!! route('addReceiptToPlan') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        $('#addasndate').val('');
                        Swal.fire({
                            type: 'success',
                            title: `Recibo programado : ${data.shipmentid}`,
                            html: `
                                    <p>Fecha de cita : ${data.addasndate}</p>
                                    <p>Proveedor : ${data.providerid}</p>
                                    <p>Línea de transporte: ${data.carrierid}</p>
                                    <p>Tipo de entrada: ${data.reason}</p>
                                `,
                            width: 450,
                        })
                        reloadtable();
                        $('#modal-add').modal('hide')
                    },
                });
            });
        });
    </script>

@endsection
