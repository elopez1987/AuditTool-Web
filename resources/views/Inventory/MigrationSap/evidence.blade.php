@if(!empty($photo))
    <a class="btn btn-sm btn-outline-info image-popup-vertical-fit" href="{{asset('storage/' . $photo)}}" alt="Evidencia" >
        <i class="ti ti-camera"></i> Ver foto
    </a>
@else
    <span class="text-muted">Sin Evidencia</span>
@endif
