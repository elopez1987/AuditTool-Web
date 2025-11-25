<script>
    var oTable = $("#table-partnumber").DataTable({
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
            url: 'getDataInventoryNumbers',
            data: function (d) {
                d.prtnum = $('input[name=prtnum]').val();
                d.statusid = $('select[name=statusid]').val();
            }
        },
        "columns": [
            {data: 'center.description', name: 'center.description'},
            {data: 'warehouse.name', name: 'warehouse.name'},
            {data: 'prtnum'},
            {data: 'lngdsc'},
            {data: 'untpal'},
            {data: 'untcas'},
            {data: 'shelflife'},
            {data: 'barcode'},
            {data: 'category01.description', name: 'category01.description'},
            {data: 'category02.description', name: 'category02.description'},
            {data: 'status.name', name: 'status.name'},
            {data: 'btn'},
        ],
        "columnDefs": [
            {"width": "250px", "targets": 0},
            {"width": "280px", "targets": 1},
            {"width": "200px", "targets": 2},
            {"width": "330px", "targets": 3},
            {"width": "180px", "targets": 4},
            {"width": "220px", "targets": 5,},
            {"width": "180px", "targets": 6,},
            {"width": "220px", "targets": 7},
            {"width": "220px", "targets": 8},
            {"width": "220px", "targets": 9},
            {"width": "250px", "targets": 10},
            {"width": "150px", "targets": 11},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],

        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem;'> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem;'> Excel</span>",
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
            // "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Loading...</span> ',
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
    });
    $('#search').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
</script>

