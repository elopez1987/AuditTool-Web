@can('storagetags.edit')
    <a href="{{url('storagetags/' . $taguuid. '/edit')}}" >
        @include('buttons.edit')
    </a>
@endcan
@can('storagetags.destroy')
    <a href="" data-target="#modal-destroy-{{$taguuid}}" data-toggle="modal" >
        <img src="{{asset('/ico/destroy2.png')}}" alt=""
             class="img-16 ml-2">
    </a>
    @include('SafetyTours.StorageTags.destroy')
@endcan
