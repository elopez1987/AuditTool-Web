<div class="modal fade" role="dialog" aria-labelledby="modalCreateTaskLabel" tabindex="-1"
     aria-hidden="true" id="modal-create-task">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateTaskLabel">
                    Agregar Tarea
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="form-create-task">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="folionumber_modal">Número de Folio <span style="color: red">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="folionumber_modal" name="folionumber" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-info btn-xs" id="searchInvoiceModal" type="button">Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="barcode_modal">Etiqueta</label>
                                <input type="text" readonly class="form-control" id="barcode_modal" name="barcode">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="linea_modal">Línea de Transporte</label>
                                <input type="text" class="form-control" id="linea_modal" name="linea" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="boxnumber_modal">Placa de caja</label>
                                <input type="text" class="form-control" id="boxnumber_modal" name="boxnumber" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="containerseal_modal">Número de sello</label>
                                <input type="text" class="form-control" readonly id="containerseal_modal" name="containerseal">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="originzoneid_modal">Zona de origen</label>
                                <input type="text" class="form-control" readonly id="originzoneid_modal" name="originzoneid">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="originlocationid_modal">Ubicación de origen</label>
                                <input type="text" class="form-control" readonly id="originlocationid_modal" name="originlocationid">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="operatorname_modal">Nombre del operador</label>
                                <input type="text" class="form-control" readonly id="operatorname_modal" name="operatorname">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone_modal">Número de contacto</label>
                                <input type="text" class="form-control" readonly id="phone_modal" name="phone">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="econumber_modal">Eco. De caja</label>
                                <input type="text" class="form-control" readonly id="econumber_modal" name="econumber">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="typeid_modal">Enviar tarea a <span style="color: red">*</span></label>
                                <select name="typeid" id='typeid_modal' class="form-control select2" required>
                                    <option value="" selected>Seleccione una opción</option>
                                    <option value="1">OPERADOR</option>
                                    <option value="2">PATIERO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div id="patiero_modal" style="display: none;">
                                <div class="form-group">
                                    <label for="patieroid_modal">Patiero <span style="color: red">*</span></label>
                                    <select name="patieroid" id='patieroid_modal' class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="zoneid_modal">Zonas <span style="color: red">*</span></label>
                                <select name="zoneid" id='zoneid_modal' required class="form-control select2">
                                    <option value="" selected>Seleccione una Zona</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="locationid_modal">Ubicación <span style="color: red">*</span></label>
                                <select name="locationid" id='locationid_modal' required class="form-control select2">
                                    <option value="" selected>Seleccione una ubicación</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm waves-effect waves-light" data-dismiss="modal">
                        <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-info btn-sm waves-effect waves-light">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
