@can('label-dictionary.edit')
    <a href="javascript:void(0)" onclick="editLabel({{ $id }})" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('label-dictionary.destroy')
    <a href="javascript:void(0)" onclick="deleteLabel({{ $id }})" data-toggle="tooltip" data-placement="top"
       title="Eliminar Registro">
        <img src="{{asset('/ico/remove_delete_exit_close_1545.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
