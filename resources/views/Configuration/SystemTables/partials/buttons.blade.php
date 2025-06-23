<a href="{{url('systemtables/' . $tableid. '/edit')}}" data-toggle="tooltip" data-placement="top"
   title="Modificar Registro">
    @include('buttons.edit')
</a>

<a href="{{url('systemtables/' . $tableid. '/addColumnToTable')}}" data-toggle="tooltip" data-placement="top"
   title="Agregar columna">
    <img src="{{asset('/ico/systemname.png')}}" alt="" class="img-16 ml-1">
</a>
