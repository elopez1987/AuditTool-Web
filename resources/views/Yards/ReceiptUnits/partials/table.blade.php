<script>
    $(document).ready(function () {
        let oTable = $("#table-boxsotck").DataTable({
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
            "autoWidth": true,
            // "colResize": {
            //     handleWidth: 10,
            //     resizeCallback: function() {
            //         console.log("Columna ajustada");
            //     }
            // },
            "ajax": {
                url: 'getDataReceipts',
                data: function (d) {
                    d.folionumber = $('input[name=folionumber]').val();
                    d.boxnumber = $('input[name=boxnumber]').val();
                    d.location = $('input[name=location]').val();
                    d.econumber = $('input[name=econumber]').val();
                    d.trucknumber = $('input[name=trucknumber]').val();
                    d.barcode = $('input[name=barcode]').val();
                    d.shipmentid = $('input[name=shipmentid]').val();
                    d.carrierid = $('select[name=carrierid]').val();
                    d.centerid = $('select[name=centerid]').val();
                    d.warehouseid = $('select[name=warehouseid]').val();
                    d.rasonid = $('select[name=rasonid]').val();
                    d.statusid = $('select[name=statusid]').val();
                }
            },
            "columns": [
                {data: 'btn'},
                {data: 'center.description','name':'centers.description'},
                {data: 'warehouse.name','name':'warehouses.name'},
                {data: 'id'},
                {data: 'shipmentid'},
                {data: 'carrier.name','name':'carriers.name'},
                {data: 'boxnumber'},
                {data: 'econumber'},
                {data: 'tracto.trucknumber','name':'tractos.trucknumber'},
                {data: 'tracto.operatorname','name':'tractos.operatorname'},
                {data: 'tracto.phone','name':'tractos.phone'},
                {data: 'barcode'},
                {data: 'zone.name','name':'zones.name'},
                {data: 'location.name','name':'locations.name'},
                {data: 'unitstatus.name','name':'unitstatuses.name'},
                {
                    data: 'status.name', name: 'status.name',
                    createdCell: function (td, cellData, rowData) {
                        $(td).css({
                            'background-color': (rowData.status.auxiliary01),
                            'color': 'black'
                        });
                    }
                },
                {data: 'truckcategory.description','name':'truckcategories.description'},
                {data: 'truck.name','name':'tractos.name'},
                {data: 'event.name','name':'events.name'},
                {data: 'entrydate'},
                {data: 'unittype.description','name':'unittypes.description'},
                {data: 'staytime'},
                {data: 'tracto.id','name':'tractos.id'},
            ],
            "order": [[2, 'desc']],
            "columnDefs": [
                {"width": "100px", "targets": 0},
                {"width": "250px", "targets": 1},
                {"width": "200px", "targets": 2},
                {"width": "150px", "targets": 3},
                {"width": "250px", "targets": 4},
                {"width": "280px", "targets": 5},
                {"width": "150px", "targets": 6},
                {"width": "150px", "targets": 7},
                {"width": "250px", "targets": 8},
                {"width": "250px", "targets": 9},
                {"width": "150px", "targets": 10},
                {"width": "150px", "targets": 11},
                {"width": "150px", "targets": 12},
                {"width": "150px", "targets": 13},
                {"width": "150px", "targets": 14},
                {"width": "250px", "targets": 15},
                {"width": "150px", "targets": 16},
                {"width": "150px", "targets": 17},
                {"width": "280px", "targets": 18},
                {"width": "180px", "targets": 19},
                {"width": "180px", "targets": 20},
                {"width": "180px", "targets": 21},
                {"width": "180px", "targets": 22},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[1,5,10, 30, 50, 100, 150, 200, 250, -1], [1,5,10, 30, 50, 100, 150, 200, 250, "Todos"]],
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

