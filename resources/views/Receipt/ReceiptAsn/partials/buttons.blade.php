@if($statusid != 'TR')
    @if($statusid != 'CA')
        @can('receiptasn.edit')
            <a href="{{url('receiptasn/' . $uuid_asn . '/edit')}}" class="btn waves-effect waves-light btn-outline-info btn-sm ml-2 mr-2">
                <i class="mdi mdi-pencil font-12"></i>
            </a>
        @endcan
    @endif
@endif
@if ($statusid != 'TR')
    @if($statusid != 'CA')
        @can('receiptasn.checklistant')
            <a href="{{url('receiptasn/' . $uuid_asn . '/checklist')}}"  class="btn waves-effect waves-light btn-outline-info btn-sm ml-2 mr-2">
                <i class="mdi mdi-view-list font-12"></i>
            </a>
        @endcan
    @endif
@endif
@can('addstock')
    @if ($statusid != 'TR' )
        @if($statusid != 'CA')
            <a href="{{url('receiptasn/' . $uuid_asn . '/addstock')}}"  class="btn waves-effect waves-light btn-outline-info btn-sm ml-2 mr-2">
                <i class="mdi mdi-shopping font-12"></i>
            </a>
        @endif
    @endif
@endcan
@can('receiptasn.show')
    <a href="{{url('receiptasn/' . $uuid_asn )}}" class="btn waves-effect waves-light btn-outline-info btn-sm ml-2 mr-2">
        <i class="mdi mdi-account-search font-12 "></i>
    </a>
@endcan

