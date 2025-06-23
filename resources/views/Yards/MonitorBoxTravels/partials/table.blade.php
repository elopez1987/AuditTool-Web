<script>
    var oTable = $("#table-monitortravels").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "600px",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        // "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: 'getDataBoxTravels',
            data: function (d) {
                d.folionumber = $('input[name=folionumber]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.folionumberwm = $('input[name=folionumberwm]').val();
                d.boxnumber = $('input[name=boxnumber]').val();
                d.barcode = $('input[name=barcode]').val();
            }
        },
        "columns": [
            {data: 'fecha'},
            {data: 'barcodekey'},
            {data: 'carriername'},
            {data: 'boxnumber'},
            {data: 'operatorname'},
            {data: 'tipo'},
            {data: 'shipmentid'},
            {data: 'starttravel'},

            {data: 'folioplant'},
            {data: 'checkinplant'},
            {data: 'tmesaplant'},
            {data: 'rampaplant'},
            {data: 'startplant'},
            {data: 'finishplant'},
            {data: 'autorzaplant'},
            {data: 'checkoutplant'},
            {data: 'timeplant'},

            {data: 'trayectoida'},

            {data: 'add'},
            {data: 'checkinwarehouse'},
            {data: 'tmesawarehouse'},
            {data: 'rampawarehouse'},
            {data: 'startwarehouse'},
            {data: 'finishwarehouse'},
            {data: 'autorwarehouse'},
            {data: 'checkoutwarehouse'},
            {data: 'timewarehouse'},
            {data: 'btn'},

        ],
        "order": [[2, 'desc']],
        "columnDefs": [
            {"width": "130px", "targets": 0},
            {"width": "80px", "targets": 1},
            {"width": "250px", "targets": 2},
            {"width": "130px", "targets": 3},
            {"width": "220px", "targets": 4},
            {"width": "130px", "targets": 5},
            {"width": "130px", "targets": 6},
            {"width": "130px", "targets": 7},
            {"width": "130px", "targets": 8},
            {"width": "130px", "targets": 9},
            {"width": "130px", "targets": 10},
            {"width": "130px", "targets": 11},
            {"width": "130px", "targets": 12},
            {"width": "130px", "targets": 13},
            {"width": "130px", "targets": 14},
            {"width": "130px", "targets": 15},
            {"width": "130px", "targets": 16},
            {"width": "130px", "targets": 17},
            {"width": "130px", "targets": 18},
            {"width": "130px", "targets": 19},
            {"width": "130px", "targets": 20},
            {"width": "130px", "targets": 21},
            {"width": "130px", "targets": 22},
            {"width": "130px", "targets": 23},
            {"width": "130px", "targets": 24},
            {"width": "130px", "targets": 25},
            {"width": "130px", "targets": 26},

        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],
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
            "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw "></i><span class="sr-only">Loading...</span> ',
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

