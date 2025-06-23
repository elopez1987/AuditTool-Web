@if($statusid != 'RSE-02')

    @can('storagesafetytours.edit')
        <a href="{{url('storagesafetytours/' . $touruuid. '/edit')}}">
            @include('buttons.edit')
        </a>
    @endcan

    @can('storagesafetytours.destroy')
        <a href="" data-target="#modal-destroy-{{$touruuid}}" data-toggle="modal">
            <img src="{{asset('/ico/destroy2.png')}}" alt=""
                 class="img-16 ml-2">
        </a>
        @include('SafetyTours.StorageSafetyTours.destroy')
    @endcan
@endif

@can('storagesafetytours.show')
    <a href="{{url('storagesafetytours/' . $touruuid)}}">
        <img src="{{asset('/ico/storagepanel.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
