<a href="{{url('systemschemas/' . $schemaid . '/edit')}}" data-toggle="tooltip" data-placement="top"
   title="Modificar Registro">
    @include('buttons.edit')
</a>

<a href="{{url('systemschemas/' . $schemaid . '/createstatus')}}" data-toggle="tooltip" data-placement="top"
   title="Agregar Estatus">
    <img src="{{asset('/ico/systemschemas.png')}}" alt="" class="img-16 ml-2">
</a>

