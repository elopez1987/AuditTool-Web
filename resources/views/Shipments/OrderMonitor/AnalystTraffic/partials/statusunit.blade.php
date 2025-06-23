@if ($entrydate == null )
    <span class="label label-warning" style="font-size: 0.675rem !important;;color: black"> Sin reportarse </span>
@endif
@if ($entrydate != null)
    @if($entrydate > $loadingdate)
        <span class="label label-danger " style="font-size: 0.675rem !important;;"> Fuera de tiempo </span>
    @elseif ($entrydate < $loadingdate)
        <span class="label label-success" style="font-size: 0.675rem !important;;"> En tiempo </span>
    @endif
@endif
