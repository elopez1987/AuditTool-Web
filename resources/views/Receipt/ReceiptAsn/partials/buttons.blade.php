@if($statusid != 'IG')
    @if($statusid != 'CE')
        @can('receiptasn.edit')
            <a href="{{url('receiptasn/' . $id . '/edit')}}">
                @include('buttons.edit')
            </a>
        @endcan
    @endif
@endif
@if ($statusid != 'IG')
    @if($statusid != 'CE')
        @can('receiptasn.checklistant')
            <a href="{{url('receiptasn/' . $id . '/checklist')}}">
                <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-1 mr-1">
            </a>
        @endcan
    @endif
@endif
@can('addstock')
    @if ($statusid != 'IG' )
        @if($statusid != 'CE')
            <a href="{{url('receiptasn/' . $id . '/addstock')}}">
                <img src="{{asset('/ico/storageareas1.png')}}" alt="" class="img-16 ml-1 mr-1">
            </a>
        @endif
    @endif
@endcan
@can('receiptasn.show')
    <a href="{{url('receiptasn/' . $id )}}">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan

