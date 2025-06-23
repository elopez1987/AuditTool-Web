@can('storagelabeltrucks.edit')
    <a href="{{url('storagelabeltrucks/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan
@can('printlabeltruck')
    <a href="{{url('printlabeltruck/' . $id )}}" data-toggle="tooltip" data-placement="top"
       title="Imprimir etiqueta">
        <img src="{{asset('/ico/printer.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
@can('storagelabeltrucks.destroy')
    <a href="" data-target="#modal-destroy-{{$id}}" data-toggle="modal" data-toggle="tooltip"
       data-placement="top" title="Eliminar registro">
        <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16 ml-2">
    </a>
    @include('Yards.StorageLabelTrucks.destroy')
@endcan

