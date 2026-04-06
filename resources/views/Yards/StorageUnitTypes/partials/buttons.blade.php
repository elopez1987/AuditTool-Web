@can('storageunittypes.edit')
    <a href="{{url('storageunittypes/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan


@can('storageunittypes.destroy')
    <a  onclick="deleteUnittype({!! $id !!})" >
        <img src="{{asset('/ico/remove_delete_exit_close_1545.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan