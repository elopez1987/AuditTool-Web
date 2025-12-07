@can('systemwarehouses.edit')
    <a href="{{url('systemwarehouses/' . $warehouseid . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan
@can('systemwarehouses.parameters')
    <a href="{{url('systemwarehouses/' . $warehouseid . '/parameters')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar Parametros">
        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan

@can('systemwarehouses.notifications')
    <a href="{{url('systemwarehouses/' . $warehouseid . '/notifications')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar Notificaciones">
        <img src="{{asset('/ico/telegram_icon_130816.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan
