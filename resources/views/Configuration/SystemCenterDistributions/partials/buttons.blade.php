@can('systemcenterdistributions.edit')
    <a href="{{url('systemcenterdistributions/' . $centerid . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan
@can('systemcenterdistributions.parametrizar')
    <a href="{{url('systemcenterdistributions/' . $centerid . '/parameters')}}" data-toggle="tooltip"
       data-placement="top"
       title="Agregar Parametros">
        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan
@can('systemcenterdistributions.folder')
    <a href="{{url('systemcenterdistributions/' . $centerid . '/folder')}}" data-toggle="tooltip"
       data-placement="top"
       title="Agregar Directorios">
        <img src="{{asset('/ico/folders.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan

@can('systemcenterdistributions.parametrizarapp')
    <a href="{{url('systemcenterdistributions/' . $centerid . '/parametersapp')}}" data-toggle="tooltip"
       data-placement="top"
       title="Parametros de App">
        <img src="{{asset('/ico/phone.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan
@can('systemcenterdistributions.notification')
    <a href="{{url('systemcenterdistributions/' . $centerid . '/notifications')}}" data-toggle="tooltip"
       data-placement="top"
       title="Notificaciones">
        <img src="{{asset('/ico/telegram_icon_130816.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan