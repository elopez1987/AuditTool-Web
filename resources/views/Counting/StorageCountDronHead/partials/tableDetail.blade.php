<script>
    var oTable = $("#table-detailcount").DataTable({
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
            url: '../getinventoryheaddrondetail',
            data: function (d) {
                d.inventoryid = {!! $storagecountdronhead->id !!};
                d.idh = $('input[name=idh]').val();
                d.location = $('input[name=location]').val();
                d.area = $('input[name=area]').val();
                d.family = $('input[name=family]').val();
                // d.workday = $('input[name=workday]').val();
            }
        },
        "columns": [
            {data: 'countid'},
            {data: 'wd'},
            {data: 'lpn'},
            {data: 'ubicacion'},
            {data: 'ultimo_movimiento'},
            {data: 'idh'},
            {data: 'cantidad_pz'},
            {data: 'uom'},
            {data: 'cajas'},
            {data: 'footprint'},
            {data: 'estatus'},
            {data: 'lote'},
            {data: 'fecha_manufactura'},
            {data: 'fecha_expiracion'},
            {data: 'fecha_de_alta'},
            {data: 'ultimo_usuario'},
            {data: 'familia'},
            {data: 'descripcion'},
            {data: 'area'},
            {data: 'match_wd'},
            {data: 'real'},
            {data: 'incidencia'},
            {data: 'nota'},
            {data: 'comentario'},
            {data: 'auxiliary01'},
        ],
        "columnDefs": [
            {"width": "150px", "targets": 0},
            {"width": "100px", "targets": 1},
            {"width": "150px", "targets": 2},
            {"width": "120px", "targets": 3},
            {"width": "250px", "targets": 4},
            {"width": "120px", "targets": 5},
            {"width": "120px", "targets": 6},
            {"width": "120px", "targets": 7},
            {"width": "120px", "targets": 8},
            {"width": "120px", "targets": 9},
            {"width": "120px", "targets": 10},
            {"width": "130px", "targets": 11},
            {"width": "180px", "targets": 12},
            {"width": "180px", "targets": 13},
            {"width": "180px", "targets": 14},
            {"width": "130px", "targets": 15},
            {"width": "120px", "targets": 16},
            {"width": "220px", "targets": 17},
            {"width": "150px", "targets": 18},
            {"width": "150px", "targets": 19},
            {"width": "150px", "targets": 20},
            {"width": "150px", "targets": 21},
            {"width": "250px", "targets": 22},
            {"width": "250px", "targets": 23},
            {"width": "250px", "targets": 24},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200,300], [1, 5, 10, 30, 50, 100, 150, 200,300]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem !important;'> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Excel</span>",
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

