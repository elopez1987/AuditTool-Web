<script>
    var oTable = $("#schedulle").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "800px",
        "serverSide": true,
        "sScrollX": true,
        "processing": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: 'getDataSchedulleV2',
            data: function (d) {
                d.workday = $('input[name=workday]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.statusid = $('select[name=statusid]').val();
            }
        },
        "columns": [
            {data: 'btn'},
            {data: 'centername', name: 'centername'},
            {data: 'warehousename', name: 'warehousename'},
            {data: 'workday', name: 'workday'},
            {data: 'secuencia', name: 'secuencia'},
            {data: 'shipmentid', name: 'shipmentid'},
            {data: 'proveedor', name: 'proveedor'},
            {data: 'material', name: 'material'},
            {data: 'quantity', name: 'quantity'},
            {data: 'tipo', name: 'tipo'},
            {data: 'carriername', name: 'carriername'},
            {data: 'factura', name: 'factura'},
            {data: 'po', name: 'po',
                createdCell: function (td, cellData, rowData) {
                    $(td).css({
                        'text-align': 'center', // Centrar el texto horizontalmente
                        'vertical-align': 'middle', // Centrar el texto verticalmente (opcional)
                    });
                }
                },
            {
                data: 'prioridad',
                name: 'prioridad',
                createdCell: function (td, cellData, rowData) {
                    $(td).css({
                        'text-align': 'center', // Centrar el texto horizontalmente
                        'vertical-align': 'middle', // Centrar el texto verticalmente (opcional)
                    });
                }
            },
            {data: 'cortina', name: 'cortina'},
            {data: 'comment', name: 'comment'},
            {
                data: 'status', name: 'status',
                createdCell: function (td, cellData, rowData) {
                    $(td).css({
                        'background-color': (rowData.color),
                        'color': 'black'
                    });
                }
            },
        ],
        "columnDefs": [
            {"width": "100px", "targets": 0},
            {"width": "180px", "targets": 1},
            {"width": "180px", "targets": 2},
            {"width": "100px", "targets": 3},
            {"width": "100px", "targets": 4},
            {"width": "280px", "targets": 5},
            {"width": "280px", "targets": 6},
            {"width": "280px", "targets": 7},
            {"width": "280px", "targets": 8},
            {"width": "280px", "targets": 9},
            {"width": "180px", "targets": 10},
            {"width": "180px", "targets": 11},
            {"width": "180px", "targets": 12},
            {"width": "180px", "targets": 13},
            {"width": "180px", "targets": 14},
            {"width": "180px", "targets": 15},
            {"width": "180px", "targets": 16},
            // {"width": "180px", "targets": 17},
        ],

        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1,5,10,15,20,25,30,40,50,100], [1,5,10,15,20,25,30,40,50,100]],

        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "copy",
                "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 0.675rem;'> Copiar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem;'> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem;'> Excel</span>",
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
</script>

