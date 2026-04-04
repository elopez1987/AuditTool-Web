@can('systemwhatsapps.edit')
    <a href="{{url('systemwhatsapps/' . $whatsappid . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        <img src="{{asset('/ico/adedit.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan
@can('systemwhatsapps.adddetailwth')
    <a href="{{url('systemwhatsapps/' . $whatsappid . '/createNotificationDetail')}}" data-toggle="tooltip"
       data-placement="top"
       title="Agregar contactos">
        <img src="{{asset('/ico/storagetask.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan

@can('systemwhatsapps.show')
    <a href="{{url('systemwhatsapps/' . $whatsappid )}}" data-toggle="tooltip"
       data-placement="top"
       title="Log de eventos">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan
