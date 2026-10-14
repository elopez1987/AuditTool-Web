<script>
    var concentratedTable = $("#inventoryErrorsConcentratedTable").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: "{{ route('getConcentratedInventoryErrors', ['id' => $inventoryhead->id]) }}",
            data: function (d) {
                d.name = $('input[name=name]').val();
            }
        },
        "columns": [
            {data: 'cardname', name: 'cardname'},
            {data: 'totalerrores', name: 'totalerrores'},
            {data: 'btn', name: 'btn', orderable: false, searchable: false},
            {data: 'btn3', name: 'btn3', orderable: false, searchable: false},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        buttons: [
            {
                "extend": "excel",
                "text": "<img src=\"{{ asset('/pics/adexportar.png') }}\" alt=''><span style='font-size: 11px;'> Excel</span>",
                "className": "btn btn-outline-info btn-md btn-bold",
            }
        ],
        language: {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });

    var assignTable = $("#inventoryErrorsAssignTable").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: "{{ route('getPendingAssignInventoryErrors', ['id' => $inventoryhead->id]) }}",
            data: function (d) {
                d.name = $('input[name=name]').val();
            }
        },
        "columns": [
            {data: 'card_name', name: 'card_name'},
            {data: 'btn', name: 'btn', orderable: false, searchable: false},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        buttons: [],
        language: {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "drawCallback": function () {
            if ($.fn.select2) {
                $('#inventoryErrorsAssignTable .select2').select2({
                    width: '100%',
                    dropdownParent: $('body')
                });
            }
        }
    });

    var detailErrorsTable = null;
    if ($("#inventorydetailerrors").length) {
        detailErrorsTable = $("#inventorydetailerrors").DataTable({
            "paging": true,
            "searching": false,
            "ordering": true,
            "sScrollY": "450px",
            "sScrollX": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "stateSave": true,
            "stateDuration": 0,
            "ajax": {
                url: "{{ route('getDetailInventoryErrors', ['id' => $inventoryhead->id]) }}",
                data: function (d) {
                    d.inventory_head_id = {{ $inventoryhead->id }};
                    d.name = $('input[name=name]').val();
                }
            },
            "columns": [
                {data: 'card_name', name: 'card_name'},
                {data: 'location_name', name: 'location_name'},
                {data: 'sku01', name: 'sku01', defaultContent: ''},
                {data: 'quantity01', name: 'quantity01', defaultContent: ''},
                {data: 'sku02', name: 'sku02', defaultContent: ''},
                {data: 'quantity02', name: 'quantity02', defaultContent: ''},
                {
                    data: 'type',
                    name: 'type',
                    defaultContent: '',
                    render: function (data) {
                        if (!data) {
                            return '<span class="badge bg-secondary">Sin estatus</span>';
                        }
                        return '<span class="badge bg-danger">' + data + '</span>';
                    }
                },
                {data: 'btn', name: 'btn', orderable: false, searchable: false},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            buttons: [
                {
                    "extend": "excel",
                    "text": "<img src=\"{{ asset('/pics/adexportar.png') }}\" alt=''><span style='font-size: 11px;'> Excel</span>",
                    "className": "btn btn-outline-info btn-md btn-bold",
                }
            ],
            language: {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    }

    var inventoryCardAssignTable = $("#inventorycardassing").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: "{{ route('inventorycardassing', ['id' => $inventoryhead->id]) }}",
            data: function (d) {
                d.name = $('input[name=name]').val();
            }
        },
        "columns": [
            {data: 'card_name', name: 'card_name'},
            {data: 'user_name', name: 'user_name', defaultContent: ''},
            {data: 'status', name: 'status', defaultContent: ''},
            {data: 'btn', name: 'btn', orderable: false, searchable: false},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        buttons: [
            {
                "extend": "excel",
                "text": "<img src=\"{{ asset('/pics/adexportar.png') }}\" alt=''><span style='font-size: 11px;'> Excel</span>",
                "className": "btn btn-outline-info btn-md btn-bold",
            }
        ],
        language: {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "drawCallback": function () {
            if ($.fn.select2) {
                $('#inventorycardassing .select2').select2({
                    width: '100%',
                    dropdownParent: $('body')
                });
            }
        }
    });

    $('#search').on('submit', function (e) {
        e.preventDefault();
        concentratedTable.draw();
        assignTable.draw();
        if (detailErrorsTable) {
            detailErrorsTable.draw();
        }
        inventoryCardAssignTable.draw();
    });
</script>
