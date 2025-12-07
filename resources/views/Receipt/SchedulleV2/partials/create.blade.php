<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-create-{{$id}}">
    <form id="form-add-shipment-{{$id}}" data-schedule-id="{{$id}}">
        @csrf
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Agregar recibo.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="razonstatusid"> {{ ui_label('receiptasn_razonstatusid_createhd','Tipo de entrada') }}
                                    @error("razonstatusid")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span>
                                </label>
                                <select name="razonstatusid" required class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($reasons as $model)
                                        <option
                                                value="{{$model->id}}" {{ old('razonstatusid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="stockcategoryid"> {{ ui_label('receiptasn_stockcategoryid_createhd','Origen de la descarga') }}
                                    @error("stockcategoryid")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <select name="stockcategoryid" required class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($categorystocks as $model)
                                        <option
                                                value="{{$model->id}}" {{ old('stockcategoryid') == $model->id ? 'selected' : '' }}> {{$model->description}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="providerid"> {{ ui_label('receiptasn_providerid_createhd','Proveedor') }}
                                    @error("providerid")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span>
                                </label>
                                <select name="providerid" id='providerid' required class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($providers as $model)
                                        <option
                                                value="{{$model->id}}" {{ old('providerid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="shipmentid"> {{ ui_label('receiptasn_shipmentid_createhd','Número de recibo') }}
                                    @error("shipmentid")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                                       id="shipmentid" name="shipmentid"
                                       aria-describedby="shipmentid" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{old('shipmentid')}}"
                                >
                                @error('shipmentid')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="billoflading"> {{ ui_label('receiptasn_billoflading_createhd','Factura') }}
                                </label>
                                <input type="text" class="form-control @error('billoflading') is-invalid @enderror"
                                       id="billoflading" name="billoflading"
                                       aria-describedby="billoflading" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{old('billoflading')}}"
                                >
                                @error('billoflading')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="oc"> {{ ui_label('receiptasn_oc_createhd','Orde de compra') }} </label>
                                <input type="text" class="form-control @error('oc') is-invalid @enderror"
                                       id="oc" name="oc"
                                       aria-describedby="oc" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{old('oc')}}"
                                >
                                @error('oc')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partnumberid">{{ ui_label('receiptasn_partnumberid_createhd','Sku') }}
                                    @error("partnumberid")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                </label>
                                <input type="text"
                                       class="form-control @error('partnumberid') is-invalid @enderror"
                                       id="partnumberid"
                                       name="partnumberid"
                                       required
                                       onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{old('partnumberid')}}"
                                >
                                @error('partnumberid')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="quantity">{{ ui_label('receiptasn_quantity_createhd','Cantidad') }}
                                    @error("quantity")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                </label>
                                <input type="text"
                                       class="form-control numeric @error('quantity') is-invalid @enderror"
                                       id="quantity"
                                       name="quantity"
                                       required
                                       onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{old('quantity')}}"
                                >
                                @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="carrierid">{{ ui_label('receiptasn_carrierid_createhd','Línea de transporte ') }}
                                    @error("carrierid")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span>
                                </label>
                                <select name="carrierid" class="form-control select2" required>
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($carriers as $model)
                                        <option
                                                value="{{$model->id}}" {{ old('carrierid') == $model->id ? 'selected' : '' }} > {{$model->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            id="btn-save-shipment-{{$id}}">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#form-add-shipment-{{$id}}').on('submit', function(e) {
            e.preventDefault();
            
            var scheduleId = $(this).data('schedule-id');
            var formData = $(this).serialize();
            
            $.ajax({
                url: '{{url('addShipmentToScheduleedit')}}/' + scheduleId,
                type: 'PUT',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#btn-save-shipment-{{$id}}').prop('disabled', true);
                },
                success: function(response) {
                    $('#modal-create-{{$id}}').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message || 'Recibo agregado al plan de entrega',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    
                    // Limpiar formulario
                    $('#form-add-shipment-{{$id}}')[0].reset();
                    $('.select2').val('').trigger('change');
                    
                    // Recargar DataTable
                    if (typeof reloadtable === 'function') {
                        reloadtable();
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'Ocurrió un error al procesar la solicitud';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            // Errores de validación
                            var errors = xhr.responseJSON.errors;
                            errorMessage = '<ul style="text-align: left;">';
                            $.each(errors, function(key, value) {
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
                complete: function() {
                    $('#btn-save-shipment-{{$id}}').prop('disabled', false);
                }
            });
        });
    });
</script>
