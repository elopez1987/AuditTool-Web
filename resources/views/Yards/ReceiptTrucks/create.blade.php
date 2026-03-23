@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    crear registro
                </div>
                <div class="card-body">
                    <form id="receiptTruckForm" method="POST" action="{{url('receipttrucks')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="row button-group">
                                <div class="col-lg-1 col-md-6 col-sm-6 col-xs-4">
                                        <button type="submit" id="btnSaveReceipTruck"
                                                class="btn waves-effect waves-light btn-outline-success btn-block btn-md">
                                            <img src="{{asset('/pics/adaceptar.png')}}" alt="" class="img-16">
                                            <span style="font-size: 11px;"> Guardar</span>
                                        </button>
                                </div>
                                <div class="col-lg-1 col-md-6 col-sm-6 col-xs-4">
                                    <a href="{{route('receipttrucks.index')}}">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-danger btn-block btn-md">
                                            <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">
                                            <span style="font-size: 11px;"> Cancelar</span>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        @include('Yards.ReceiptTrucks.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')

@endsection


@section('script')
    <script type="module">
        const baseUrl = "{{ config('app.url') }}";
        const warehouseUrl = "{{ asset('js/selects/warehouse-for-center.js') }}";
        const carrierUrl = "{{ asset('js/selects/carriers-for-center.js') }}";
        
        // const {initOperatorLookup} = await import(operatorUrl);
        const {initializeWarehouseLookup} = await import(warehouseUrl);
        // const {initTruckLookup} = await import(truckUrl);
        const {initCarrierSelector} = await import(carrierUrl);

        initCarrierSelector({
            centerSelectId: "centerid",
            carrierSelectId: "carrierid",
            baseUrl: baseUrl
        });
        initializeWarehouseLookup("centerid", "warehouseid", baseUrl);
    </script>

    <script>
        $(document).ready(function () {

            // Interceptar el submit del formulario
            $("#receiptTruckForm").on("submit", function (e) {
                e.preventDefault();

                const $form = $(this);
                const $submitButton = $("#btnSaveReceipTruck");
                const formData = new FormData(this);
                const url = $form.attr("action");

                // Deshabilitar botón mientras se envía
                $submitButton.prop("disabled", true);
                $submitButton.find("span").text(" Guardando...");


                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function (response) {


                        Swal.fire({
                            icon: "success",
                            title: "Éxito",
                            text: response.message || "Registro guardado exitosamente",
                            timer: 3000,
                            showConfirmButton: true
                        }).then(() => {
                            // Si hay redirect, ir a esa URL
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                // Limpiar el formulario
                                $form[0].reset();
                                // Reiniciar los selects de Select2
                                $('.select2').val(null).trigger('change');
                                // Opcionalmente redirigir al index
                                // window.location.href = "{{ route('receipttrucks.index') }}";
                            }
                        });
                    },
                    error: function (xhr) {

                        let errorMessage = "Error al guardar el registro";

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                // Errores de validación
                                const errors = xhr.responseJSON.errors;
                                errorMessage = Object.values(errors).flat().join("<br>");
                            }
                        } else if (xhr.responseText) {
                            errorMessage = xhr.responseText;
                        }

                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            html: errorMessage,
                            showConfirmButton: true
                        });
                    },
                    complete: function () {
                        // Rehabilitar botón
                        $submitButton.prop("disabled", false);
                        $submitButton.find("span").text(" Guardar");
                    }
                });
            });
        });
    </script>

@endsection

