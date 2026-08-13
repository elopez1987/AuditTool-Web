<script>
    var oTable = $("#table-monitor-status").DataTable({
        "paging": true,
        "searching": false,
        "ordering": true,
        "sScrollY": "650px",
        "sScrollX": true,
        "serverSide": true,
        "processing": true,
        "autoFill": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "responsive": true,
        "ajax": {
            url: 'getShipmentStatus',
            data: function (d) {
                d.shipmentid = $('input[name=shipmentid-status]').val();
                d.carrierid = $('select[name=carrierid-status]').val();
                d.customerid = $('select[name=customerid-status]').val();
                d.statusid = monitorOperationHenkelStatusId;
            }
        },
        "columns": [
            {data: 'shipmentid'},
            {data: 'customer.name',name:'customer.name'},
            {data: 'carrier.name',name:'carrier.name'},
            {data: 'status.name',name:'status.name'},
            {data: 'folio.staytime',name:'folio.staytime'},

        ],
        "columnDefs": [
            {"width": "180px", "targets": 0},
            {"width": "250px", "targets": 1},
            {"width": "250px", "targets": 2},
            {"width": "250px", "targets": 3},
            {"width": "250px", "targets": 4},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],

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
    $('#search-monitor-status').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
</script>

