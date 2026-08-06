@if(is_null($deleted_at))
    @can('storagelocations.edit')
        <a href="{{url('storagelocations/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro">
            @include('buttons.edit')
        </a>
    @endcan

    @can('storagelocations.parameters')
        <a href="{{url('storagelocations/' . $id . '/parameters')}}" data-toggle="tooltip"
           data-placement="top"
           title="Agregar Parametros">
            <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-2 mr-2">
        </a>
    @endcan
@endif

@can('storagelocations.destroy')
    <a href="javascript:void(0)" class="btn-delete-location" data-id="{{ $id }}" data-toggle="tooltip"
       data-placement="top" title="{{ empty($deleted_at) ? 'Eliminar Registro' : 'Restaurar Registro' }}">
        <img src="{{ asset(empty($deleted_at) ? '/ico/addestroy1.png' : '/ico/typemovements.png') }}"
             alt="" class="img-16 ml-2">
    </a>
@endcan