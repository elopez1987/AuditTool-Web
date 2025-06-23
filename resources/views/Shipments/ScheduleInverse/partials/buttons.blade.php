{{--@can('scheduleinverse.edit')--}}
{{--    <a href="{{url('scheduleinverse/' . $id. '/edit')}}" data-toggle="tooltip" data-placement="top"--}}
{{--       title="Modificar Registro">--}}
{{--        @include('buttons.edit')--}}
{{--    </a>--}}
{{--@endcan--}}

@can('scheduleinverse.show')
    <a href="{{url('scheduleinverseshow/' . $id )}}" data-toggle="tooltip"
       data-placement="top"
       title="Detalle">
        <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan


@can('scheduleinverse.activate')
    @if($statusid != 'ACT')
        <a onclick="activateschedulle({!! $id !!})">
            <img src="{{asset('/ico/typemovements.png')}}" alt="" class="img-16 ml-1">
        </a>
    @endif
@endcan
