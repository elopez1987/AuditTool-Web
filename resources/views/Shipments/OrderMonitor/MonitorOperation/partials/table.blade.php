<script>
    var oTable = $("#table-monitoroperation").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "450px",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: 'getdataoperationmonitor',
            data: function (d) {
                d.statusid = $('select[name=statusid]').val();
                d.warehouseid = $('select[name=warehouseid]').val();
                d.turnid = $('select[name=turnid]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.workday = $('input[name=workday]').val();
            }
        },
        "columns": [
            {data: 'btnoperation'},
            {data: 'loadingdate'},
            {data: 'turno'},
            {data: 'shipmentiactive'},
            {data: 'statusshipment'},
            {data: 'shtotalcontainers'},
            {data: 'shtotalpieces'},
            {data: 'statusunit'},
            {data: 'key'},
            {data: 'precarga'},
            {data: 'cortina'},
            {data: 'customer'},
            {data: 'city'},
            {data: 'outputdate'},
            {data: 'downloaddate'},
            {data: 'priority'},
            {data: 'entrydate'},
            {data: 'deliverdate'},
            {data: 'peso'},
            {data: 'armado'},
            {data: 'dvnegocio'},
            {data: 'planer'},
            {data: 'comment01'},
            {data: 'comment02'},
            {data: 'comment03'},
        ],
        "columnDefs": [
            {"width": "130px", "targets": 0},
            {"width": "130px", "targets": 1},
            {"width": "130px", "targets": 2},
            {"width": "130px", "targets": 3},
            {"width": "180px", "targets": 4},
            {"width": "130px", "targets": 5,"orderable": false},
            {"width": "130px", "targets": 6,"orderable": false},
            {"width": "130px", "targets": 7},
            {"width": "150px", "targets": 8},
            {"width": "130px", "targets": 9},
            {"width": "150px", "targets": 10},
            {"width": "350px", "targets": 11},
            {"width": "250px", "targets": 12},
            {"width": "230px", "targets": 13},
            {"width": "130px", "targets": 14},
            {"width": "130px", "targets": 15},
            {"width": "130px", "targets": 16},
            {"width": "130px", "targets": 17},
            {"width": "130px", "targets": 18},
            {"width": "130px", "targets": 19},
            {"width": "130px", "targets": 20},
            {"width": "130px", "targets": 21},
            {"width": "220px", "targets": 22},
            {"width": "220px", "targets": 23},
            {"width": "220px", "targets": 24},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1,5,10, 30, 50, 100, 150, 200, 250, -1], [1,5,10, 30, 50, 100, 150, 200, 250, "Todos"]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "copy",
                "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 11px;'> Copiar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
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
            // "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw "></i><span class="sr-only">Cargando ...</span> ',
            "processing": '<img src=\"{{asset('/img/loading-truck.gif')}}\" >',
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "decimal": ",",
        "thousands": "."
    });
    $('#search').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
</script>

