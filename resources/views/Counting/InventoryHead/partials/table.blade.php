<script>
    var oTable = $("#table-inventoryhead").DataTable({
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
            url: 'getDataInventory',
            data: function (d) {
                d.statusid = $('select[name=statusid]').val();
            }
        },
        initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    let column = this;
                    let title = column.footer().textContent;
                    let input = document.createElement('input');
                    input.placeholder = title;
                    column.footer().replaceChildren(input);
                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });
        },
        "columns": [
            {data: 'centername'},
            {data: 'warehouse'},
            {data: 'id'},
            {data: 'type'},
            {data: 'description'},
            {data: 'createdate'},
            {data: 'startdate'},
            {data: 'finishdate'},
            {data: 'user'},
            {data: 'inventorytype'},
            {
                data: 'status',
                render: function (data, type) {
                    if (type === 'display') {
                        return data;
                    }
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.statusid === 'AT') {
                        var label = $("<label class='label'>").css({
                            'background-color': '#76ff03',
                            'color': 'black',
                        }).text(rowData.status);
                        $(td).empty().append(label);
                    }
                    if (rowData.statusid === 'TO') {
                        var label = $("<label class='label'>").css({
                            'background-color': '#ff1744',
                            'color': 'white',
                        }).text(rowData.status);
                        $(td).empty().append(label);
                    }
                    if (rowData.statusid === 'PN') {
                        var label = $("<label class='label'>").css({
                            'background-color': '#ffff00',
                            'color': 'black',
                        }).text(rowData.status);
                        $(td).empty().append(label);
                    }
                }
            },
            {data: 'btn'},
        ],
        "columnDefs": [
            {"width": "220px", "targets": 1, "defaultContent": "-"},
            {"width": "220px", "targets": 2, "defaultContent": "-"},
            {"width": "100px", "targets": 3, "defaultContent": "-"},
            {"width": "250px", "targets": 4, "defaultContent": "-"},
            {"width": "150px", "targets": 5, "defaultContent": "-"},
            {"width": "150px", "targets": 6, "defaultContent": "-"},
            {"width": "150px", "targets": 7, "defaultContent": "-"},
            {"width": "150px", "targets": 8, "defaultContent": "-"},
            {"width": "150px", "targets": 9, "defaultContent": "-"},
            {"width": "150px", "targets": 10, "defaultContent": "-"},
            {"width": "180px", "targets": 11, "defaultContent": "-"},
        ],
        "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],

        buttons: [
            {
                "extend": "colvis",
                "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Mostar/Ocultar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "copy",
                "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Copiar</span>",
                "className": "btn btn-outline-info btn-md  btn-bold",
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

