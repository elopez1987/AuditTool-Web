@can('securityfunctions.edit')
    <div class="btn-group">
        <a href="{{url('securityfunctions/' . $functionid . '/edit')}}" data-toggle="tooltip" data-placement="top"
           title="Modificar Registro">
            @include('buttons.edit')
        </a>
    </div>
@endcan
@can('securityfunctions.destroy')
    <div class="btn-group">
        <a href="" data-target="#modal-delete-{{$functionid}}"
           data-toggle="modal">
            <img src="{{asset('/ico/addestroy.png')}}" alt="" class="img-16">
        </a>
        @include('Security.SecurtiyFunctions.partials.destroy')
    </div>
@endcan
