@can('inventorylabels.edit')
    <a href="{{url('inventorylabels/' . $labelid. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('inventorylabels.addDetailLabel')
    <a href="{{url('inventorylabels/' . $labelid. '/addDetailLabel')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar detalle">
        <img src="{{asset('/ico/configuration_config_system_1540.ico')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
