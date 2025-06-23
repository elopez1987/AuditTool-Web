<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-addfolio-{{$folioplant}}">
    <form method="POST" action="{{url('addfolio/' . $folioplant)}}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Vincular
                        unidad a la vuelta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="foliowarehouse">Folio de unidad</label>
                            <input type="text" class="form-control numeric" autofocus id="foliowarehouse"
                                   name="foliowarehouse"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
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
