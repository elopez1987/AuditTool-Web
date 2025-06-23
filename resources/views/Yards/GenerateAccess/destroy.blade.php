<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-destroy-{{$id}}">
    <form method="POST" action="{{url('generateaccesswarehouse/' . $id)}}" enctype="multipart/form-data">
        {{ method_field('DELETE') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">
                        Eliminar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <span> ¿ Estás seguro de eliminar el registro ?</span>

                    <hr class="mt-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Nombre de operador</label>
                                <input type="text" class="form-control"  id="name" name="name"
                                       value="{{$operador}}" readonly
                                >
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
                    <button type="submit" class="btn waves-effect waves-light btn-outline-secondary btn-sm ">
                        <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
