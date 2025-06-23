<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-comment-{{$detail->folioid}}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title"> Comentario de la incidencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <span>
                     {{$detail->comment}}
                </span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm "
                        data-dismiss="modal">
                    <img src="{{asset('/ico/closemodal.png')}}" alt="" class="img-16">
                    Salir
                </button>
            </div>
        </div>
    </div>
</div>
