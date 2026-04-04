@can('storagechecklists.edit')
    <a href="{{url('storagechecklists/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('createchecklistdetails')
    <a href="{{url('createchecklistdetails/' . $id)}}" data-toggle="tooltip" data-placement="top"
       title="Detalle del Check List">
        <img src="{{asset('/ico/checklistdetails.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan

@can('storagechecklists.destroy')
    <a  onclick="deletechecklist({!! $id !!})" >
        <img src="{{asset('/ico/remove_delete_exit_close_1545.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan