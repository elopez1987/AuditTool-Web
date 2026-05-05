<script>
    var oTable = $("#table-receipts").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "400px",
        "serverSide": true,
        "sScrollX": true,
        "processing": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: 'getDataAsns',
            data: function (d) {
                d.shipmentid = $('input[name=shipmentid]').val();
                d.oc = $('input[name=oc]').val();
                d.folionumber = $('input[name=folionumber]').val();
                d.statusid = $('select[name=statusid]').val();
            }
        },
        order: [[2, 'desc']],
        "columns": [
            {data: 'center.description', name: 'center.description'},
            {data: 'warehouse.name', name: 'warehouse.name'},
            {data: 'folionumber'},
            {data: 'shipmentid'},
            {data: 'billoflading'},
            {data: 'oc'},
            {data: 'provider.name', name: 'provider.name'},
            {data: 'reason.name', name: 'reason.name'},
            {data: 'inventorystock.description', name: 'inventorystock.description'},
            {data: 'carrier.name', name: 'carrier.name'},
            {data: 'auxiliary01'},
            {data: 'addasndate'},
            {data: 'arriveddate'},
            {data: 'unloadstartdate'},
            {data: 'unloadfinishdate'},
            {data: 'unload_duration'},
            {data: 'stay_duration'},
            {data: 'goodissuedate'},
            {data: 'cancellationdate'},
            {
                data: 'status.name', name: 'status.name',
                createdCell: function (td, cellData, rowData) {
                    $(td).css({
                        'background-color': (rowData.status.auxiliary01),
                        'color': 'black'
                    });
                }
            },
            {data: 'btn'},
        ],
        "columnDefs": [
            {"width": "280px", "targets": 0},
            {"width": "280px", "targets": 1},
            {"width": "100px", "targets": 2},
            {"width": "100px", "targets": 3},
            {"width": "150px", "targets": 4},
            {"width": "150px", "targets": 5},
            {"width": "250px", "targets": 6},
            {"width": "250px", "targets": 7},
            {"width": "200px", "targets": 8},
            {"width": "320px", "targets": 9},
            {"width": "250px", "targets": 10},
            {"width": "150px", "targets": 11},
            {"width": "150px", "targets": 12},
            {"width": "150px", "targets": 13},
            {"width": "150px", "targets": 14},
            {"width": "180px", "targets": 15},
            {"width": "180px", "targets": 16},
            {"width": "180px", "targets": 17},
            {"width": "180px", "targets": 18},
            {"width": "180px", "targets": 19},
            {"width": "180px", "targets": 20},
        ],

        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10,25,50,100], [10,25,50,100]],

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

