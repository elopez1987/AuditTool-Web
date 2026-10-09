<a href="" data-target="#modal-update-location-{{ $id }}" data-toggle="modal" data-placement="top">
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
            data-toggle="tooltip" data-placement="top" title="Actualizar ubicación">
        <img src="{{ asset('/ico/typemovements.png') }}" alt="" class="img-16">
        <span style="font-size: 11px;"> Actualizar </span>
    </button>
</a>
@include('Inventory.InventoryHead.InventoryLocationCounts.update-location')
