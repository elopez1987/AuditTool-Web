@can('storagesafetytourtype.edit')
    <a href="{{url('storagesafetytourtype/' . $typeuuid. '/edit')}}" >
        @include('buttons.edit')
    </a>
@endcan
@can('storagesafetytourtype.show')
    <a href="{{url('storagesafetytourtype/' . $typeuuid )}}" >
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan
@can('storagesafetytourtype.destroy')
    <a href="" data-target="#modal-destroy-{{$typeuuid}}" data-toggle="modal" >
        <img src="{{asset('/ico/destroy2.png')}}" alt=""
             class="img-16 ml-2">
    </a>
    @include('SafetyTours.StorageSafetyTourType.destroy')
@endcan
