<script>
    var oTable = $("#table-stays").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "450px",
        "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax": {
            url: 'getDataStays',
            data: function (d) {
                d.folionumber = $('input[name=folionumber]').val();
                d.boxnumber = $('input[name=boxnumber]').val();
                d.location = $('input[name=location]').val();
                d.econumber = $('input[name=econumber]').val();
                d.trucknumber = $('input[name=trucknumber]').val();
                d.barcode = $('input[name=barcode]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.carrierid = $('select[name=carrierid]').val();
                d.centerid = $('select[name=centerid]').val();
                d.warehouseid = $('select[name=warehouseid]').val();
                d.rasonid = $('select[name=rasonid]').val();
                d.statusid = $('select[name=statusid]').val();
                d.unitstatusid = $('select[name=unitstatusid]').val();
            }
        },
        "columns": [
            {data:'warehouse.name',name:'warehouse.name'},
            {data: 'id'},
            {data: 'shipmentid'},
            {data: 'carrier.name',name:'carrier.name'},
            {data: 'boxnumber'},
            {data: 'econumber'},
            {data: 'truckcategory.description',name:'truckcategory.description'},
            {data: 'truck.name',name:'truck.name'},
            {data: 'location.name',name:'location.name'},
            {data: 'unitstatus.name',name:'unitstatus.name'},
            {data: 'status.name',name:'status.name'},
            {data: 'entrydate'},
            {data: 'autorizaoutputdate'},
            {data: 'reason.name',name:'reason.name'},
            {data: 'unittype.description',name:'unittype.description'},
            {data: 'stays'},
            {data: 'autorize'},
        ],
        "columnDefs": [
            {"width": "200px", "targets": 0},
            {"width": "180px", "targets": 1},
            {"width": "180px", "targets": 2},
            {"width": "320px", "targets": 3},
            {"width": "200px", "targets": 4},
            {"width": "200px", "targets": 5},
            {"width": "220px", "targets": 6},
            {"width": "250px", "targets": 7},
            {"width": "180px", "targets": 8},
            {"width": "180px", "targets": 9},
            {"width": "280px", "targets": 10},
            {"width": "150px", "targets": 11},
            {"width": "150px", "targets": 12},
            {"width": "250px", "targets": 13},
            {"width": "150px", "targets": 14},
            {"width": "220px", "targets": 15},
            {"width": "220px", "targets": 16},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 30, 50, 100, 150, 200, 250, -1], [10, 30, 50, 100, 150, 200, 250, "Todos"]],

        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
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
            "processing": "Procesando...",
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

