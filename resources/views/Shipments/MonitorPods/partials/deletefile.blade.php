<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-delete-{{$file->recordid}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Borrar archivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="recordid">Número de registro</label>
                            <input type="text" class="form-control"  readonly id="recordid" name="recordid"
                                   onKeyUp=this.value=this.value.toUpperCase()
                                   value="{{$file->recordid}}"
                            >
                        </div>
                    </div>
                </div>
                <span> ¿ Estás seguro de eliminar el registro ?</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm "
                        data-dismiss="modal">
                    <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                    Cancelar
                </button>
                <button class="btn waves-effect waves-light btn-outline-secondary btn-sm" id="deletefile">
                    <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
