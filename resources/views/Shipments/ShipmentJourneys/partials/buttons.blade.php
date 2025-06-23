@can('shipmentjourney.edit')
    <a href="{{url('shipmentjourney/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

