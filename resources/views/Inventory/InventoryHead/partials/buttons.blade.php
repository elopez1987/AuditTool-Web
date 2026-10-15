@if($statusid == 'INF-01')
    @can('inventoryheads.edit')
        <a href="{{url('inventoryheads/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro">
            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                    data-toggle="tooltip" data-placement="top" title="Modificar">
                <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16">
                <span style="font-size: 11px;"> Modificar </span>
            </button>
        </a>
    @endcan

    @can('inventoryheads.activate')
        <a href="" data-target="#modal-activate-{{$id}}" data-toggle="modal" data-placement="top">
            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                    data-toggle="tooltip" data-placement="top" title="Activar Inventario">
                <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16">
                <span style="font-size: 11px;"> Activar </span>
            </button>
        </a>
        @include('Inventory.InventoryHead.Events.activate-inventory')
    @endcan
@endif

@can('inventoryheads.show')
    <a href="{{url('inventoryheads/' . $inventory_uuid )}}" data-toggle="tooltip" data-placement="top"
       title="Detalle del Inventario">
        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                data-toggle="tooltip" data-placement="top" title="Dashboard">
            <img src="{{asset('/ico/dashboard.png')}}" alt="" class="img-16">
            <span style="font-size: 11px;"> Dashboard </span>
        </button>
    </a>
@endcan

@if($statusid == 'INF-02')
    @can('inventoryheads.close')
        <a href="" data-target="#modal-close-{{$id}}" data-toggle="modal" data-placement="top">
            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                    data-toggle="tooltip" data-placement="top" title="Cerrar Inventario">
                <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16">
                <span style="font-size: 11px;"> Cerrar Inv.</span>
            </button>
        </a>
        @include('Inventory.InventoryHead.Events.close-inventory')
    @endcan

    @can('inventoryheads.initialize')
        <a href="" data-target="#modal-initialize-{{$id}}" data-toggle="modal" data-placement="top">
            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                    data-toggle="tooltip" data-placement="top" title="Cerrar Inventario">
                <img src="{{asset('/ico/adreload.png')}}" alt="" class="img-16">
                <span style="font-size: 11px;"> Reiniciar Inv.</span>
            </button>
        </a>
        @include('Inventory.InventoryHead.Events.initialize-inventory')
    @endcan
@endif

@if($statusid !== 'INF-03')
    @can('inventoryheads.delete')
        <a href="" data-target="#modal-delete-{{$id}}" data-toggle="modal" data-placement="top">
            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                    data-toggle="tooltip" data-placement="top" title="Eliminar Inventario">
                <img src="{{asset('/ico/refuseunit.png')}}" alt="" class="img-16">
                <span style="font-size: 11px;"> Eliminar Inv.</span>
            </button>
        </a>
        @include('Inventory.InventoryHead.Events.delete-inventory')
    @endcan
@endif
