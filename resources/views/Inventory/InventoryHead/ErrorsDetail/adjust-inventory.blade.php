<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-activate-{{$id}}">
    <form method="POST" action="{{url('inventory-adjust-count/' . $id)}}">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Ajustar ubicación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <x-text-input
                                name="partnumber01"
                                label="Número de parte conteo 1"
                                required
                                uppercase
                                colClass="col-lg-6 col-md-12"
                                value="{{$sku01}}"
                        />
                        <x-text-input
                                name="quantity01"
                                label="Cantidad conteo 1"
                                required
                                uppercase
                                colClass="col-lg-6 col-md-12"
                                value="{{$quantity01}}"
                        />
                        <x-text-input
                                name="partnumber02"
                                label="Número de parte conteo 2"
                                required
                                uppercase
                                colClass="col-lg-6 col-md-12"
                                value="{{$sku02}}"
                        />
                        <x-text-input
                                name="quantity02"
                                label="Cantidad conteo 2"
                                required
                                uppercase
                                colClass="col-lg-6 col-md-12"
                                value="{{$quantity02}}"
                        />
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
