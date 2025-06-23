@can('editokens.edit')
    <a href="{{url('editokens/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('editokens.destroy')
    <a  onclick="deletetoken({!! $id !!})" >
        <img src="{{asset('/ico/remove_delete_exit_close_1545.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
