<script>
    var oTable = $("#table-incidents").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "450px",
        "sScrollX": "100%",
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "ajax": {
            url: 'getIncidenceSafeties',
            data: function (d) {
                d.name = $('input[name=name]').val();
            }
        },
        "columns": [
            {data: 'center.description', name: 'center.description'},
            {data: 'name'},
            {data: 'description'},
            {data: 'category.name', name: 'category.name'},
            {data: 'categorydetail.name', name: 'categorydetail.name'},
            {data: 'priority'},
            {data: 'location'},
            {data: 'auxiliary02'},
            {data: 'createuser.name', name: 'createuser.name'},
            {data: 'closeduser.name', name: 'closeduser.name'},
            {data: 'created_at'},
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
            {"width": "280px", "targets": 2},
            {"width": "280px", "targets": 3},
            {"width": "280px", "targets": 4},
            {"width": "250px", "targets": 5},
            {"width": "250px", "targets": 6},
            {"width": "250px", "targets": 7},
            {"width": "200px", "targets": 8},
            {"width": "200px", "targets": 9},
            {"width": "200px", "targets": 10},
            {"width": "150px", "targets": 11},
            {"width": "150px", "targets": 12},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[ 5, 10, 30, 50, 100, 150, 200, 250, -1], [5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span > Mostar/Ocultar</span>",
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

    $('#search').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
</script>

