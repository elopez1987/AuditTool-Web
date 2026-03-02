<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true" style="display: none;" id="modal-clearschedulle">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title"> Limpiar horario de descarga.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form method="POST" action="{{url('clearSchedulle')}}">
                @csrf
                <div class="modal-body">
                        <p>
                            ¿Está seguro de que desea limpiar el horario de descarga de los registros PREDESCARGA?
                        </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm "
                            data-dismiss="modal">
                        <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                        Cancelar
                    </button>
                    <button class="btn waves-effect waves-light btn-outline-secondary btn-sm" type="submit">
                        <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                        Aceptar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
