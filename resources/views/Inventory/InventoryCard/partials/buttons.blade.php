@can('inventorycards.edit')
    <a href="{{url('inventorycards/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('inventorycards.show')
    <a href="{{url('inventorycards/' . $id )}}" data-toggle="tooltip" data-placement="top"
       title="Agregar ubicación">
        <img src="{{asset('/ico/adagregar1.png')}}" alt="" class="img-16 ml-2 mr-2">

    </a>
@endcan


