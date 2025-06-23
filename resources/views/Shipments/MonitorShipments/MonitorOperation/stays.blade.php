@php use Carbon\Carbon; @endphp
@if(!empty($entrydate))
    @if($stay > 180 && $stay < 270)
    <span class="label label-orange font-table">
        {{Carbon::now()->diff($entrydate)->format('%H:%i h/m')}}
    </span>
    @elseif( $stay > 271)
    <span class="label label-red font-table">
      {{Carbon::now()->diff($entrydate)->format('%H:%i h/m')}}
    </span>
    @elseif($stay < 180)
        <span class="label label-green font-table text-black">
        {{Carbon::now()->diff($entrydate)->format('%H:%i h/m')}}
    </span>
    @endif
@endif

