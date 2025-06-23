@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Registro de unidad
                </div>
                <form method="POST" action="{{url('receiptuniteasystore')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @include('Yards.ReceiptUnits.partials.formCreateSimple')
                    </div>
                    <div class="card-footer">
                        <div class="row button-group">
                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-4">
                                <a href="">
                                    <button type="submit"
                                            class="btn waves-effect waves-light btn-outline-success btn-block btn-md">
                                        <img src="{{asset('/pics/adaceptar.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Guardar</span>
                                    </button>
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-4">
                                <a href="{{route('receiptunits.index')}}">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-danger btn-block btn-md">
                                        <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Cancelar</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    @include('SelectDynamics.SearchOperator')
    @include('SelectDynamics.SelectWarehouseForCenterForCreate')
    @include('SelectDynamics.SelectProvidersForWarehouseForCreate')
    @include('SelectDynamics.SelectCarriersForCenterForCreate')
    @include('SelectDynamics.SelectTrucksForCenterForCreate')
    @include('SelectDynamics.SelectRasonsForCenterForCreate')
    @include('SelectDynamics.SelectUnitStatusForCenterForCreate')
    <script>
        $("#zoneid").change(event => {
            $.get(`./getLocationForZone/${event.target.value}`, function (res) {
                console.log(res)
                $("#locationid").empty();
                res.forEach(element => {
                    $("#locationid").append(`<option value=${element.id}> ${element.name} </option>`);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Ocultar el div "proveedor" al cargar la página si razonstatusid no es 2
            $('#proveedor').hide();
            $('#seal').hide();
            $('#razonstatusid').on('change', function () {
                let selectedValue = $(this).val();
                if (selectedValue === '2') {
                    $('#proveedor').show();
                    $('#seal').show();
                } else {
                    $('#proveedor').hide();
                    $('#seal').hide();
                }
            });
        });
    </script>
    <script>
        $("#searchBox").click(function () {
            if ($("#barcodebox").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el numero de caja!',
                })
            } else {
                $.getJSON('../getDataBox/' + $("#barcodebox").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros de la caja!',
                        })
                    } else {

                        $("#boxnumber").attr("value", values[0].boxnumber);
                        $("#econumberbox").attr("value", values[0].econumber);

                        $.getJSON('../getCarrierForBox/' + $("#barcodebox").val(), function (res) {
                            $("#carrierid").empty();
                            res.forEach(element => {
                                $("#carrierid").append(`<option value=${element.id}> ${element.name} </option>`);
                            });
                        });

                        $.getJSON('../getTypeUnitForBox/' + $("#barcodebox").val(), function (res) {
                            $("#unittypeid").empty();
                            res.forEach(element => {
                                $("#unittypeid").append(`<option value=${element.id}> ${element.description} </option>`);
                            });
                        });

                    }
                });
            }
        });
    </script>
    <script>
        $("#searchBoxNumber").click(function () {

            if ($("#boxnumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese la placa de la caja!',
                })
            } else {
                $.getJSON('../getDataBoxNumber/' + $("#boxnumber").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros de la caja!',
                        })
                    } else {

                        $("#barcodebox").attr("value", values[0].barcode);
                        $("#econumberbox").attr("value", values[0].econumber);

                        $.getJSON('../getCarrierForBoxNumber/' + $("#boxnumber").val(), function (res) {
                            $("#carrierid").empty();
                            res.forEach(element => {
                                $("#carrierid").append(`<option value=${element.id}> ${element.name} </option>`);
                            });
                        });
                        $.getJSON('../getTypeUnitForBoxNumber/' + $("#boxnumber").val(), function (res) {
                            $("#unittypeid").empty();
                            res.forEach(element => {
                                $("#unittypeid").append(`<option value=${element.id}> ${element.description} </option>`);
                            });
                        });

                    }
                });
            }
        });
    </script>

    <script>
        $("#searchTruck").click(function () {
            if ($("#barcodetruck").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el CB del tracto!',
                })
            } else {
                $.getJSON('../getDataTruck/' + $("#barcodetruck").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'No se encuentran registros de tracto!',
                        })
                    } else {
                        $("#trucknumber").attr("value", values[0].boxnumber);
                        $("#econumbertruck").attr("value", values[0].econumber);
                    }
                });
            }

        });
    </script>


    <script>
        $("#searchTruckNumber").click(function () {
            if ($("#trucknumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese la placa del tracto!',
                })
            } else {
                $.getJSON('../getDataTruckNumber/' + $("#trucknumber").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'No se encuentran registros de tracto!',
                        })
                    } else {
                        $("#barcodetruck").attr("value", values[0].barcodetruck);
                        $("#econumbertruck").attr("value", values[0].econumber);
                    }
                });
            }
        });
    </script>



    <script>
        $("#razonstatusid").change(event => {
            let razonstatusid = document.getElementById("razonstatusid").value;
            if (razonstatusid === '1' || razonstatusid === '2') {
                $.get(`../getStatusBoxUnit/${event.target.value}`, function (res) {
                    $("#unitstatusid").empty();
                    res.forEach(element => {
                        $("#unitstatusid").append(`<option value=${element.id}> ${element.name} </option>`);
                    });
                });
            } else {
                $.get(`../getStatusBoxUnitAll/`, function (res) {
                    $("#unitstatusid").empty();
                    res.forEach(element => {
                        $("#unitstatusid").append(`<option value=${element.id}> ${element.name} </option>`);
                    });
                });
            }
        });
    </script>

    <script>
        $("#truckid").change(event => {
            $.get(`../getShipmentTruck/${event.target.value}`, function (res) {
                let valor = res.auxiliary01;
                if (valor === 'N') {
                    $("#tracto").hide();
                    $("#barcodetruck").attr("value", '0');
                    $("#trucknumber").attr("value", '0');
                    $("#econumbertruck").attr("value", '0');
                } else {
                    $("#tracto").show();
                    $("#barcodetruck").attr("value", '');
                    $("#trucknumber").attr("value", '');
                    $("#econumbertruck").attr("value", '');
                }
            });
        });
    </script>

    <script>
        $("#razonstatusid").change(event => {
            let razonstatusid = document.getElementById("razonstatusid").value;
            if (razonstatusid === '1') {
                $("#containerseal").attr("value", '0');
                $("#shipmentid").attr("value", '0');
                $("#sello").hide();
            }
            if (razonstatusid === '2') {
                $("#containerseal").attr("value", '');
                $("#shipmentid").attr("value", '');
                $("#sello").show();
            }
        });
    </script>
@endsection

