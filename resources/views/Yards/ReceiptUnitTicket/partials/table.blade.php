<script>
    $(document).ready(function () {
        let oTable = $("#table-tickets").DataTable({
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
                url: 'getUnitTickets',
                data: function (d) {
                    d.warehouseid = $('select[name=warehouseid]').val();
                    d.folionumber = $('input[name=folionumber]').val();
                    d.boxnumber = $('input[name=boxnumber]').val();
                    d.statusid = $('select[name=statusid]').val();
                }
            },
            "columns": [
                {data: 'warehousename'},
                {data: 'folionumber'},
                {data: 'ticket'},
                {data: 'carriername'},
                {data: 'boxnumber'},
                {data: 'operatorname'},
                {data: 'weightin'},
                {data: 'weightinlb'},
                {data: 'btn'},
            ],
            "columnDefs": [
                {"width": "350px", "targets": 0},
                {"width": "180px", "targets": 1},
                {"width": "150px", "targets": 2},
                {"width": "250px", "targets": 3},
                {"width": "250px", "targets": 4},
                {"width": "250px", "targets": 5},
                {"width": "250px", "targets": 6},
                {"width": "250px", "targets": 7},
                {"width": "250px", "targets": 8},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
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
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Registros",
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

        $('#table-boxsotck').on('blur', function () {
            $('#table-boxsotck tfoot input').val(''); // Limpia el footer
            oTable.draw(); // Redibuja la tabla
        });
    });
</script>

