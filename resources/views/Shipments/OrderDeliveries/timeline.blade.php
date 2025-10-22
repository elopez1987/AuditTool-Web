<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Timeline de Eventos </h5>
            </div>
            <div class="card-body">
                <div class="timeline-horizontal">
                    <ul class="list-unstyled d-flex flex-wrap justify-content-between align-items-start position-relative">

                        <li class="timeline-item text-center" style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Inicio de preparación</h6>
                                <p class="text-muted mb-0" style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->lanzamientoola))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0" style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->lanzamientoola))->format('H:i') }}</p>
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

                        <li class="timeline-item text-center" style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Fin de preparación</h6>
                                <p class="text-muted mb-0" style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->preparadoenpiso))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0" style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->preparadoenpiso))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0" style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->inicioverificacion))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0" style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->inicioverificacion))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0" style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->finverificacion))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0" style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->finverificacion))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0"
                                   style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->porcargar))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0"
                                   style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->porcargar))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0"
                                   style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->iniciacarga))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0"
                                   style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->iniciacarga))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0"
                                   style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->fincarga))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0"
                                   style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->fincarga))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0"
                                   style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->enviodocumentos))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0"
                                   style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->enviodocumentos))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0"
                                   style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->iniciofacturacion))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0"
                                   style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->iniciofacturacion))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0"
                                   style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->finfacturacion))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0"
                                   style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->finfacturacion))->format('H:i') }}</p>
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
                                <p class="text-muted mb-0"
                                   style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->autorizasalida))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0"
                                   style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->autorizasalida))->format('H:i') }}</p>
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

                        <li class="timeline-item text-center"
                            style="min-width: 150px; max-width: 200px; position: relative;">
                            <div class="timeline-badge bg-info rounded-circle mx-auto"
                                 style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 1;">
                                <i class="mdi mdi-check text-white"></i>
                            </div>
                            <div class="timeline-panel mt-2">
                                <h6 class="mb-1 font-weight-bold" style="font-size: 11px;">Salida de unidad</h6>
                                <p class="text-muted mb-0"
                                   style="font-size: 10px;">{{ optional(\Carbon\Carbon::parse($events->salidadeunidad))->format('d/m/Y') }}</p>
                                <p class="text-muted mb-0"
                                   style="font-size: 9px;">{{ optional(\Carbon\Carbon::parse($events->salidadeunidad))->format('H:i') }}</p>
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