@can('shipmenttruckcategories.edit')
    <a href="{{url('shipmenttruckcategories/' . $truckcategoryid. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

