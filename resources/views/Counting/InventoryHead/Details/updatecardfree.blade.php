<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-updatestatus-{{$recordid}}">
    <form method="POST" action="{{url('updateCardFree/' . $recordid)}}">
        {{ method_field('PUT') }}
        @csrf
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;"> Liberar conteo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class=" col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="inventoryid">Número de inventario<span style="color: red">*</span></label>
                            <input type="text" class="form-control" readonly  id="inventoryid" name="inventoryid"
                                   value="{{$inventoryid}}"
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class=" col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="cardid">Cédula<span style="color: red">*</span></label>
                            <input type="text" class="form-control" readonly  id="cardid" name="cardid"
                                   value="{{$cardid}}"
                            >
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="typecount">Número de conteo<span style="color: red">*</span></label>
                            <select name="typecount" required class="form-control select2">
                                <option value="">Selecciona una opcion</option>
                                <option value="1">Primer conteo</option>
                                <option value="2">Segundo conteo</option>
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
                <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                    <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
    </form>
</div>
