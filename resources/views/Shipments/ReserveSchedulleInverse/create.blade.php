@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('reserveschedulleinverse.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mt-3">
                </div>
                <div class="row">
                    @include('layouts.Partials.Errors')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <table width="100%" id="table-createreserve"
                                   class="table table-condensed table-bordered">
                                <thead>
                                <tr>
                                    <th>Seleccionar</th>
                                    <th>Linea de transporte</th>
                                    <th>Embarque</th>
                                    <th>Cliente</th>
                                    <th>Total de cajas</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-lg-2">
                            <div class="card card-input " style="margin-top: 65px;">
                                <div class="card">
                                    <div class="card-header">
                                        Total de cajas
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h4>Total de cajas seleccionadas: </h4>
                                                <h2 id="total_quantity_label" class="text-danger">0</h2>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <h4>Total de embarques seleccionados: </h4>
                                                <h2 id="selected_count_label" class="text-danger">0</h2>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <h4>Fecha seleccionada: </h4>
                                                <h2 id="fechawork" class="text-danger"></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a>
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-danger btn-block"
                                                id="btn_generate_schedule">
                                            <img src="{{asset('/ico/calendar.png')}}" alt="" class="img-16">
                                            <span > Generar cita</span>
                                        </button>

                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Horarios de entrega
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 ">
                            <table class="table table-condensed table-bordered table-responsive" id="table-reserve-schedule">
                                <thead>
                                <tr>
                                    <th>
                                        Bloque
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Capacidad Disponible</td>
                                    <!-- Las capacidades se insertarán aquí -->
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        function reloadtable() {
            $("#table-createreserve").DataTable().ajax.reload();
        }
    </script>
    @include('Shipments.ReserveSchedulleInverse.partials.tablecreate')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btnGenerateSchedule = document.getElementById('btn_generate_schedule');
            btnGenerateSchedule.disabled = true; // Desactivar el botón al cargar la página

            Promise.all([
                fetch('../getWorkdays').then(response => response.json()),
                fetch('../getQuantityMax').then(response => response.json()),
                fetch('../getQuantityForDays').then(response => response.json())
            ])
                .then(([workdays, quantitymax, quantityForDays]) => {
                    const dataRow = document.querySelector('#table-reserve-schedule tbody tr');
                    dataRow.setAttribute('data-workdays', JSON.stringify(workdays));
                    dataRow.setAttribute('data-quantitymax', JSON.stringify(quantitymax));
                    dataRow.setAttribute('data-quantityfordays', JSON.stringify(quantityForDays));

                    const headerRow = document.querySelector('#table-reserve-schedule thead tr');
                    workdays.forEach(workday => {
                        const th = document.createElement('th');
                        th.innerHTML = `Fecha: ${workday.workday}
                                <button class="select-date-btn" data-date="${workday.workday}" style="margin-left: 5px;">Seleccionar</button>`;
                        headerRow.appendChild(th);
                    });

                    let totalQuantity = 0;
                    const table = $('#table-createreserve').DataTable();
                    table.rows().every(function () {
                        const row = this.node();
                        if ($(row).find('input.row_checkbox').is(':checked')) {
                            totalQuantity += parseFloat(this.data().quantity) || 0;
                        }
                    });

                    updateTableColors(totalQuantity, workdays, quantitymax, quantityForDays);
                    updateSelectedCount();

                    document.querySelectorAll('.select-date-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const selectedDate = this.getAttribute('data-date');
                            document.getElementById('fechawork').innerText = selectedDate;

                            document.querySelectorAll('#table-reserve-schedule tbody tr td')
                                .forEach(td => td.classList.remove('highlight-yellow'));

                            const index = [...button.parentElement.parentElement.children].indexOf(button.parentElement);
                            document.querySelector(`#table-reserve-schedule tbody tr td:nth-child(${index + 1})`)
                                .classList.add('highlight-yellow');
                        });
                    });

                    btnGenerateSchedule.addEventListener('click', function () {
                        const selectedRows = [];
                        const table = $('#table-createreserve').DataTable();
                        table.rows().every(function () {
                            const row = this.node();
                            if ($(row).find('input.row_checkbox').is(':checked')) {
                                const folioid = $(row).find('input.row_checkbox').val();
                                selectedRows.push(folioid);
                            }
                        });

                        const fechawork = document.getElementById('fechawork').innerText;
                        const totalQuantity = document.getElementById('total_quantity_label').innerText;

                        const postData = {
                            folioids: selectedRows,
                            fechawork: fechawork,
                            totalQuantity: totalQuantity
                        };

                        fetch('{{route('saveSchedulleInverse')}}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(postData)
                        })
                            .then(response => response.json())
                            .then(data => {
                                limpiarValores();
                                reloadtable();

                                Swal.fire({
                                    type: 'success',
                                    title: `${data.message}`,
                                    width: 350,
                                })
                            })
                            .catch(error => console.error('Error:', error));
                    });
                })
                .catch(error => console.error('Error:', error));
        });

        function limpiarValores() {
            document.getElementById('total_quantity_label').innerText = '0';
            document.getElementById('selected_count_label').innerText = '0';
            document.getElementById('fechawork').innerText = '';
            // Limpiar checkboxes seleccionados
            const table = $('#table-createreserve').DataTable();
            table.$('input.row_checkbox').prop('checked', false);
            toggleGenerateButton();
        }


        function updateTotalQuantity() {
            let totalQuantity = 0;
            const table = $('#table-createreserve').DataTable();
            table.rows().every(function () {
                const row = this.node();
                if ($(row).find('input.row_checkbox').is(':checked')) {
                    totalQuantity += parseFloat(this.data().quantity) || 0;
                }
            });

            const dataRow = document.querySelector('#table-reserve-schedule tbody tr');
            const workdays = JSON.parse(dataRow.getAttribute('data-workdays'));
            const quantitymax = JSON.parse(dataRow.getAttribute('data-quantitymax'));
            const quantityForDays = JSON.parse(dataRow.getAttribute('data-quantityfordays'));

            $('#total_quantity_label').text(totalQuantity);
            updateTableColors(totalQuantity, workdays, quantitymax, quantityForDays);
            updateSelectedCount();
            toggleGenerateButton();
        }

        function updateTableColors(totalQuantity, workdays, quantitymax, quantityForDays) {
            const dataRow = document.querySelector('#table-reserve-schedule tbody tr');
            dataRow.innerHTML = '<td>Capacidad Disponible</td>';

            workdays.forEach(workday => {
                const td = document.createElement('td');
                td.style.textAlign = "center";

                const day = quantityForDays.find(day => day.workday === workday.workday);
                if (day) {
                    const availableCapacity = quantitymax.value03 - day.total;
                    td.innerHTML = availableCapacity;

                    const selectButton = document.querySelector(`button[data-date="${workday.workday}"]`);
                    if (totalQuantity < availableCapacity) {
                        td.classList.add('bg-green');
                        if (selectButton) {
                            selectButton.style.display = '';
                        }
                    } else {
                        td.classList.add('bg-red');
                        if (selectButton) {
                            selectButton.style.display = 'none';
                        }
                    }
                } else {
                    td.innerHTML = 'No data';
                    td.classList.add('bg-red');
                }

                dataRow.appendChild(td);
            });
        }

        function updateSelectedCount() {
            let selectedCount = 0;
            const table = $('#table-createreserve').DataTable();
            table.rows().every(function () {
                const row = this.node();
                if ($(row).find('input.row_checkbox').is(':checked')) {
                    selectedCount++;
                }
            });

            document.getElementById('selected_count_label').innerText = `${selectedCount}`;
        }

        function toggleGenerateButton() {
            const table = $('#table-createreserve').DataTable();
            const checkboxes = table.$('input.row_checkbox');
            const btnGenerateSchedule = document.getElementById('btn_generate_schedule');
            const isAnyCheckboxChecked = checkboxes.filter(':checked').length > 0;

            btnGenerateSchedule.disabled = !isAnyCheckboxChecked;
        }

        document.addEventListener("DOMContentLoaded", function () {
            const table = $('#table-createreserve').DataTable();
            table.on('change', 'input.row_checkbox', function () {
                updateTotalQuantity();
            });

            $('#select_all').on('click', function () {
                const rows = table.rows({'search': 'applied'}).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                updateTotalQuantity();
            });

            $('#table-createreserve tbody').on('change', 'input[type="checkbox"]', function () {
                if (!this.checked) {
                    const el = $('#select_all').get(0);
                    if (el && el.checked && ('indeterminate' in el)) {
                        el.indeterminate = true;
                    }
                }
                updateTotalQuantity();
            });
        });
    </script>

    <style>
        .select-date-btn {
            background-color: #78909c;
            border: none;
            color: white;
            padding: 3px 7px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            border-radius: 3px;
        }

        .select-date-btn:hover {
            background-color: red;
            color: white;
        }


        .highlight-yellow {
            background-color: #40c4ff !important;
        }
    </style>
@endsection

