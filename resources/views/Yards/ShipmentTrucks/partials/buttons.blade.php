@can('shipmenttrucks.edit')
    <a href="{{url('shipmenttrucks/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('shipmenttrucks.destroy')
    <a href="" data-target="#modal-delete-{{$id}}"
       data-toggle="modal">
        <img src="{{asset('/ico/addestroy1.png')}}" alt=""
             class="img-16 ml-2 mr-2">
    </a>
    @include('Yards.ShipmentTrucks.delete')
@endcan

@can('shipmenttrucks.tolerance')
    <a href="{{url('shipmenttrucks/' . $id. '/tolerance')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar tolerancias">
        <img src="{{asset('/ico/filter_filters_funnel_list_navigation_sort_sorting_icon_123212.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
