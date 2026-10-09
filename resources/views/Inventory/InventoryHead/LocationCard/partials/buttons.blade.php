{{--@can('inventoryheads.validatelocation')--}}
@if($type === 'D')
    <a href="{{url('validate-location/' . $id )}}" data-toggle="tooltip" data-placement="top"
       title="Validar fotografias">
        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm"
                data-toggle="tooltip" data-placement="top" title="Modificar">
            <img src="{{asset('/ico/galeria-de-fotos.png')}}" alt="" class="img-16 ml-2  mr-2">
            <span> Validar Ubicación</span>
        </button>
    </a>
@endif
{{--@endcan--}}


{{--@can('inventoryheads.3ercon')--}}
{{--    <a href="" data-target="#modal-3erconteo-{{$id}}" data-toggle="modal" data-placement="top">--}}
{{--        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"--}}
{{--                data-toggle="tooltip" data-placement="top" title="Activar Inventario">--}}
{{--            <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16">--}}
{{--            <span style="font-size: 11px;"> 3er conteo </span>--}}
{{--        </button>--}}
{{--    </a>--}}
{{--    @include('Inventory.InventoryHead.LocationCard.3erconteo')--}}
{{--@endcan--}}

