{{--@can('securityroles.edit')--}}
{{--    <a href="{{url('securityroles/' . $id . '/edit')}}" data-toggle="tooltip" data-placement="top"--}}
{{--       title="Modificar Registro">--}}
{{--        @include('buttons.edit')--}}
{{--    </a>--}}
{{--@endcan--}}
@can('activeusers.destroy')
    <a href="" data-target="#modal-delete-{{$user_id}}" data-toggle="modal" >
        <img src="{{asset('/ico/refuseunit.png')}}" alt="" class="img-16 ml-2">
    </a>
    @include('Security.ActiveUsers.destroy')
@endcan
{{--@can('securityroles.conceptajust')--}}
{{--    <a href="{{url('securityroles/' . $id . '/conceptajust')}}" data-toggle="tooltip" data-placement="top"--}}
{{--       title="Conceptos de Ajuste">--}}
{{--        <img src="{{asset('/ico/storageareas.png')}}" alt="" class="img-16 ml-2">--}}
{{--    </a>--}}
{{--@endcan--}}
