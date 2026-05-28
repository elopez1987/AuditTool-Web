@can('inventorylocations-card.show')
    <a href="{{url('inventorylocations-card/' . $row->id )}}" data-toggle="tooltip" data-placement="top"
       title="Detalle de la cedula">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan


@can('inventoryheads.destroy')
    <button type="button"
            class="btn btn-danger btn-sm btn-delete"
            data-id="{{ $row->id }}">
        <i class="mdi mdi-delete"></i> Eliminar
    </button>
@endcan
