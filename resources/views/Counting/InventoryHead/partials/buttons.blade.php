@can('inventoryhead.edit')
    @if($statusid != 'TO')
        <a href="{{url('inventoryhead/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Inventario">
            @include('buttons.edit')
        </a>
    @endif
@endcan
@if($statusid != 'PN')
    @if($inventorytype == 'APP')
        @can('inventoryhead.show')
            <a href="{{url('inventoryhead/' . $id)}}" data-toggle="tooltip" data-placement="top"
               title="Ver graficas">
                <img src="{{asset('/ico/dashboard.png')}}" alt="" class="img-16 ml-2">
            </a>
        @endcan
    @endif
    @if($inventorytype == 'DRON')
        @can('inventoryhead.show')
            <a href="{{url('inventoryheaddron/' . $id)}}" data-toggle="tooltip" data-placement="top"
               title="Ver detalles">
                <img src="{{asset('/ico/dashboard.png')}}" alt="" class="img-16 ml-2">
            </a>
        @endcan
    @endif
@endif

@can('inventoryhead.active')
    @if($statusid == 'PN')
        <a href="" data-target="#modal-active-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16 ml-2">
        </a>
        @include('Counting.InventoryHead.activeInventory')
    @endif
@endcan
@can('inventoryhead.close')
    @if($statusid == 'AT')
        <a href="" data-target="#modal-close-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/close.png')}}" alt="" class="img-16 ml-2">
        </a>
        @include('Counting.InventoryHead.closeInventory')
    @endif
@endcan
@can('inventoryhead.destroy')
    @if($statusid != 'TO')
        <a href="" data-target="#modal-destroy-{{$id}}" data-toggle="modal">
            <img src="{{asset('/ico/destroy2.png')}}" alt="" class="img-16 ml-2">
        </a>
        @include('Counting.InventoryHead.destroyInventory')
    @endif
@endcan
@if($statusid != 'PN')
    @if($inventorytype == 'APP')
        @can('resetinventory')
            @if($statusid != 'TO')
                <a href="" data-target="#modal-reset-{{$id}}" data-toggle="modal">
                    <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-2">
                </a>
                @include('Counting.InventoryHead.reset')
            @endif
        @endcan
    @endif

    @if($inventorytype == 'DRON')
        @can('resetinventory')
            <a href="" data-target="#modal-resetdron-{{$id}}" data-toggle="modal">
                <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-2">
            </a>
            @include('Counting.InventoryHead.resetdron')
        @endif
    @endcan
@endif
