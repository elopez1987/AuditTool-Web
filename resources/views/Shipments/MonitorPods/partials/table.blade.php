<script>
    var oTable = $("#table-monitorpods").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "450px",
        "processing": true,
        "serverSide": true,
        "sScrollX": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "responsive": true,
        "ajax": {
            url: 'getDataMonitorPods',
            data: function (d) {
                d.shipmentid = $('input[name=shipmentid]').val();
                d.datestart = $('input[name=datestart]').val();
                d.datefinish = $('input[name=datefinish]').val();
                d.statusid = $('select[name=statusid]').val();
            }
        },
        "columns": [
            {data: 'carriername'},
            {
                data: 'shipmentid',
            },
            {data: 'customername'},
            {data: 'city'},
            {data: 'downloaddate'},
            {
                data: 'status',
                createdCell: function(td, cellData, rowData, row, col) {
                    if (rowData.statusid === 'CON') {
                        $(td).css({
                            'background-color': '#76ff03',
                            'color': 'black'
                        });
                    } else {
                        $(td).css({
                            'background-color': '#ffff00',
                            'color': 'black'
                        });
                    }
                }
            },
            {data: 'btn'},
        ],
        "columnDefs": [
            {"width": "280px", "targets": 0},
            {"width": "220px", "targets": 1},
            {"width": "280px", "targets": 2},
            {"width": "250px", "targets": 3},
            {"width": "250px", "targets": 4},
            {"width": "250px", "targets": 5},
            {"width": "250px", "targets": 6},
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

