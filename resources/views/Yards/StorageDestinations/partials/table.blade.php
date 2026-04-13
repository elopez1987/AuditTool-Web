<script>
    var oTable = $("#table-trucks").DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "sScrollY": "450px",
        "serverSide": true,
        "processing": true,
        "ajax": {
            url: 'getDataDestinations',
            data: function (d) {
                d.name = $('input[name=name]').val();
            }
        },
        "columns": [
            {data: 'center.name', name: 'center.name'},
            {data: 'warehouse.name', name: 'warehouse.name'},
            {data: 'id'},
            {data: 'name'},
            {data: 'time'},
            {data: 'auxiliary01'},
            {data: 'auxiliary02'},
            {data: 'auxiliary03'},
            {data: 'auxiliary04'},
            {data: 'auxiliary05'},
            {data: 'status.name', name: 'status.name'},
            {data: 'btn'},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[25, 50, -1], [25, 50, "Todos"]],

        buttons: [
            {
                "extend": "excel",
                "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Exportar Excel</span>",
                "className": "btn waves-effect waves-light btn-outline-secondary btn-sm"
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

