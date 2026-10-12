<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-change-user-{{ $id }}">
    <form method="POST" action="{{ url('assingnewuser/' . $id) }}">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size: 14px;font-style: normal;font-weight: 400;">
                        Cambiar usuario asignado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <x-select2
                            name="user_id"
                            label="Usuario"
                            :options="$users"
                            valueField="id"
                            textField="name"
                            :required="true"
                            colClass="col-12"
                    />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{ asset('/pics/adcancelar.png') }}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm waves-effect waves-light">
                        <img src="{{ asset('/pics/adaceptar.png') }}" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
