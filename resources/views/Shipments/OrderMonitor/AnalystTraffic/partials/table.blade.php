<script>
    var oTable = $("#table-traffics").DataTable({
        "paging":true,
        "searching":true,
        "ordering":true,
        "sScrollY":"450px",
        "sScrollX":true,
        "processing":true,
        "serverSide":true,
        // "responsive":true,
        "colReorder":true,
        "stateSave":true,
        "stateDuration":0,
        "ajax": {
            url: 'getdatamonitortraffic',
            data: function (d) {
                d.statusid = $('select[name=statusid]').val();
                d.warehouseid = $('select[name=warehouseid]').val();
                d.turnid = $('select[name=turnid]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.workday = $('input[name=workday]').val();
            }
        },
        "columns": [
            {data: 'btn'},
            {data: 'statustransport'},
            {data: 'workday'},
            {data: 'turno'},
            {data: 'shipmentiactive'},
            {data: 'statusshipment'},
            {data: 'cortina'},
            {data: 'key'},
            {data: 'comment'},
            {data: 'statusunit'},
            {data: 'r2operator'},
            {data: 'carriername'},
            {data: 'r2boxnumber'},
            {data: 'customer'},
            {data: 'city'},
            {data: 'loadingdate'},
            {data: 'downloaddate'},
            {data: 'entrydate'},
            {data: 'deliverdate'},
            {data: 'timetransfer'},
            {data: 'autorizaoutputdate'},
            {data: 'maxoutputtransport'},
            {data: 'hrestante'},
            {data: 'dvnegocio'},
            {data: 'planer'},
            {data: 'precarga'},
            {data: 'fpatios'},
            {data: 'armado'},
            {data: 'cajas'},
            {data: 'pallets'},
            {data: 'totalweight'},
        ],
        "columnDefs": [
            {"width": "150px", "targets": 0,"orderable": false},
            {"width": "100px", "targets": 1,"orderable":false},
            {"width": "100px", "targets": 2},
            {"width": "100px", "targets": 3},
            {"width": "180px", "targets": 4},
            {"width": "200px", "targets": 5},
            {"width": "180px", "targets": 6},
            {"width": "100px", "targets": 7},
            {"width": "220px", "targets": 8},
            {"width": "200px", "targets": 9},
            {"width": "350px", "targets": 10},
            {"width": "380px", "targets": 11},
            {"width": "180px", "targets": 12},
            {"width": "350px", "targets": 13},
            {"width": "350px", "targets": 14},
            {"width": "120px", "targets": 15},
            {"width": "120px", "targets": 16},
            {"width": "120px", "targets": 17},
            {"width": "100px", "targets": 18},
            {"width": "120px", "targets": 19},
            {"width": "120px", "targets": 20},
            {"width": "120px", "targets": 21},
            {"width": "120px", "targets": 22},
            {"width": "120px", "targets": 23},
            {"width": "120px", "targets": 24},
            {"width": "120px", "targets": 25},
            {"width": "150px", "targets": 26},
            {"width": "150px", "targets": 27},
            {"width": "150px", "targets": 28},
            {"width": "150px", "targets": 29},
            {"width": "150px", "targets": 30},
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
    });
    $('#search').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
</script>

