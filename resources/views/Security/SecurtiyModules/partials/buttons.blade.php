@can('securitymodules.edit')
    <div class="btn-group">
        <a href="{{url('securitymodules/' . $moduleid . '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro">
            @include('buttons.edit')
        </a>
    </div>
@endcan
@can('securitymodules.destroy')
    <div class="btn-group">
        <a href="" data-target="#modal-delete-{{$moduleid}}" data-toggle="modal">
            <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16">
        </a>
        @include('Security.SecurtiyModules.partials.destroy')
    </div>
@endcan
