<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-addlocation">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title"> Agregar ubicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="inventoryid">Número de inventario:</label>
                            <input type="text" class="form-control" readonly value="{{$inventory->id}}" id="inventoryid" name="inventoryid">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="inventoryid">Descripción de inventario:</label>
                            <input type="text" class="form-control" readonly value="{{$inventory->description}}" id="inventoryid" name="inventoryid">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="areaid">Area:</label>
                            <input type="text" class="form-control" required value="{{old('areaid')}}" id="areaid" name="areaid"
                                   onKeyUp=this.value=this.value.toUpperCase()>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="aisleid">Pasillo:</label>
                            <input type="text" class="form-control" required value="{{old('aisleid')}}" id="aisleid" name="aisleid"
                                   onKeyUp=this.value=this.value.toUpperCase()>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="cardid">Cédula:</label>
                            <input type="text" class="form-control" required value="{{old('cardid')}}" id="cardid" name="cardid"
                                   onKeyUp=this.value=this.value.toUpperCase()>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="locationid">Ubicación:</label>
                            <input type="text" class="form-control" required value="{{old('locationid')}}" id="locationid" name="locationid"
                                   onKeyUp=this.value=this.value.toUpperCase()>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="type">Tipo de ubicación: <span style="color: red">*</span></label>
                            <select name="type" id="type" required class="form-control select2">
                                <option value="">Seleccione una opción</option>
                                <option value="A">Almacenable</option>
                                <option value="T">Transitoria</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="unitmeasureid">Unidad de medida:<span style="color: red">*</span></label>
                            <select name="unitmeasureid" id="unitmeasureid" required class="form-control select2">
                                <option value="">Seleccione una opción</option>
                                <option value="CS">Cajas</option>
                                <option value="PAL">Pallets</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm "
                        data-dismiss="modal">
                    <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                    Cancelar
                </button>
                <button class="btn waves-effect waves-light btn-outline-secondary btn-sm" id="savelocation">
                    <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
