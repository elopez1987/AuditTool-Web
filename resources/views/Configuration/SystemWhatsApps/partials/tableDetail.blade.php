<script>
    var oTable = $("#table-detailkardex").DataTable({
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
            // url: '../getKardexEventsForWhatApp',
            url: '{{url('getKardexEventsForWhatApp')}}',
            data: function (d) {
                d.whatsappid = '{!! $systemwhatsapp->whatsappid !!}';
                d.phonenumber = $('input[name=phonenumber]').val();
                d.shipmentid = $('input[name=shipmentid]').val();
                d.folionumber = $('input[name=folionumber]').val();
            }
        },
        "columns": [
            {data: 'centername'},
            {data: 'shipmentid'},
            {data: 'folionumber'},
            {data: 'message'},
            {data: 'chatid'},
            {data: 'value02'},
            {data: 'value03'},
            {data: 'created_at'},
        ],
        "columnDefs": [
            {"width": "150px", "targets": 0},
            {"width": "150px", "targets": 1},
            {"width": "150px", "targets": 2},
            {"width": "280px", "targets": 3},
            {"width": "150px", "targets": 4},
            {"width": "280px", "targets": 5},
            {"width": "280px", "targets": 6},
            {"width": "280px", "targets": 7},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200], [1, 5, 10, 30, 50, 100, 150, 200]],
        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
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

