<div class="btn-group">
    <a href="{{url('systemparameters/' . $parameterid . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
</div>
@can('adddetailparameter')
    <a href="{{url('detailparameter/' . $parameterid . '/add')}}" data-toggle="tooltip" data-placement="top"
       title="Detalle del parametro">
        <img src="{{asset('/ico/systemschemas.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
@can('deleteparameter')
    <a href="" data-target="#modal-delete-{{$parameterid}}"
       data-toggle="modal">
        <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16 ml-2">
    </a>
    @include('Configuration.SystemParameters.delete')
@endcan
