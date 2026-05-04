<script>
    var oTable = $("#table-trucks").DataTable({
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
            url: 'getDataTask',
            data: function (d) {
                d.folionumber = $('input[name=folionumber]').val();
                d.boxnumber = $('input[name=boxnumber]').val();
                d.barcode = $('input[name=barcode]').val();
                d.statusid = $('select[name=statusid]').val();
            }
        },
        "columns": [
            {data: 'centername'},
            {data: 'warehouse',},
            {data: 'asignado'},
            {data: 'folionumber'},
            {data: 'barcode'},
            {data: 'boxnumber'},
            {data: 'econumber'},
            {data: 'operatorname'},
            {data: 'rason',},
            {data: 'zone',},
            {data: 'location',},
            {data: 'locationactual',},
            {data: 'status',},
            {data: 'creation'},
            {data: 'cancelledbtn'}
        ],
        "columnDefs": [
            {"width": "180px", "targets": 0},
            {"width": "150px", "targets": 1},
            {"width": "250px", "targets": 2},
            {"width": "120px", "targets": 3},
            {"width": "120px", "targets": 4},
            {"width": "120px", "targets": 5},
            {"width": "120px", "targets": 6},
            {"width": "280px", "targets": 7},
            {"width": "320px", "targets": 8},
            {"width": "180px", "targets": 9},
            {"width": "180px", "targets": 10},
            {"width": "180px", "targets": 11},
            {"width": "180px", "targets": 12},
            {"width": "180px", "targets": 13},
            {"width": "180px", "targets": 14},
        ],

        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],

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

