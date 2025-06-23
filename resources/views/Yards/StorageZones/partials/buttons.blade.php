@can('storagezones.edit')
    <a href="{{url('storagezones/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('storagezones.parametrizar')
    <a href="{{url('storagezones/' . $id . '/parameters')}}" data-toggle="tooltip"
       data-placement="top"
       title="Agregar Parametros">
        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan

@can('storagezones.destroy')
    <a  onclick="deletezone({!! $id !!})" >
        <img src="{{asset('/ico/remove_delete_exit_close_1545.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
