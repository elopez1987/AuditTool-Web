<script>
    var oTable = $("#table-trucksbox").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "500px",
        "processing": true,
        "serverSide": true,
        "autoFill": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "responsive": true,

        "ajax": {
            url: 'getDataTruckForBox',
            data: function (d) {
                d.folionumber = $('input[name=folionumber]').val();
                d.boxnumber = $('input[name=boxnumber]').val();
                d.barcode = $('input[name=barcode]').val();
                d.carrierid = $('select[name=carrierid]').val();
            }
        },
        "columns": [
            {data: 'warehouse.name', name: 'warehouse.name'},
            {data: 'id'},
            {data: 'barcode'},
            {data: 'carrier.name', name: 'carrier.name'},
            {data: 'boxnumber'},
            {data: 'tracto.trucknumber'},
            {data: 'tracto.operatorname'},
            {data: 'tracto.phone'},
            {data: 'btn'},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]],

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

