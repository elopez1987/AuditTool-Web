@can('responsible-area-security.edit')
    <a href="{{url('responsible-area-security/' . $id. '/edit')}}">
        @include('buttons.edit')
    </a>
@endcan

@can('responsible-area-security.emails')
    <a href="{{url('responsible-area-emails/' . $id. '/emails')}}">
        <img src="{{asset('/ico/Email_30017.png')}}" alt="" class="img-16 ml-2 mr-2">
    </a>
@endcan

{{--@can('responsible-area-security.destroy')--}}
{{--    <a href="" data-target="#modal-destroy-{{$id}}" data-toggle="modal">--}}
{{--        <img src="{{asset('/ico/destroy2.png')}}" alt=""--}}
{{--             class="img-16 ml-2">--}}
{{--    </a>--}}
{{--    @include('SafetyTours.responsible-area-security.destroy')--}}
{{--@endcan--}}


{{--@can('responsible-area-security.show')--}}
{{--    <a href="{{url('responsible-area-security/' . $id)}}">--}}
{{--        <img src="{{asset('/ico/storagepanel.png')}}" alt="" class="img-16 ml-1">--}}
{{--    </a>--}}
{{--@endcan--}}
