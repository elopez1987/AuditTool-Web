<style>
    .timeline-horizontal {
        overflow-x: auto;
        overflow-y: visible;
        padding: 20px 0;
    }
    
    .timeline-horizontal ul {
        position: relative;
        padding: 20px 0;
        min-width: max-content;
    }
    
    /* Línea horizontal que conecta los eventos */
    .timeline-horizontal ul::before {
        content: '';
        position: absolute;
        top: 40px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, #17a2b8 0%, #17a2b8 100%);
        z-index: 0;
        margin: 0 100px;
    }
    
    .timeline-item {
        flex: 0 0 auto;
        position: relative;
    }
    
    .timeline-badge {
        position: relative;
        z-index: 2;
        background-color: #17a2b8 !important;
        box-shadow: 0 0 0 4px #fff, 0 0 0 5px #17a2b8;
    }
    
    /* Responsive: en pantallas pequeñas mantener la línea visible */
    @media (max-width: 768px) {
        .timeline-horizontal {
            padding-bottom: 30px;
        }
        
        .timeline-horizontal ul::before {
            margin: 0 75px;
        }
        
        .timeline-item {
            min-width: 120px !important;
            max-width: 150px !important;
        }
        
        .timeline-panel h6 {
            font-size: 9px !important;
        }
        
        .timeline-panel p {
            font-size: 8px !important;
        }
    }
    
    @media (max-width: 576px) {
        .timeline-horizontal ul::before {
            margin: 0 60px;
        }
        
        .timeline-item {
            min-width: 100px !important;
            max-width: 120px !important;
        }
    }
</style>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Timeline de Eventos </h5>
            </div>
            <div class="card-body">
                <div class="timeline-horizontal">
                    <ul class="list-unstyled d-flex flex-wrap justify-content-between align-items-start position-relative">

{{--                        Lanzamiento de la orden:--}}
                        <li class="timeline-item text-center" style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Inicio de preparación</h6>
                                @if($events->lanzamientoola )
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->lanzamientoola)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->lanzamientoola)->format('H:i') }}</p>
                                @endif
                            </div>
                            @php
                                $prev = $events->lanzamientoola ? \Carbon\Carbon::parse($events->lanzamientoola) : null;
                                $curr = $events->preparadoenpiso ? \Carbon\Carbon::parse($events->preparadoenpiso) : null;
                            @endphp
                            @if($prev && $curr)
                                @php
                                    $diff = $prev->diff($curr);
                                    $hours = ($diff->d * 24) + $diff->h;
                                    $minutes = $diff->i;
                                @endphp
                                <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                    {{ $hours }}h {{ $minutes }}m
                                </div>
                            @endif
                        </li>

