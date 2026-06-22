<script>
    var oTable = $("#table-schedullebrid").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "sScrollY": "750px",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: 'getDataSchedulleShipmentBridgestone',
            data: function (d) {
                d.statusid = $('select[name=statusid]').val();
                d.warehouseid = $('select[name=warehouseid]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.workday = $('input[name=workday]').val();
            }
        },
        "columns": [
            {data: 'btn'},
            {data: 'shipmentid'},
            {data: 'city'},
            {data: 'carriername'},
            {data: 'truckname'},
            {data: 'citaentrega'},
            {data: 'shipmenttype'},
            {data: 'cortina'},
            {
                data: 'status', name: 'status',
                createdCell: function (td, cellData, rowData) {
                    $(td).css({
                        'background-color': (rowData.color),
                        'color': 'black'
                    });
                }
            },
            {data: 'boxnumber'},
            {data: 'econumber'},
            {data: 'eta'},
            {data: 'totalpieces'},

        ],
        "columnDefs": [
            {"width": "130px", "targets": 0,"orderable": false},
            {"width": "180px", "targets": 1},
            {"width": "280px", "targets": 2},
            {"width": "280px", "targets": 3},
            {"width": "230px", "targets": 4},
            {"width": "180px", "targets": 5,},
            {"width": "180px", "targets": 6,},
            {"width": "180px", "targets": 7},
            {"width": "180px", "targets": 8},
            {"width": "180px", "targets": 9},
            {"width": "180px", "targets": 10},
            {"width": "180px", "targets": 11},
            {"width": "180px", "targets": 12},
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

