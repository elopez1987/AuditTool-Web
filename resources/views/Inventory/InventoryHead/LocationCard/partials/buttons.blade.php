{{-- Solo si la ubicación aún no tiene estatus de comparación (statusid_location null) --}}
@can('inventoryheads.markaserror')
    @if (is_null($statusid_location))
        <button type="button"
                class="btn waves-effect waves-light btn-outline-info btn-sm btn-markaserror"
                data-id="{{ $id }}"
                data-toggle="tooltip"
                data-placement="top"
                title="Enviar a 3er conteo">
            <img src="{{ asset('/ico/typemovements.png') }}" alt="" class="img-16 ml-2 mr-2">
            <span> Enviar a 3er conteo</span>
        </button>
    @endif
@endcan

