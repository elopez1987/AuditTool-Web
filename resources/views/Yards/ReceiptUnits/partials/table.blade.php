<script>
    /* Filtros que se muestran en pantalla y los del modal Yards.ReceiptUnits.filters.
       Es compartido por el datatable, las tarjetas de medibles y la exportación
       completa para que todos respeten exactamente los mismos criterios. */
    function getReceiptUnitsFilterParams() {
        return {
            folionumber: $('input[name=folionumber]').val(),
            boxnumber: $('input[name=boxnumber]').val(),
            location: $('input[name=location]').val(),
            econumber: $('input[name=econumber]').val(),
            trucknumber: $('input[name=trucknumber]').val(),
            barcode: $('input[name=barcode]').val(),
            shipmentid: $('input[name=shipmentid]').val(),
            carrierid: $('select[name=carrierid]').val(),
            warehouseid: $('select[name=warehouseid]').val(),
            rasonid: $('select[name=rasonid]').val(),
            statusid: $('select[name=statusid]').val(),
            unitstatusid: $('select[name=unitstatusid]').val(),
        };
    }

    $(document).ready(function () {
        let oTable = $("#table-boxsotck").DataTable({
        // const oTable = new DataTable('#table-boxsotck', {
            "paging": true,
            "searching": false,
            "ordering": true,
            "sScrollY": "450px",
            "sScrollX": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            // "autoWidth": true,
            "ajax": {
                url: 'getDataReceipts',
                data: function (d) {
                    $.extend(d, getReceiptUnitsFilterParams());
                }
            },
            "columns": [
                {data: 'btn'},
                {data: 'center.name',name:'center.name'},
                {data: 'warehouse.name',name:'warehouse.name'},
                {data: 'id'},
                {data: 'shipmentid'},
                {data: 'barcode'},
                {data: 'carrier.name',name:'carrier.name'},
                {data: 'boxnumber'},
                {data: 'econumber'},
                {data: 'tracto.trucknumber',name:'tracto.trucknumber'},
                {data: 'tracto.operatorname',name:'tracto.operatorname'},
                {data: 'tracto.phone',name:'tracto.phone'},
                {data: 'zone.name',name:'zone.name'},
                {data: 'location.name',name:'location.name'},
                {data: 'unitstatus.name',name:'unitstatus.name'},
                {
                    data: 'status.name', name: 'status.name',
                    render: function (data, type, rowData) {
                        return '<span style="background-color:' + rowData.status.auxiliary01 + '; color: black; padding: 2px 8px; border-radius: 4px; display: inline-block;">' + data + '</span>';
                    }
                },
                {data: 'truckcategory.description',name:'truckcategory.description'},
                {data: 'truck.name',name:'truck.name'},
                {data: 'event.name',name:'event.name'},
                {data: 'entrydate'},
                {data: 'unittype.description',name:'unittype.description'},
                {data: 'staytime',searchable: false},
                {data: 'tracto.id',name:'tracto.id'},
            ],
            // "order": [[2, 'desc']],
            "columnDefs": [
                {"width": "100px", "targets": 0,"visible": false},
                {"width": "200px", "targets": 1,"visible": false},
                {"width": "200px", "targets": 2},
                {"width": "180px", "targets": 3},
                {"width": "180px", "targets": 4},
                {"width": "180px", "targets": 5},
                {"width": "380px", "targets": 6},
                {"width": "150px", "targets": 7},
                {"width": "180px", "targets": 8,"visible": false},
                {"width": "180px", "targets": 9},
                {"width": "280px", "targets": 10},
                {"width": "150px", "targets": 11,"visible": false},
                {"width": "150px", "targets": 12,"visible": false},
                {"width": "150px", "targets": 13},
                {"width": "150px", "targets": 14},
                {"width": "250px", "targets": 15},
                {"width": "150px", "targets": 16,"visible": false},
                {"width": "150px", "targets": 17,"visible": false},
                {"width": "280px", "targets": 18},
                {"width": "180px", "targets": 19},
                {"width": "180px", "targets": 20,},
                {"width": "180px", "targets": 21,"visible": false},
                {"width": "180px", "targets": 22,"visible": false},
            ],

            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[1,5,10, 30, 50, 100, 150, 200, 250, -1], [1,5,10, 30, 50, 100, 150, 200, 250, "Todos"]],
            buttons: [
                {
                    "extend": "colvis",
                    "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Mostar/Ocultar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                    columns: ':not(:first):not(:last)'
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
            if (typeof loadReceiptUnitsKpis === 'function') {
                loadReceiptUnitsKpis();
            }
            e.preventDefault();
        });

        $('#table-boxsotck').on('blur', function () {
            $('#table-boxsotck tfoot input').val(''); // Limpia el footer
            oTable.draw(); // Redibuja la tabla
        });
    });
</script>

