<script>
    var oTable = $("#table-monitorincidences").DataTable({
        "paging": true,
        "orderClasses": false,
        "deferRender": true,
        "searching": false,
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
            url: 'getDataIncidenceShipments',
            data: function (d) {
                d.shipmentid = $('input[name=shipmentid]').val();
                d.datestart = $('input[name=datestart]').val();
                d.datefinish = $('input[name=datefinish]').val();
                d.statusid = $('select[name=statusid]').val();
            }
        },
        "columns": [
            {data: 'shipmentid'},
            {data: 'carriername'},
            {data: 'customername'},
            {data: 'city'},
            {data: 'loadingdate'},
            {data: 'downloaddate'},
            {data: 'created_at'},
            {data: 'time'},
            {data: 'btn'},
        ],
        "columnDefs": [
            {"width": "150px", "targets": 0},
            {"width": "380px", "targets": 1},
            {"width": "350px", "targets": 2},
            {"width": "250px", "targets": 3},
            {"width": "200px", "targets": 4},
            {"width": "200px", "targets": 5},
            {"width": "200px", "targets": 6},
            {"width": "200px", "targets": 7},
            {"width": "200px", "targets": 8},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[5,10, 30, 50, 100, 150, 200, 250, -1], [5,10, 30, 50, 100, 150, 200, 250, "Todos"]],

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
            },
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
            "search": "Embarque:",
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

