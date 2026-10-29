@if($statusid != 'TR')
    @if($statusid != 'CA')
        @can('receiptasn.edit')
            <a href="{{url('receiptasn/' . $uuid_asn . '/edit')}}">
                @include('buttons.edit')
            </a>
        @endcan
    @endif
@endif
@if ($statusid != 'TR')
    @if($statusid != 'CA')
        @can('receiptasn.checklistant')
            <a href="{{url('receiptasn/' . $uuid_asn . '/checklist')}}">
                <img src="{{asset('/ico/checklistin.png')}}" alt="" class="img-16 ml-1 mr-1">
            </a>
        @endcan
    @endif
@endif
@can('addstock')
    @if ($statusid != 'TR' )
        @if($statusid != 'CA')
            <a href="{{url('receiptasn/' . $uuid_asn . '/addstock')}}">
                <img src="{{asset('/ico/storageareas1.png')}}" alt="" class="img-16 ml-1 mr-1">
            </a>
        @endif
    @endif
@endcan
@can('receiptasn.show')
    <a href="{{url('receiptasn/' . $uuid_asn )}}">
        <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16 ml-1">
    </a>
@endcan

