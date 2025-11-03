<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{url('getWarehousesForUser')}}',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var options = '';
                $.each(response, function (indice, row) {
                    options += "<option value='" + row.warehouseid + "'>" + row.name + "</option>";
                });
                $("#warehouseid").html(options);
                $('#warehouseid').select2({
                    placeholder: "Selecciona una o más bodegas",
                    allowClear: true,
                    width: '100%'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error cargando bodegas:', error);
                $('#warehouseid').select2({
                    placeholder: "Error al cargar datos",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    });
</script>
