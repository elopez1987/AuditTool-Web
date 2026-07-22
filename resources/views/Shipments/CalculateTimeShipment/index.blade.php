@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Calculadora de tiempo de embarque
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    Información de embarque
                                </div>
                                <div class="card-body">
                                    <form method="GET" id="search" role="form" class="r-separator">
                                        {{csrf_field()}}
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <a href="javascript:void(0);">
                                                    <button type="button" id="btn-generate" class="btn waves-effect waves-light btn-outline-info btn-md">
                                                        <img src="{{asset('/ico/adbuscar.png')}}" alt="" class="img-16 ml-1">
                                                        <span class="text-button"> Generar tiempos</span>
                                                    </button>
                                                </a>
                                                <a>
                                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md" onclick="clearForm();">
                                                        <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                                        <span class="text-button"> Limpiar</span>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                        <hr class="m-t-0">
                                        <div class="row">
                                            <x-text-input
                                                    name="shipmentid"
                                                    label="Número de embarque"
                                                    type="text"
                                                    required
                                                    placeholder="C56334...."
                                                    colClass="col-lg-6 col-md-12"
                                            />
                                        </div>
                                        <div class="row">
                                            <x-text-input
                                                    name="customername"
                                                    label="Cliente"
                                                    type="text"
                                                    colClass="col-lg-6 col-md-12"
                                                    disabled="disabled"
                                            />
                                            <x-text-input
                                                    name="customercity"
                                                    label="Destino"
                                                    type="text"
                                                    colClass="col-lg-6 col-md-12"
                                                    disabled="disabled"
                                            />
                                        </div>
                                        <div class="row">
                                            <x-text-input
                                                    name="loaddate"
                                                    label="Fecha de carga"
                                                    type="text"
                                                    colClass="col-lg-6 col-md-12"
                                                    disabled="disabled"
                                            />
                                            <x-text-input
                                                    name="deliverydate"
                                                    label="Cita de entrega"
                                                    type="text"
                                                    colClass="col-lg-6 col-md-12"
                                                    disabled="disabled"
                                            />
                                        </div>
                                        <div class="row">
                                            <x-text-input
                                                    name="maxdeparturedate"
                                                    label="Máxima de salida"
                                                    type="text"
                                                    colClass="col-lg-6 col-md-12"
                                                    disabled="disabled"
                                            />
                                            <x-text-input
                                                    name="newloaddate"
                                                    label="Fecha de carga optima"
                                                    type="text"
                                                    colClass="col-lg-6 col-md-12"
                                                    disabled="disabled"
                                            />
                                        </div>
                                    </form>
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

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Generar los tiempos del embarque
            $('#btn-generate').on('click', function () {
                getDataCalculateTime();
            });

            // Permite generar presionando Enter en el número de embarque
            $('#shipmentid').on('keypress', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    getDataCalculateTime();
                }
            });
        });

        function getDataCalculateTime() {
            let shipmentid = $('#shipmentid').val();

            if (!shipmentid || shipmentid.trim() === '') {
                notify('warning', 'Ingrese el número de embarque.');
                $('#shipmentid').focus();
                return;
            }

            $.ajax({
                url: "{{ route('getDataCalculatetimeshipment') }}",
                method: 'GET',
                data: {shipmentid: shipmentid},
                success: function (response) {
                    let data = response.data || {};
                    $('#customername').val(data.customername || '');
                    $('#customercity').val(data.customercity || '');
                    $('#loaddate').val(data.loaddate || '');
                    $('#deliverydate').val(data.deliverydate || '');
                    $('#maxdeparturedate').val(data.maxdeparturedate || '');
                    $('#newloaddate').val(data.arrivetransportdate || '');

                    if (response.message) {
                        notify('success', response.message);
                    }
                },
                error: function (xhr) {
                    let message = (xhr.responseJSON && xhr.responseJSON.error)
                        ? xhr.responseJSON.error
                        : 'Ocurrió un error al obtener los datos del embarque.';
                    notify('error', message);
                }
            });
        }

        // Limpia el formulario para calcular otro embarque
        function clearForm() {
            document.getElementById('search').reset();
            $('#shipmentid, #customername, #customercity, #loaddate, #deliverydate, #maxdeparturedate, #newloaddate').val('');
            $('#shipmentid').focus();
        }

        function notify(type, message) {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                alert(message);
            }
        }
    </script>
@endsection
