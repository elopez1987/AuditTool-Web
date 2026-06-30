@if($statusid != 'TD')
    {{--Permiso para personal de trafico--}}
    @can('storagescheduleinverse.edit')
        <a href="{{url('storagescheduleinversetraffic/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro">
            @include('buttons.edit')
        </a>
    @endcan

    {{--Permiso para personal de inventarios--}}
    @can('updateDataInventory')
        <a href="{{url('updateDataInventory/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro Inventario">
            <img src="{{asset('/ico/viewdetails.png')}}" alt="" class="img-16 ml-1 mr-2">
        </a>
    @endcan
@endif

{{--Permiso para personal de operaciones--}}
@can('updateDataOperation')
    <a href="{{url('updateDataOperation/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro Operaciones">
        <img src="{{asset('/ico/cheklistin.png')}}" alt="" class="img-16 ml-1 mr-2">
    </a>
@endcan

@if($statusid != 'TD')
    @can('storagescheduleinverse.close')
        <a onclick="closeInverse({!! $id !!})">
            <img src="{{asset('/ico/remove_delete_exit_close_1545.png')}}" alt="" class="img-16 ml-1 mr-2">
        </a>
    @endcan
@endif
