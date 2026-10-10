<script>
    var oTable = $("#inventoryvalidation").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "order": [[0, 'asc']],
        "sScrollY": "450px",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: "{{ route('getDataValidationSap', ['id' => $inventoryhead->id]) }}",
            data: function (d) {
                d.partnumber = $('input[name=partnumber]').val();
            }
        },
        "columns": [
            {data: 'partnumber', name: 'partnumber'},
            {data: 'description', name: 'description'},
            {data: 'total_piezas_conteo', name: 'total_piezas_conteo'},
            {data: 'total_piezas_transitorias', name: 'total_piezas_transitorias'},
            {data: 'total_piezas_conteo_mas_transitorias', name: 'total_piezas_conteo_mas_transitorias'},
            {data: 'stock_wms', name: 'stock_wms'},
            {data: 'total_piezas_sap', name: 'total_piezas_sap'},
            {
                data: 'status_conteo_vs_sap',
                name: 'status_conteo_vs_sap',
                render: function (data) {
                    if (data === 'CORRECTO') {
                        return '<span class="badge bg-success">' + data + '</span>';
                    }
                    return '<span class="badge bg-danger">' + data + '</span>';
                }
            },
            {
                data: 'status_sap_vs_wms',
                name: 'status_sap_vs_wms',
                render: function (data) {
                    if (data === 'CORRECTO') {
                        return '<span class="badge bg-success">' + data + '</span>';
                    }
                    return '<span class="badge bg-danger">' + data + '</span>';
                }
            },
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],

        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{ asset('/pics/adbuscar.png') }}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{ asset('/ico/csv.png') }}\"  alt='' class='img-16'><span  style='font-size: 11px;'> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{ asset('/pics/adexportar.png') }}\"  alt=''><span  style='font-size: 11px;'> Excel</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
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
        },
    });

    $('#search').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
</script>
