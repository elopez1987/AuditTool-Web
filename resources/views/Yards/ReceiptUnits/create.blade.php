@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Registro de unidad
                </div>
                <form id="receiptUnitForm" method="POST" action="{{url('receiptunits')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @include('Yards.ReceiptUnits.partials.formCreate')
                    </div>
                    <div class="card-footer">
                        <div class="row button-group">
                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-4">
                                <button type="submit" id="btnSaveReceiptUnit"
                                        class="btn waves-effect waves-light btn-outline-success btn-block btn-md">
                                    <img src="{{asset('/pics/adaceptar.png')}}" alt="" class="img-16">
                                    <span style="font-size: 11px;"> Guardar</span>
                                </button>
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
    @include('layouts.Partials.Errors')
@endsection

@section('css')

@endsection


@section('script')

    <script type="module">
        const baseUrl = "{{ url('') }}";
        const operatorUrl = "{{ asset('js/selects/operator.js') }}";
        const warehouseUrl = "{{ asset('js/selects/warehouse-for-center.js') }}";
        const zoneUrl = "{{ asset('js/selects/zone-for-warehouse.js') }}";
        const locationUrl = "{{ asset('js/selects/locations-for-zone.js') }}";
        const truckUrl = "{{ asset('js/selects/search-truck.js') }}";
        const shipmentUrl = "{{ asset('js/selects/shipment-truck.js') }}";
        const razonUrl = "{{ asset('js/selects/validate-razonstatusid.js') }}";
        const providerUrl = "{{ asset('js/selects/providers-for-warehouse.js') }}";
        const boxUrl = "{{ asset('js/selects/search-box.js') }}";
        
        const {initOperatorLookup} = await import(operatorUrl);
        const {initializeWarehouseLookup} = await import(warehouseUrl);
        const {initializeZoneLookup} = await import(zoneUrl);
        const {initializeLocationLookup} = await import(locationUrl);
        const {initTruckLookup} = await import(truckUrl);
        const {initShipmentTruckChange} = await import(shipmentUrl);
        const {initRazonStatusBehavior} = await import(razonUrl);
        const {initProviderSelector} = await import(providerUrl);
        const {initBoxSearch} = await import(boxUrl);

        initOperatorLookup(
            "searchOperator",
            "barcodeoperator",
            {
                operatorname: "name",
                drivernumber: "drivernumber",
                phone: "phone"
            }
        );

        initTruckLookup({
            searchBarcodeButtonId: "searchTruck",
            searchTruckNumberButtonId: "searchTruckNumber",
            barcodeInputId: "barcodetruck",
            truckNumberInputId: "trucknumber",
            ecoNumberInputId: "ecotruck"
        });

        initShipmentTruckChange({
            selectId: "truckid",
            tractoSectionId: "tracto",
            barcodeInputId: "barcodetruck",
            truckNumberInputId: "trucknumber",
            ecoNumberInputId: "ecotruck"
        });

        initializeWarehouseLookup("centerid", "warehouseid");
        initializeZoneLookup("warehouseid", "zoneid", baseUrl);
        initializeLocationLookup("zoneid", "locationid");
        initProviderSelector();
        initRazonStatusBehavior();
        initBoxSearch();
    </script>

    <script>
        $(document).ready(function() {

            // Interceptar el submit del formulario
            $("#receiptUnitForm").on("submit", function(e) {
                e.preventDefault();

                const $form = $(this);
                const $submitButton = $("#btnSaveReceiptUnit");
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
                    success: function(response) {

                        
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
                                // window.location.href = "{{ route('receiptunits.index') }}";
                            }
                        });
                    },
                    error: function(xhr) {
                        
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
                    complete: function() {
                        // Rehabilitar botón
                        $submitButton.prop("disabled", false);
                        $submitButton.find("span").text(" Guardar");
                    }
                });
            });
        });
    </script>


@endsection
