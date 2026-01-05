<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-returnstartgoodissuedate-{{$orderDelivery->id}}">
    <form method="POST" action="{{url('returnstartgoodissuedate/' . $orderDelivery->id)}}" enctype="multipart/form-data" id="saveFormReturnStartGoodIssueDate">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">Reiniciar
                        facturación.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="font-10">¿Esta seguro de devolver los documentos a operaciones?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light" id="submitBtnReturnStartGoodIssueDate">
                        <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
