<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-addresponsible-{{$id}}">
    <form method="POST" action="{{url('saveresponsibletounit/' . $id)}}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 11px;font-style: normal;font-weight: 400;">Asignar responsable a la unidad.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label >Responsable de unidad.</label>
                                <select name="responsibleid" id="responsibleid" class="form-control select2 @error('responsibleid') is-invalid @enderror" required>
                                    <option value="" selected>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label >Area de responsabilidad.</label>
                                <select name="areaid" id="areaid" class="form-control select2 @error('areaid') is-invalid @enderror" required>
                                    <option value="" selected>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/ico/close.png')}}" alt="" class="img-16 mr-2 ml-2">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                        <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16 mr-2 ml-2">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
