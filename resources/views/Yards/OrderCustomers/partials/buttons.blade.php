@can('ordercustomers.edit')
    <a href="{{url('ordercustomers/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('ordercustomers.parameters')
    <a href="{{url('ordercustomers/' . $id . '/parameters')}}" data-toggle="tooltip"
       data-placement="top"
       title="Agregar Parametros">
        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
