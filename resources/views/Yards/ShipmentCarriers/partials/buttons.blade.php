@can('shipmentcarriers.edit')
    <a href="{{url('shipmentcarriers/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan
@can('shipmentcarriers.parameters')
    <a href="{{url('shipmentcarriers/' . $id . '/parameters')}}" data-toggle="tooltip"
       data-placement="top"
       title="Agregar Parametros">
        <img src="{{asset('/ico/systemsettings.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
{{--shipmentcarriers/{id}/contacts--}}
@can('shipmentcarriers.contacts')
    <a href="{{url('shipmentcarriers/' . $id . '/contacts')}}" data-toggle="tooltip"
       data-placement="top"
       title="Agregar contactos">
        <img src="{{asset('/ico/receiptasn.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan