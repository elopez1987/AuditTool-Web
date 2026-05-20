@can('inventorypartnumbers.edit')
    <a href="{{url('inventorypartnumbers/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan

@can('inventorypartnumbers.adddata01')
    <a href="{{url('inventorypartnumbers/' . $id. '/adddata01')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar datos">
        <img src="{{asset('/ico/addshipment.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan

@can('inventorypartnumbers.parameter')
    <a href="{{url('inventorypartnumbers/' . $id. '/parameters')}}" data-toggle="tooltip" data-placement="top"
       title="Parametrizar">
        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
