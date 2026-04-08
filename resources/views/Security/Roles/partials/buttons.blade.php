@can('securityroles.edit')
    <a href="{{url('securityroles/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"
       title="Modificar Registro">
        @include('buttons.edit')
    </a>
@endcan
@can('securityroles.destroy')
    <a href="" data-target="#modal-deleterole-{{$id}}" data-toggle="modal" >
        <img src="{{asset('/ico/refuseunit.png')}}" alt="" class="img-16 ml-2">
    </a>
    @include('Security.Roles.destroy')
@endcan
@can('securityroles.conceptajust')
    <a href="{{url('securityroles/' . $id . '/conceptajust')}}" data-toggle="tooltip" data-placement="top"
       title="Conceptos de Ajuste">
        <img src="{{asset('/ico/storageareas.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan

@can('securityroles.parameters')
    <a href="{{url('securityroles/' . $id . '/parameters')}}" data-toggle="tooltip" data-placement="top"
       title="Agregar Parametros">
        <img src="{{asset('/ico/systemparameters.png')}}" alt="" class="img-16 ml-2">
    </a>
@endcan