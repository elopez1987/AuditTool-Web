@can('inventorylocations-card.show')
    <a href="{{url('inventorylocations-card/' . $row->id )}}">
        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">
            <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
            <span > Detalle</span>
        </button>
    </a>
@endcan


@can('inventoryheads.destroy')
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm btn-delete"
            data-id="{{ $row->id }}">
        <img src="{{asset('/ico/refuseunit.png')}}" alt="" class="img-16 ml-2 mr-2">
        <span > Eliminar</span>
    </button>
@endcan
