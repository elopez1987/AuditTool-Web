<script>
    var oTable =   $("#table-logs").DataTable({
        "paging"      : true,
        "searching"  : false,
        "ordering"    : true,
        "sScrollY": "450px",
        "serverSide" : true,
        "ajax" : {
            url : 'getDataActivityLog',
            data: function (d) {
                d.description = $('select[name=description]').val();
                d.model = $('select[name=model]').val();
                d.user = $('select[name=user]').val();
                d.date_in = $('text[name=date_in]').val();
                d.date_out = $('text[name=date_out]').val();
                d.id = $('input[name=id]').val();
                d.subject_id = $('input[name=subject_id]').val();
            }
        },
        "columns" : [
            {data : 'id'},
            {data : 'description'},
            {data : 'subject_type'},
            {data : 'subject_id'},
            {data : 'user.name',name:'user.name'},
            {data : 'created_at'},
            {data : 'updated_at'},
            {data : 'btn'},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],

        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\" alt='' class='img-16 ml-2 mr-2' ><span> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "csv",
                "text": "<img src=\"{{asset('/ico/csv.png')}}\" alt='' class='img-16 ml-2 mr-2' ><span> Csv</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
            },
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\" alt='' class='img-16 ml-2 mr-2'><span> Excel</span>",
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

