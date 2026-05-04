@if($centerid == 'HENKELTOL01')
    @if($folionumber != null && $statusunit != 'TR')
        @php
            $minutes = \Carbon\Carbon::now()->diffInRealMinutes($entrydate);
            $diff = \Carbon\Carbon::parse($entrydate)->diff(\Carbon\Carbon::now());
            $label = match(true) {
                $minutes < 51          => 'label-success',
                $minutes <= 70         => 'label-warning',
                default                => 'label-danger',
            };
            $text = $minutes < 51
                ? "{$diff->h} h {$diff->i} m"
                : "{$minutes} Min";
        @endphp
        <span class="label {{ $label }} font-table font-12">{{ $text }}</span>

    @elseif($folionumber != null && $statusunit == 'TR')
        <span class="label label-success font-table font-12">Unidad fuera de patio</span>
    @endif
@endif
