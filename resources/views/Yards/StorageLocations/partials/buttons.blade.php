@can('storagelocations.edit')
    <a href="{{url('storagelocations/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('storagelocations.destroy')
    <a href="" data-target="#modal-delete-{{$id}}"
       data-toggle="modal">
        <img src="{{asset('/ico/addestroy1.png')}}" alt=""
             class="img-16 ml-2">
    </a>
    @include('Yards.StorageLocations.delete')
@endcan

@can('storagelocations.parameters')
    <a href="{{url('storagelocations/' . $id . '/parameters')}}" data-toggle="tooltip"
       data-placement="top"
       title="Agregar Parametros">
        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan
