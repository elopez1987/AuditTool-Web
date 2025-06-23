@can('securitymenustructure.edit')
    <div class="btn-group">
        <a href="{{url('securitymenustructure/' . $menustrucid . '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro">
            @include('buttons.edit')
        </a>
    </div>
@endcan
@can('securitymenustructure.destroy')
    <div class="btn-group">
        <a href="" data-target="#modal-delete-{{$menustrucid}}"
           data-toggle="modal">
            <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16">
        </a>
        @include('Security.SecurityMenu.partials.destroy')
    </div>
@endcan
