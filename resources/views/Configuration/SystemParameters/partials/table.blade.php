<script>
    var oTable =   $("#table-parameters").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "450px",
        // "sScrollX": true,
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "colReorder": true,
        "stateSave": true,
        "stateDuration": 0,
        "ajax" : {
            url : 'getDataParameters',
            data: function (d) {
                d.name = $('input[name=name]').val();
                d.parameterid = $('input[name=parameterid]').val();
            }
        },
        "columns" : [
            {data : 'parameterid'},
            {data : 'name'},
            {data : 'auxiliary01'},
            {data : 'auxiliary02'},
            {data : 'auxiliary03'},
            {data : 'auxiliary04'},
            {data : 'auxiliary05'},
            {data:'status.name',name:'status.name'},
            {data : 'btn'},
        ],
        "columnDefs": [
            {"width": "100px", "targets": 0},
            {"width": "450px", "targets": 1},
            {"width": "150px", "targets": 2},
            {"width": "120px", "targets": 3},
            {"width": "120px", "targets": 4},
            {"width": "120px", "targets": 5},
            {"width": "120px", "targets": 6},
            {"width": "120px", "targets": 7},
            {"width": "120px", "targets": 8},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[1,5,10, 30, 50, 100, 150, 200, 250, -1], [1,5,10, 30, 50, 100, 150, 200, 250, "Todos"]],
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
    $('#search').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
</script>

