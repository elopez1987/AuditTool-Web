<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-soporte">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Solicitar soporte.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h3>Selecciona un contacto</h3>
                        <button class="btn" onclick="sendMessage('527222643408')">Soporte 1</button>
                        <button class="btn" onclick="sendMessage('5215551234567')">Soporte 2</button>
                        <button class="btn" onclick="sendMessage('5213339876543')">Ventas</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                        data-dismiss="modal">
                    <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
