<script>
    var oTable = $("#table-monitortrafico").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "650px",
        "sScrollX": true,
        "serverSide": true,
        "processing": true,
        "autoFill": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "responsive": true,
        "ajax": {
            url: 'getDataMonitorOperation',
        },
        "columns": [
            {data: 'shipmentid',},
            {data: 'customer'},
            {data: 'city'},
            {data: 'carrier'},
            {data: 'status'},
            {data: 'statustransport'},
            {data: 'comment'},
            {data: 'entrydate'},
            {data: 'keydate'},
            {data: 'stayskey'},
            {data: 'stays'},
            {data: 'outputdate'},
        ],
        "columnDefs": [
            {"width": "180px", "targets": 0},
            {"width": "250px", "targets": 1},
            {"width": "250px", "targets": 2},
            {"width": "250px", "targets": 3},
            {"width": "150px", "targets": 4},
            {"width": "150px", "targets": 5},
            {"width": "350px", "targets": 6},
            {"width": "200px", "targets": 7},
            {"width": "250px", "targets": 8},
            {"width": "200px", "targets": 9},
            {"width": "150px", "targets": 10},
            {"width": "150px", "targets": 11},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],

        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "copy",
                "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Copiar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem !important;'> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Excel</span>",
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

