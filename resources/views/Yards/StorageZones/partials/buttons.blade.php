@if(is_null($deleted_at))
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
@endif

@can('storagezones.destroy')
    <a onclick="deletezone({!! $id !!})" data-toggle="tooltip"
       data-placement="top" title="{{ empty($deleted_at) ? 'Eliminar Registro' : 'Restaurar Registro' }}">
        <img src="{{ asset(empty($deleted_at) ? '/ico/addestroy1.png' : '/ico/typemovements.png') }}"
             alt="" class="img-16 ml-2">
    </a>
@endcan
