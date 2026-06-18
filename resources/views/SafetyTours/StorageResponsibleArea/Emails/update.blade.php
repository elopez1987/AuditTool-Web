<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-update-{{$contact->id}}">
    <form method="POST" action="{{url('responsible-area-emails-update/' . $contact->id)}}">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">
                        Actualizar registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <x-text-input
                                name="name"
                                label="Nombre completo"
                                required
                                uppercase
                                :value="$contact->name"
                                colClass="col-lg-6 col-md-12"
                        />
                        <x-text-input
                                name="email"
                                label="Correo electronico"
                                required
                                uppercase
                                :value="$contact->email"
                                colClass="col-lg-6 col-md-12"
                        />
                    </div>
                    <div class="row">
                        <x-text-input
                                name="phone"
                                label="Número de contacto"
                                required
                                uppercase
                                :value="$contact->phone"
                                colClass="col-lg-6 col-md-12"
                        />
                    </div>

                    <div class="row">
                        <x-text-input
                                name="level"
                                label="Nivel de contacto"
                                maxlength="1"
                                required
                                :value="$contact->level"
                                colClass="col-lg-6 col-md-12"
                        />
                        <x-text-input
                                name="attempts"
                                label="Cantidad de correos a enviar"
                                maxlength="1"
                                required
                                :value="$contact->attempts"
                                colClass="col-lg-6 col-md-12"
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
