@can('receiptproviders.edit')
    <a href="{{url('receiptproviders/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

