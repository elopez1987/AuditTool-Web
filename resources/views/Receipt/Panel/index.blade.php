@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form id="receipt-process-filters">
                        <div class="row align-items-end">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group mb-lg-0">
                                    <label for="shipmentid">Vale de recepción</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                           placeholder="Buscar por vale">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group mb-lg-0">
                                    <label for="operatorid">Operador</label>
                                    <select class="form-control select2" id="operatorid" name="operatorid">
                                        <option value="">Todos los operadores</option>
                                        @foreach($operators as $operator)
                                            <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group mb-lg-0">
                                    <label for="providerid">Ruta</label>
                                    <select class="form-control select2" id="providerid" name="providerid">
                                        <option value="">Todas las rutas</option>
                                        @foreach($providers as $provider)
                                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <button class="btn btn-outline-info" type="button" id="clear-receipt-process-filters">
                                    Limpiar filtros
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row" id="receipt-process-cards" aria-live="polite"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .receipt-process-card {
            border-left: 4px solid #26c6da !important;
        }

        .receipt-process-card-body {
            border: 2px solid #78909c;
            border-radius: 8px;
        }

        .receipt-process-details {
            display: grid;
            gap: .75rem;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .receipt-process-details span,
        .receipt-process-details strong {
            display: block;
        }

        .receipt-process-details strong {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .receipt-process-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('script')
    <script>
        (function () {
            const filtersForm = document.getElementById('receipt-process-filters');
            const cardsContainer = document.getElementById('receipt-process-cards');
            const shipmentInput = document.getElementById('shipmentid');
            const operatorSelect = document.getElementById('operatorid');
            const providerSelect = document.getElementById('providerid');
            const clearFiltersButton = document.getElementById('clear-receipt-process-filters');
            const dataUrl = @json(route('receiptpanel.data'));
            const numberFormatter = new Intl.NumberFormat('es-MX', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
            let shipmentSearchTimeout;
            let activeRequest;

            function escapeHtml(value) {
                const element = document.createElement('div');
                element.textContent = value ?? 'Sin asignar';

                return element.innerHTML;
            }

            function renderReceiptCards(receiptProcesses) {
                if (receiptProcesses.length === 0) {
                    cardsContainer.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                No hay recibos en proceso para los filtros seleccionados.
                            </div>
                        </div>
                    `;

                    return;
                }

                cardsContainer.innerHTML = receiptProcesses.map(function (receiptProcess) {
                    const quantityToReceive = Number(receiptProcess.quantity_to_receive ?? 0);
                    const quantityVerified = Number(receiptProcess.quantity_verified ?? 0);
                    const progress = quantityToReceive > 0
                        ? Math.min(100, (quantityVerified / quantityToReceive) * 100)
                        : 0;
                    const progressColor = progress >= 100 ? 'success' : 'info';
                    const progressLabel = numberFormatter.format(progress);

                    return `
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm receipt-process-card">
                                <div class="card-body receipt-process-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <p class="text-muted text-uppercase font-12 mb-1">Vale de recepción</p>
                                            <h5 class="mb-0 font-weight-bold">${escapeHtml(receiptProcess.shipmentid)}</h5>
                                        </div>
                                        <span class="badge badge-${progressColor}">${escapeHtml(receiptProcess.status)}</span>
                                    </div>

                                    <div class="receipt-process-details mb-3">
                                        <div>
                                            <span class="text-muted">Bodega</span>
                                            <strong>${escapeHtml(receiptProcess.warehouse)}</strong>
                                        </div>
                                        <div>
                                            <span class="text-muted">Ruta</span>
                                            <strong>${escapeHtml(receiptProcess.provider)}</strong>
                                        </div>
                                        <div>
                                            <span class="text-muted">Operador</span>
                                            <strong>${escapeHtml(receiptProcess.operator)}</strong>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-end mb-2">
                                        <div>
                                            <span class="d-block text-muted font-12">Cantidad a recibir</span>
                                            <strong class="font-18">${numberFormatter.format(quantityToReceive)}</strong>
                                        </div>
                                        <div class="text-right">
                                            <span class="d-block text-muted font-12">Cantidad verificada</span>
                                            <strong class="font-18 text-${progressColor}">
                                                ${numberFormatter.format(quantityVerified)}
                                            </strong>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between font-12 mb-1">
                                        <span>Avance de verificación</span>
                                        <strong>${progressLabel}%</strong>
                                    </div>
                                    <div class="progress" role="progressbar"
                                         aria-label="Avance de verificación"
                                         aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-${progressColor}" style="width: ${progress}%">
                                            <span class="sr-only">${progressLabel}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            async function loadReceiptProcesses() {
                if (activeRequest) {
                    activeRequest.abort();
                }

                const requestController = new AbortController();
                activeRequest = requestController;
                const searchParameters = new URLSearchParams(new FormData(filtersForm));

                try {
                    const response = await fetch(`${dataUrl}?${searchParameters.toString()}`, {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: requestController.signal,
                    });

                    if (!response.ok) {
                        throw new Error('No fue posible obtener los recibos en proceso.');
                    }

                    const responseData = await response.json();
                    renderReceiptCards(responseData.data);
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        cardsContainer.innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-danger mb-0">
                                    No fue posible cargar los recibos en proceso. Intente nuevamente.
                                </div>
                            </div>
                        `;
                    }
                } finally {
                    if (activeRequest === requestController) {
                        activeRequest = null;
                    }
                }
            }

            shipmentInput.addEventListener('input', function () {
                window.clearTimeout(shipmentSearchTimeout);
                shipmentSearchTimeout = window.setTimeout(loadReceiptProcesses, 350);
            });
            operatorSelect.addEventListener('change', loadReceiptProcesses);
            providerSelect.addEventListener('change', loadReceiptProcesses);
            clearFiltersButton.addEventListener('click', function () {
                filtersForm.reset();
                $('.select2').trigger('change.select2');
                loadReceiptProcesses();
            });

            loadReceiptProcesses();
            window.setInterval(loadReceiptProcesses, 60000);
        })();
    </script>
@endsection
