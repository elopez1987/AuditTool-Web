@can('storagereasons.edit')
    <a href="{{url('storagereasons/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('storagereasons.parameters')
    <a href="{{url('storagereasons/' . $id . '/parameters')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar Parametros">
        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan