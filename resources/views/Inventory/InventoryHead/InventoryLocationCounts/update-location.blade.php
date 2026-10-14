<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-update-location-{{$id}}">
    <form method="POST" action="{{ route('updatelocation', $id) }}">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size: 14px;font-style: normal;font-weight: 400;">
                        Actualizar ubicación {{ $location_name ?? '' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <x-text-input
                                name="partnumber01"
                                label="Número de parte"
                                required
                                uppercase
                                colClass="col-lg-6 col-md-12"
                                value="{{ $part_number1 }}"
                        />
                        <x-text-input
                                name="quantity01"
                                label="Cantidad"
                                type="number"
                                required
                                colClass="col-lg-6 col-md-12"
                                value="{{ $quantity01 }}"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{ asset('/pics/adcancelar.png') }}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm waves-effect waves-light">
                        <img src="{{ asset('/pics/adaceptar.png') }}" alt="">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
