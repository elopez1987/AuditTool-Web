<script>
    var oTable = $("#table-inventory-logs").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "sScrollY": "500px",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "order": [[11, "desc"]],
        "ajax": {
            url: '{{ route("getInventoryLog", $inventoryHead->id) }}',
            data: function (d) {
                d.inventory_card_id = $('input[name=inventory_card_id]').val();
                d.location_id = $('input[name=location_id]').val();
                d.partnumber_name = $('input[name=partnumber_name]').val();
                d.status = $('select[name=status]').val();
            }
        },
        "columns": [
            {data: 'transaction_id', name: 'transaction_id', defaultContent: ''},
            {data: 'inventory_card_id', name: 'inventory_card_id', defaultContent: ''},
            {data: 'location_id', name: 'location_id', defaultContent: ''},
            {data: 'partnumber_name', name: 'partnumber_name', defaultContent: ''},
            {data: 'number_count', name: 'number_count', defaultContent: ''},
            {data: 'quantity', name: 'quantity', defaultContent: ''},
            {data: 'username', name: 'username', defaultContent: ''},
            {data: 'service_name', name: 'service_name', defaultContent: ''},
            {data: 'status', name: 'status', defaultContent: '', orderable: true},
            {data: 'http_code', name: 'http_code', defaultContent: ''},
            {data: 'message', name: 'message', defaultContent: ''},
            {data: 'received_at', name: 'received_at', defaultContent: ''},
            {data: 'processed_at', name: 'processed_at', defaultContent: ''},
            {data: 'btn', name: 'btn', orderable: false, searchable: false}
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 20, 30, 50, 100, -1], [10, 20, 30, 50, 100, "Todos"]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{ asset('/pics/adbuscar.png') }}\" alt=''><span style='font-size: 11px;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md btn-bold",
                columns: ':not(:last)'
            },
            {
                "extend": "copy",
                "text": "<img src=\"{{ asset('/pics/adcopiar.png') }}\" alt=''><span style='font-size: 11px;'> Copiar</span>",
                "className": "btn btn-outline-info btn-md btn-bold"
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{ asset('/ico/csv.png') }}\" alt='' class='img-16'><span style='font-size: 11px;'> Csv</span>",
                "className": "btn btn-outline-info btn-md btn-bold"
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{ asset('/pics/adexportar.png') }}\" alt=''><span style='font-size: 11px;'> Excel</span>",
                "className": "btn btn-outline-info btn-md btn-bold"
            }
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });

    $('#search').on('submit', function (e) {
        oTable.draw();
        if (typeof loadInventoryLogKpis === 'function') {
            loadInventoryLogKpis();
        }
        e.preventDefault();
    });
</script>
