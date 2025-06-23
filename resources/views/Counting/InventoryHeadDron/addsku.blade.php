<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-addsku">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title"> Agregar Existencia de WMS</h5>
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
                            <label for="description">Descripción de inventario:</label>
                            <input type="text" class="form-control" readonly value="{{$inventory->description}}" id="description" name="description">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="locationid">Ubicación:</label>
                            <input type="text" class="form-control" required value="{{old('locationid')}}" id="locationid" name="locationid"
                                   onKeyUp=this.value=this.value.toUpperCase()>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="partnumberid">Número de parte:</label>
                            <input type="text" class="form-control" required value="{{old('partnumberid')}}" id="partnumberid" name="partnumberid"
                                   onKeyUp=this.value=this.value.toUpperCase()>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="totalbox">Total de cajas:</label>
                            <input type="text" class="form-control numeric" required value="{{old('totalbox')}}" id="totalbox" name="totalbox">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="lpns">Total de lpns:</label>
                            <input type="text" class="form-control numeric" required value="{{old('lpns')}}" id="lpns" name="lpns" >
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
                <button class="btn waves-effect waves-light btn-outline-secondary btn-sm" id="savesku">
                    <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
