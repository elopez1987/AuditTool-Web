@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Agregar detalle
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{route('storageincidencesjourneys.index')}}">
                                @include('buttons.back')
                            </a>
                            <hr class="mb-3">
                        </div>
                    </div>
                    <div class="row  mt-3">
                        <div class="col-lg-12 col-sm-12">
                            <div class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                                <h3 class="text-info"><i class="fa fa-check-circle"></i> Información</h3>
                                Selecione un número de embarque en la cual requiera reportar una
                                incidencia, al dar clic se mostrara la información relacionada con el embarque
                                seleccionado.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="shipmentid">Número de embarque con incidencia : </label>
                                <select name="shipmentid" class="form-control select2" id="shipmentid">
                                    <option value="">Seleccione un embarque</option>
                                    @foreach($shipments as $shipment)
                                        <option value="{{$shipment->id}}">{{$shipment->shipmentid}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="customername">Cliente</label>
                                <input type="text" class="form-control" readonly id="customername" name="customername"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="city">Ciudad</label>
                                <input type="text" class="form-control" readonly id="city" name="city"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="downloaddate">Fecha de descarga</label>
                                <input type="text" class="form-control" readonly id="downloaddate" name="downloaddate"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row  mt-3">
                        <div class="col-lg-6 col-sm-12">
                            <div class="card p-2">
                                <div class="alert alert-info">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                                    <h3 class="text-info font-12"><i class="fa fa-check-circle"></i> Información</h3>
                                    En el siguiente apartado ingrese los datos solicitados para reportar su incidencia,
                                    todos los registros son creados con estatus de <strong>SIN RESPUESTA</strong> hasta
                                    ser revisados por
                                    el equipo de inventarios del centro de distribución. <br>
                                    En caso de registrar una incidencia por código cambiado favor de ingresar el segundo
                                    IDH en el apartado que aparece abajo del resto de campos solicitados.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            @include('Shipments.StorageIncidenceJourneys.partials.formCreate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script>
        var quill = new Quill('#comment', {
            theme: 'snow'
        });
    </script>
    <script>
        $("#shipmentid").change(event => {
            let valor = document.getElementById("shipmentid").value;
            if (valor !== "") {
                $.getJSON('../getDataForShipment/' + $("#shipmentid").val(), function (values) {
                    $("#downloaddate").attr("value", values[0].downloaddate);
                    $("#customername").attr("value", values[0].name);
                    $("#city").attr("value", values[0].city);
                });
                $.get(`../getBillForShipment/${event.target.value}`, function (res) {
                    $(".delivery").empty();
                    res.forEach(element => {
                        $(".delivery").append(`<option value=${element.billingdocument}> ${element.billingdocument} </option>`);
                    });
                });
            }
        });
    </script>
    <script>
        $("#razoncode").change(event => {
            let valor = razoncode.value;
            if (valor === '13') {
                codigo.classList.remove('hide');
            } else {
                $('#codigo').addClass('hide');
            }
        });
    </script>
    <script>
        let route = "{{url('autocompletesku')}}";
        $('#partnumberid').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    return process(data);
                });
            },
        });
        $('#partnumberiderror').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    return process(data);
                });
            }
        });
    </script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#saveIncidence').click(function (e) {
                e.preventDefault();

                let shipmentid = $("#shipmentid").val();
                let delivery = $("#delivery").val();
                let partnumberid = $("#partnumberid").val();
                let quantity = $("#quantity").val();
                let downloaddate = $("#downloaddate").val();
                let unitmeasure = $("#unitmeasure").val();
                let razoncode = $("#razoncode").val();

                if (!shipmentid || !delivery || !partnumberid || !quantity || !downloaddate || !unitmeasure || !razoncode) {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'Todos los campos son obligatorios. Por favor, llene todos los campos antes de continuar.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }
                $.ajax({
                    data: {
                        shipmentid: $("#shipmentid").val(),
                        delivery: $("#delivery").val(),
                        partnumberid: $("#partnumberid").val(),
                        quantity: $("#quantity").val(),
                        downloaddate: $("#downloaddate").val(),
                        unitmeasure: $("#unitmeasure").val(),
                        razoncode: $("#razoncode").val(),
                        comment: $("#comment").val(),
                        partnumberiderror: $("#partnumberiderror").val(),
                    },
                    url: '{!! route('storageincidencesjourneys.store') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        $('#partnumberid').val('');
                        $('#description').val('');
                        $('#quantity').val('');
                        $('#description').val('');
                        $('#partnumberiderror').val('');
                        $('#codigo').addClass('hide');
                        $('#comment').val('');
                        Swal.fire({
                            type: 'success',
                            html: `
                                    <p>${data.message}</p>
                                `,
                            width: 350,
                        })
                    },
                    error: function (xhr) {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: (xhr.responseJSON.message),
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
                // reloadtable();
            });
        });
    </script>
@endsection

