<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-edit-{{$id}}">
    <form id="form-edit-shipment-{{$id}}" data-receipt-id="{{$receiptid}}">
        @csrf
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size: 14px;font-style: normal;font-weight: 400;">Editar
                        recibo.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="razonstatusid_edit_{{$id}}"> {{ ui_label('receiptasn_razonstatusid_createhd','Tipo de entrada') }}
                                    <span style="color: red">*</span>
                                </label>
                                <select name="razonstatusid" id="razonstatusid_edit_{{$id}}" required class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($reasons as $model)
                                        <option value="{{$model->id}}" {{ $razonstatusid == $model->id ? 'selected' : '' }}>
                                            {{$model->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="stockcategoryid_edit_{{$id}}"> {{ ui_label('receiptasn_stockcategoryid_createhd','Origen de la descarga') }}
                                    <span style="color: red">*</span></label>
                                <select name="stockcategoryid" id="stockcategoryid_edit_{{$id}}" required class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($categorystocks as $model)
                                        <option value="{{$model->id}}" {{ $stockcategoryid == $model->id ? 'selected' : '' }}>
                                            {{$model->description}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="providerid_edit_{{$id}}"> {{ ui_label('receiptasn_providerid_createhd','Proveedor') }}
                                    <span style="color: red">*</span>
                                </label>
                                <select name="providerid" id="providerid_edit_{{$id}}" required class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($providers as $model)
                                        <option value="{{$model->id}}" {{ $providerid == $model->id ? 'selected' : '' }}>
                                            {{$model->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(ui_visible('receiptasn_shipmentid_createhd'))
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid_edit_{{$id}}"> {{ ui_label('receiptasn_shipmentid_createhd','Número de recibo') }}
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control"
                                           id="shipmentid_edit_{{$id}}" name="shipmentid"
                                           onKeyUp="this.value=this.value.toUpperCase();"
                                           value="{{$shipmentid}}"
                                    >
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="billoflading_edit_{{$id}}"> {{ ui_label('receiptasn_billoflading_createhd','Factura') }}
                                </label>
                                <input type="text" class="form-control"
                                       id="billoflading_edit_{{$id}}" name="billoflading"
                                       onKeyUp="this.value=this.value.toUpperCase();"
                                       value="{{$billoflading}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="oc_edit_{{$id}}"> {{ ui_label('receiptasn_oc_createhd','Orden de compra') }} </label>
                                <input type="text" class="form-control"
                                       id="oc_edit_{{$id}}" name="oc"
                                       onKeyUp="this.value=this.value.toUpperCase();"
                                       value="{{$oc}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partnumberid_edit_{{$id}}">{{ ui_label('receiptasn_partnumberid_createhd','Sku') }}</label>
                                <input type="text" class="form-control"
                                       id="partnumberid_edit_{{$id}}" name="partnumberid"
                                       required
                                       onKeyUp="this.value=this.value.toUpperCase();"
                                       value="{{$partnumberid}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="quantity_edit_{{$id}}">{{ ui_label('receiptasn_quantity_createhd','Cantidad') }}</label>
                                <input type="text" class="form-control numeric"
                                       id="quantity_edit_{{$id}}" name="quantity"
                                       required
                                       value="{{$quantity}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(ui_visible('receiptasn_carrierid_createhd'))
                            <div class="col-lg-12 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carrierid_edit_{{$id}}">{{ ui_label('receiptasn_carrierid_createhd','Línea de transporte') }}
                                        <span style="color: red">*</span>
                                    </label>
                                    <select name="carrierid" id="carrierid_edit_{{$id}}" class="form-control select2" required>
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($carriers as $model)
                                            <option value="{{$model->id}}" {{ $carrierid == $model->id ? 'selected' : '' }}>
                                                {{$model->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm waves-effect waves-light"
                            id="btn-edit-shipment-{{$id}}">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#form-edit-shipment-{{$id}}').on('submit', function (e) {
            e.preventDefault();

            var receiptId = $(this).data('receipt-id');
            var formData = $(this).serialize();

            $.ajax({
                url: '{{url('updateShipmentToSchedule')}}/' + receiptId,
                type: 'PUT',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#btn-edit-shipment-{{$id}}').prop('disabled', true);
                },
                success: function (response) {
                    $('#modal-edit-{{$id}}').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message || 'Recibo actualizado correctamente.',
                        timer: 3000,
                        showConfirmButton: false
                    });

                    if (typeof reloadtable === 'function') {
                        reloadtable();
                    }
                },
                error: function (xhr) {
                    var errorMessage = 'Ocurrió un error al procesar la solicitud';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            errorMessage = '<ul style="text-align: left;">';
                            $.each(errors, function (key, value) {
                                errorMessage += '<li>' + value[0] + '</li>';
                            });
                            errorMessage += '</ul>';
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage,
                        confirmButtonText: 'Aceptar'
                    });
                },
                complete: function () {
                    $('#btn-edit-shipment-{{$id}}').prop('disabled', false);
                }
            });
        });
    });
</script>
