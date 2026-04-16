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
                d.statusid = $('select[name="statusid[]"]').val();
                d.warehouseid = $('select[name=warehouseid]').val();
                d.turnid = $('select[name=turnid]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.workday = $('input[name=workday]').val();
            }
        },
        "columns": [
            {data: 'btnoperation'},
            {data: 'workday'},
            {data: 'turns.name',name:'turns.name'},
            {data: 'priority'},
            {data: 'shipmentid'},
            {
                data: 'status.name', name: 'status.name',
                createdCell: function (td, cellData, rowData) {
                    $(td).css({
                        'background-color': (rowData.status.auxiliary01),
                        'color': 'black'
                    });
                }
            },
            {data: 'customer.name',name:'customer.name'},
            {data: 'customer.city',name:'customer.city'},
            {data: 'cortina'},
            {data: 'precarga'},
            {data: 'enrampado'},
            {data: 'key'},
            {data: 'loadingdate'},
            {data: 'downloaddate'},
            {data: 'monitor.outputdate',name:'monitor.outputdate'},
            {data: 'armado'},
            {data: 'header.totalcontainers',name:'header.totalcontainers'},
            {data: 'header.totalpieces',name:'header.totalpieces'},
        ],
        "columnDefs": [
            {"width": "130px", "targets": 0},
            {"width": "130px", "targets": 1},
            {"width": "130px", "targets": 2},
            {"width": "130px", "targets": 3},
            {"width": "180px", "targets": 4},
            {"width": "220px", "targets": 5,},
            {"width": "330px", "targets": 6,},
            {"width": "380px", "targets": 7},
            {"width": "150px", "targets": 8},
            {"width": "130px", "targets": 9},
            {"width": "150px", "targets": 10},
            {"width": "150px", "targets": 11},
            {"width": "180px", "targets": 12},
            {"width": "180px", "targets": 13},
            {"width": "180px", "targets": 14},
            {"width": "130px", "targets": 15},
            {"width": "130px", "targets": 16},
            {"width": "130px", "targets": 17},
            // {"width": "130px", "targets": 18},
            // {"width": "130px", "targets": 19},
            // {"width": "130px", "targets": 20},
            // {"width": "130px", "targets": 21},
            // {"width": "220px", "targets": 22},
            // {"width": "220px", "targets": 23},
            // {"width": "220px", "targets": 24},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16 ml-2 mr-2'><sp> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><spa> Excel</span>",
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
            "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw "></i><span class="sr-only">Cargando ...</span> ',
            {{--"processing": '<img src=\"{{asset('/img/loading-truck.gif')}}\" >',--}}
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

