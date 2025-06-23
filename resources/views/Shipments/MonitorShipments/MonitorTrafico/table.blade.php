<script>
    var oTable = $("#table-monitortrafico").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "650px",
        "serverSide": true,
        "processing": true,
        "autoFill": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "responsive": true,
        "ajax": {
            url: 'getDataMonitorTrafico',
            data: function (d) {
                d.shipmentid = $('input[name=shipmentid]').val();
            }
        },
        "columns": [
            {data: 'shipmentid',},
            {data: 'customer'},
            {data: 'city'},
            {data: 'carrier'},
            {data: 'loadingdate'},
            {
                data: 'status',
                render: function (data, type) {
                    if (type === 'display') {
                        return data;
                    }
                },
                "createdCell": function (td, cellData) {
                    if (cellData === 'En tiempo') {
                        $(td).css({
                            'background-color': '#00e676',
                            'color': 'black',
                        });
                    }
                    if(cellData === 'Fuera de tiempo'){
                        $(td).css({
                            'background-color': '#ff1744',
                            'color': 'white',
                        });
                    }
                    if(cellData === 'Validar Transporte'){
                        $(td).css({
                            'background-color': '#ffff00',
                            'color': 'black',
                        });
                    }

                }
            },
        ],
        "columnDefs": [
            {"width": "140px", "targets": 0},
            {"width": "280px", "targets": 1},
            {"width": "280px", "targets": 2},
            {"width": "150px", "targets": 3},
            {"width": "150px", "targets": 4},
            {"width": "150px", "targets": 5, "defaultContent": "-"},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],

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

