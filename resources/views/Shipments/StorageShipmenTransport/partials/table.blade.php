<script>
    var oTable = $("#table-journeytransport").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "sScrollY": "400px",
        "processing": true,
        "serverSide": true,
        "sScrollX": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "responsive": true,
        "ajax": {
            url: 'getDataTransportShipment',
            data: function (d) {
                d.shipmentid = $('input[name=shipmentid]').val();
                d.carrierid = $('select[name=carrierid]').val();
            }
        },
        "columns": [
            {data: 'shipmentid'},
            {data: 'customername',name:'customername'},
            {data: 'city',name:'city'},
            {data: 'loadingdate'},
            {data: 'downloaddate'},
            {data: 'carriername',name:'carriername'},
            {data: 'operatorname'},
            {data: 'boxnumber'},
            {data: 'trucknumber'},
            {
                data: 'statustransport',
                createdCell: function (td, cellData, rowData) {
                    if (rowData.statustransport == 1) {
                        $(td).css({
                            'background-color': '#00e676', // Verde
                            'color': 'black',
                            'text-align': 'center'
                        }).text('Asignado'); // Cambia el texto a 'Asignado'
                    } else {
                        $(td).css({
                            'background-color': '#fff176', // Amarillo
                            'color': 'black',
                            'text-align': 'center'
                        }).text('Pendiente'); // Cambia el texto a 'Pendiente'
                    }
                }
            },
            {data: 'btn'},
        ],
        "columnDefs": [
            {"width": "120px", "targets": 0},
            {"width": "380px", "targets": 1},
            {"width": "250px", "targets": 2},
            {"width": "150px", "targets": 3},
            {"width": "150px", "targets": 4},
            {"width": "200px", "targets": 5},
            {"width": "320px", "targets": 6},
            {"width": "200px", "targets": 7},
            {"width": "280px", "targets": 8},
            {"width": "200px", "targets": 9},
            {"width": "200px", "targets": 10},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 25, 50,100,200,300], [10, 25, 50,100,200,300]],

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

