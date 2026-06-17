@if($statusid != 'IRS-03')
    @can('storage-incidence-safeties.edit')
        <a href="{{url('storage-incidence-safeties/' . $id. '/edit')}}">
            @include('buttons.edit')
        </a>
    @endcan
@endif

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
