<script>
    var oTable = $("#table-orders").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "sScrollY": "650px",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: 'getDataSchedule',
            data: function (d) {
                d.statusid = $('select[name=statusid]').val();
                d.warehouseid = $('select[name=warehouseid]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.workday = $('input[name=workday]').val();
            }
        },
        "columns": [
            {data: 'warehouse.name',name: 'warehouse.name'},
            {data: 'workday',name: 'workday'},
            {data: 'turns.name',name: 'turns.name'},
            {data: 'shipmentid',name: 'shipmentid'},
            {data: 'carrier.name',name: 'carrier.name',},
            {data: 'customer.name',name: 'customer.name'},
            {data: 'customer.city',name: 'customer.city',},
            {data: 'loadingdate',name: 'loadingdate'},
            {data: 'downloaddate',name: 'downloaddate'},
            {data: 'status.name',name: 'status.name'},
            {data: 'btn',name: 'btn'},
        ],
        "columnDefs": [
            {"width": "200px", "targets": 0},
            {"width": "200px", "targets": 1},
            {"width": "200px", "targets": 2},
            {"width": "200px", "targets": 3},
            {"width": "280px", "targets": 4},
            {"width": "280px", "targets": 5},
            {"width": "280px", "targets": 6},
            {"width": "250px", "targets": 6},
            {"width": "270px", "targets": 7},
            {"width": "200px", "targets": 8},
            {"width": "200px", "targets": 9},
            {"width": "200px", "targets": 10},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1,5,10, 30, 50, 100, 150], [1,5,10, 30, 50, 100, 150]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "copy",
                "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Copiar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Excel</span>",
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

