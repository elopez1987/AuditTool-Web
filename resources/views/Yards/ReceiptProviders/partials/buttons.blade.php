@can('receiptproviders.edit')
    <a href="{{url('receiptproviders/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('receiptproviders.addcontacts')
    <a href="{{url('receiptprovidercontacts/' . $id . '/create')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar Contacto">
        <img src="{{asset('/ico/addcontact.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan

@can('receiptproviders.destroy')
    <a  onclick="deleteproviders({!! $id !!})" >
        <img src="{{asset('/ico/remove_delete_exit_close_1545.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan