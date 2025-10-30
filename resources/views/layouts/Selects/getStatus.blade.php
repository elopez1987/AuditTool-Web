<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{url('getStatusForSchemaRUS')}}',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var options = '';
                $.each(response, function (indice, row) {
                    options += "<option value='" + row.statusid + "'>" + row.name + "</option>";
                });
                $("#statusid").html(options);
                $('#statusid').select2({
                    placeholder: "Selecciona uno o más estatus",
                    allowClear: true,
                    width: '100%'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error cargando status:', error);
                $('#statusid').select2({
                    placeholder: "Error al cargar datos",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    });
</script>
