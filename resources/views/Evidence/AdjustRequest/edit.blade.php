@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Solicitud de material
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{url('storageevidenceprocess/' . $storageevidenceprocess->recordid)}}">
                                @include('buttons.back')
                            </a>
                            <a href="" data-target="#modal-add" data-toggle="modal">
                                <button type="submit"
                                        class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/adagregar1.png')}}" alt=""
                                         class="img-16 ml-1">
                                    <span style="font-size: 11px;"> Agregar Solicitud</span>
                                </button>
                            </a>
                            @include('Evidence.AdjustRequest.addsku')
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-xl-6 col-lg-10 col-md-12">
                            <table class="table table-condensed table-bordered table-striped tablegeneral">
                                <thead>
                                <tr>
                                    <th>Embarque</th>
                                    <th>Número de parte</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stocks as $stock)
                                    <tr>
                                        <td>{{$stock->shipment ? $stock->shipment->shipmentid : ''}}</td>
                                        <td>{{$stock->sku ? $stock->sku->prtnum : ''}}</td>
                                        <td>{{$stock->sku ? $stock->sku->lngdsc : ''}}</td>
                                        <td>{{$stock->quantity}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <div class="card-footer text-center">
                    Evidencias / Evidencias de Proceso / Modificar solicitud de material
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
    @include('layouts.Partials.DataTable')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#savePartNumber').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        partnumberid: $("#partnumberid").val(),
                        quantity: $("#quantity").val(),
                        shipmentid: $("#shipmentid").val(),
                    },
                    url: '{!! route('addPartNumberToEvidenceStock') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        $('#partnumberid').val('');
                        $('#quantity').val('');
                        Swal.fire({
                            type: 'success',
                            title: `Número de parte agregado con exito.`,
                            // text: ` Fecha de cita : ${data.addasndate} `,
                            width: 350,
                        })
                        $('#modal-add').modal('hide')
                        setTimeout(function () {
                            window.location.reload()
                        }, 2000)
                    },
                });
            });
        });
    </script>
@endsection

