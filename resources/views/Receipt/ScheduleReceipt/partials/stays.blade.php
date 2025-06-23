@if($folionumber != null)
    @if($statusunit != 'TR')
        @if( Carbon\Carbon::now()->diffInRealMinutes($entrydate) < 51)
            <span class="label label-success font-table">
        {{Carbon\Carbon::now()->diffInRealMinutes($entrydate)}} Min
    </span>
        @elseif(Carbon\Carbon::now()->diffInRealMinutes($entrydate) >=  51 && Carbon\Carbon::now()->diffInRealMinutes($entrydate) <= 70 )
            <span class="label label-warning font-table">
        {{Carbon\Carbon::now()->diffInRealMinutes($entrydate)}} Min
    </span>
        @else
            <span class="label label-danger font-table">{{Carbon\Carbon::now()->diffInRealMinutes($entrydate)}} Min </span>
        @endif
    @else
        <span class="label label-success font-table">
      Fuera de Patio
    </span>
    @endif
@endif
