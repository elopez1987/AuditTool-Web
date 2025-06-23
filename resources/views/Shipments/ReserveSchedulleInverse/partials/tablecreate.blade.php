<script>
    var oTable = $("#table-createreserve").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "350px",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: '../getDetailIncidenteJourney',
            data: function (d) {
                d.description = $('input[name=description]').val();
            }
        },
        "columns": [
            {
                data: 'folioid',
                render: function(data, type, row) {
                    return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
                },
                orderable: false
            },
            {data: 'carriername'},
            {data: 'shipmentid'},
            {data: 'customername'},
            {data: 'quantity'},
            // {data: 'btn'},
        ],
        "columnDefs": [
            {"width": "350px", "targets": 0},
            {"width": "150px", "targets": 1},
            {"width": "350px", "targets": 2},
            {"width": "220px", "targets": 3},
            {"width": "220px", "targets": 4},
        ],

        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],


        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 11px;'> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 11px;'> Excel</span>",
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
            "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Loading...</span> ',
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

    // Maneja el checkbox de selección de todos los elementos
    $('#select_all').on('click', function() {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
        updateTotalQuantity();
    });

    // Si se desmarca algún checkbox de una fila, desmarca el "select_all" automáticamente
    $('#table-createreserve tbody').on('change', 'input[type="checkbox"]', function() {
        if(!this.checked) {
            var el = $('#select_all').get(0);
            if(el && el.checked && ('indeterminate' in el)) {
                el.indeterminate = true;
            }
        }
        updateTotalQuantity();
    });

    // Función que obtiene los IDs de los checkboxes seleccionados
    function getSelectedCheckboxes() {
        const selectedIds = [];
        $('#table-createreserve').find('input.row_checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        return selectedIds;
    }

    // // Función que suma la cantidad (quantity) de las filas seleccionadas
    // function updateTotalQuantity() {
    //     let totalQuantity = 0;
    //     let table = $('#table-createreserve').DataTable();
    //     table.rows().every(function() {
    //         const row = this.node();
    //         if ($(row).find('input.row_checkbox').is(':checked')) {
    //             totalQuantity += parseFloat(this.data().quantity) || 0;
    //         }
    //     });
    //     $('#total_quantity_label').text(totalQuantity);
    // }

    // // Ejemplo de cómo usar la función con un botón
    // $('#validate_selection').on('click', function() {
    //     const selectedIds = getSelectedCheckboxes();
    //     if (selectedIds.length > 0) {
    //         console.log('IDs seleccionados:', selectedIds);
    //     } else {
    //         console.log('No hay checkboxes seleccionados.');
    //     }
    //     updateTotalQuantity();
    // });
</script>

