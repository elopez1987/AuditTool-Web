@if($folionumber != null)
    @if($statusunit != 'TR')
        @if( Carbon\Carbon::now()->diffInRealMinutes($entrydate) < 51)
            @php
                $diff = \Carbon\Carbon::parse($entrydate)->diff(\Carbon\Carbon::now());
            @endphp
            <span class="label label-success font-table">
               {{ $diff->h }}h {{ $diff->i }}m
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
