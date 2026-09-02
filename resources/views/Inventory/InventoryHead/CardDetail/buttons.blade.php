@can('inventorylocations-card.show')
    <a href="{{url('inventorylocations-card/' . $row->id )}}" class="ml-2 mr-2 ">
        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">
            <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2 mr-2">
            <span> Detalle</span>
        </button>
    </a>
@endcan

@can('inventorylocations-card.printformat01')
    @if($row->statusid01 == 'C')
        <a href="{{url('print-format-inventory-01/' . $row->id )}}" class="ml-2 mr-2 ">
            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">
                <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span> Formato 01</span>
            </button>
        </a>
    @endif
@endcan

@can('inventorylocations-card.printformat02')
    @if($row->statusid02 == 'C')
        <a href="{{url('print-format-inventory-02/' . $row->id )}}" class="ml-2 mr-2 ">
            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">
                <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16 ml-2 mr-2">
                <span> Formato 02</span>
            </button>
        </a>
    @endif
@endcan

@can('inventoryheads.destroy')
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm btn-delete ml-2 mr-2 "
            data-id="{{ $row->id }}">
        <img src="{{asset('/ico/refuseunit.png')}}" alt="" class="img-16 ml-2 mr-2">
        <span> Eliminar</span>
    </button>
@endcan

@can('inventoryheads.clearuser')
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm btn-clear01 ml-2 mr-2 "
            data-id="{{ $row->id }}">
        <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-2 mr-2">
        <span> Desasignar 1</span>
    </button>
@endcan
@can('inventoryheads.clearuser')
    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm btn-clear02 ml-2 mr-2 "
            data-id="{{ $row->id }}">
        <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-2 mr-2">
        <span> Desasignar 2</span>
    </button>
@endcan