{{--                        Fin de preparacion--}}
                        <li class="timeline-item text-center" style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Fin de preparación</h6>
                                @if($events->preparadoenpiso)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->preparadoenpiso)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->preparadoenpiso)->format('H:i') }}</p>
                                @endif
                            </div>
                            @php
                                $prev = $events->preparadoenpiso ? \Carbon\Carbon::parse($events->preparadoenpiso) : null;
                                $curr = $events->inicioverificacion ? \Carbon\Carbon::parse($events->inicioverificacion) : null;
                            @endphp
                            @if($prev && $curr)
                                @php
                                    $diff = $prev->diff($curr);
                                    $hours = ($diff->d * 24) + $diff->h;
                                    $minutes = $diff->i;
                                @endphp
                                <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                    {{ $hours }}h {{ $minutes }}m
                                </div>
                            @endif
                        </li>

                        <li class="timeline-item text-center" style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Inicio de verificación</h6>
                                @if($events->inicioverificacion )
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->inicioverificacion)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->inicioverificacion)->format('H:i') }}</p>
                                @endif
                            </div>
                            @php
                                $prev = $events->inicioverificacion ? \Carbon\Carbon::parse($events->inicioverificacion) : null;
                                $curr = $events->finverificacion ? \Carbon\Carbon::parse($events->finverificacion) : null;
                            @endphp
                            @if($prev && $curr)
                                @php
                                    $diff = $prev->diff($curr);
                                    $hours = ($diff->d * 24) + $diff->h;
                                    $minutes = $diff->i;
                                @endphp
                                <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                    {{ $hours }}h {{ $minutes }}m
                                </div>
                            @endif
                        </li>

                        <li class="timeline-item text-center" style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Fin de verificación</h6>
                                @if($events->finverificacion)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->finverificacion)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->finverificacion)->format('H:i') }}</p>
                                @endif
                            </div>
                            @php
                                $prev = $events->finverificacion ? \Carbon\Carbon::parse($events->finverificacion) : null;
                                $curr = $events->porcargar ? \Carbon\Carbon::parse($events->porcargar) : null;
                            @endphp
                            @if($prev && $curr)
                                @php
                                    $diff = $prev->diff($curr);
                                    $hours = ($diff->d * 24) + $diff->h;
                                    $minutes = $diff->i;
                                @endphp
                                <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                    {{ $hours }}h {{ $minutes }}m
                                </div>
                            @endif
                        </li>

                        <li class="timeline-item text-center"
                            style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Por Cargar</h6>
                                @if($events->porcargar)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->porcargar)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->porcargar)->format('H:i') }}</p>
                                @endif
                            </div>
                            @php
                                $prev = $events->porcargar ? \Carbon\Carbon::parse($events->porcargar) : null;
                                $curr = $events->iniciacarga ? \Carbon\Carbon::parse($events->iniciacarga) : null;
                            @endphp
                            @if($prev && $curr)
                                @php
                                    $diff = $prev->diff($curr);
                                    $hours = ($diff->d * 24) + $diff->h;
                                    $minutes = $diff->i;
                                @endphp
                                <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                    {{ $hours }}h {{ $minutes }}m
                                </div>
                            @endif
                        </li>

                        <li class="timeline-item text-center"
                            style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Inicio de Carga</h6>
                                @if($events->iniciacarga)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->iniciacarga)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->iniciacarga)->format('H:i') }}</p>
                                @endif
                                @php
                                    $prev = $events->iniciacarga ? \Carbon\Carbon::parse($events->iniciacarga) : null;
                                    $curr = $events->fincarga ? \Carbon\Carbon::parse($events->fincarga) : null;
                                @endphp
                                @if($prev && $curr)
                                    @php
                                        $diff = $prev->diff($curr);
                                        $hours = ($diff->d * 24) + $diff->h;
                                        $minutes = $diff->i;
                                    @endphp
                                    <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                        {{ $hours }}h {{ $minutes }}m
                                    </div>
                                @endif
                            </div>
                        </li>
                        <li class="timeline-item text-center"
                            style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Fin de Carga</h6>
                                @if($events->fincarga)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->fincarga)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->fincarga)->format('H:i') }}</p>
                                @endif
                                @php
                                    $prev = $events->fincarga ? \Carbon\Carbon::parse($events->fincarga) : null;
                                    $curr = $events->enviodocumentos ? \Carbon\Carbon::parse($events->enviodocumentos) : null;
                                @endphp
                                @if($prev && $curr)
                                    @php
                                        $diff = $prev->diff($curr);
                                        $hours = ($diff->d * 24) + $diff->h;
                                        $minutes = $diff->i;
                                    @endphp
                                    <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                        {{ $hours }}h {{ $minutes }}m
                                    </div>
                                @endif
                            </div>
                        </li>

                        <li class="timeline-item text-center"
                            style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Envio a Facturación</h6>
                                @if($events->enviodocumentos)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->enviodocumentos)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->enviodocumentos)->format('H:i') }}</p>
                                @endif
                                @php
                                    $prev = $events->enviodocumentos ? \Carbon\Carbon::parse($events->enviodocumentos) : null;
                                    $curr = $events->iniciofacturacion ? \Carbon\Carbon::parse($events->iniciofacturacion) : null;
                                @endphp
                                @if($prev && $curr)
                                    @php
                                        $diff = $prev->diff($curr);
                                        $hours = ($diff->d * 24) + $diff->h;
                                        $minutes = $diff->i;
                                    @endphp
                                    <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                        {{ $hours }}h {{ $minutes }}m
                                    </div>
                                @endif
                            </div>
                        </li>

                        <li class="timeline-item text-center"
                            style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Inicio de Facturación</h6>
                                @if($events->iniciofacturacion)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->iniciofacturacion)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->iniciofacturacion)->format('H:i') }}</p>
                                @endif
                                @php
                                    $prev = $events->iniciofacturacion ? \Carbon\Carbon::parse($events->iniciofacturacion) : null;
                                    $curr = $events->finfacturacion ? \Carbon\Carbon::parse($events->finfacturacion) : null;
                                @endphp
                                @if($prev && $curr)
                                    @php
                                        $diff = $prev->diff($curr);
                                        $hours = ($diff->d * 24) + $diff->h;
                                        $minutes = $diff->i;
                                    @endphp
                                    <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                        {{ $hours }}h {{ $minutes }}m
                                    </div>
                                @endif
                            </div>
                        </li>

                        <li class="timeline-item text-center"
                            style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Fin de Facturación</h6>
                                @if($events->finfacturacion)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->finfacturacion)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->finfacturacion)->format('H:i') }}</p>
                                @endif
                                @php
                                    $prev = $events->finfacturacion ? \Carbon\Carbon::parse($events->finfacturacion) : null;
                                    $curr = $events->autorizasalida ? \Carbon\Carbon::parse($events->autorizasalida) : null;
                                @endphp
                                @if($prev && $curr)
                                    @php
                                        $diff = $prev->diff($curr);
                                        $hours = ($diff->d * 24) + $diff->h;
                                        $minutes = $diff->i;
                                    @endphp
                                    <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                        {{ $hours }}h {{ $minutes }}m
                                    </div>
                                @endif
                            </div>
                        </li>

                        <li class="timeline-item text-center"
                            style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Entrega de documentos a operador</h6>
                                @if($events->autorizasalida)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->autorizasalida)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->autorizasalida)->format('H:i') }}</p>
                                @endif
                                @php
                                    $prev = $events->autorizasalida ? \Carbon\Carbon::parse($events->autorizasalida) : null;
                                    $curr = $events->salidadeunidad ? \Carbon\Carbon::parse($events->salidadeunidad) : null;
                                @endphp
                                @if($prev && $curr)
                                    @php
                                        $diff = $prev->diff($curr);
                                        $hours = ($diff->d * 24) + $diff->h;
                                        $minutes = $diff->i;
                                    @endphp
                                    <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                        {{ $hours }}h {{ $minutes }}m
                                    </div>
                                @endif
                            </div>
                        </li>

                        <li class="timeline-item text-center" style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Salida de unidad</h6>
                                @if($events->autorizasalida)
                                    <p class="text-muted mb-0" style="font-size: 10px;">{{ \Carbon\Carbon::parse($events->salidadeunidad)->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0" style="font-size: 9px;">{{ \Carbon\Carbon::parse($events->salidadeunidad)->format('H:i') }}</p>
                                @endif
                                @php
                                    $prev = $events->autorizasalida ? \Carbon\Carbon::parse($events->autorizasalida) : null;
                                    $curr = $events->salidadeunidad ? \Carbon\Carbon::parse($events->salidadeunidad) : null;
                                @endphp
                                @if($prev && $curr)
                                    @php
                                        $diff = $prev->diff($curr);
                                        $hours = ($diff->d * 24) + $diff->h;
                                        $minutes = $diff->i;
                                    @endphp
                                    <div class="timeline-duration" style="position: absolute; top: -5px; left: 80%; width: 150px; text-align: center; font-size: 12px; color: #6c757d;">
                                        {{ $hours }}h {{ $minutes }}m
                                    </div>
                                @endif
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>