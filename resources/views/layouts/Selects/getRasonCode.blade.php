<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{url('getRasonCode')}}',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var options = '';
                $.each(response, function (indice, row) {
                    options += "<option value='" + row.id + "'>" + row.name + "</option>";
                });
                $("#razonstatusid").html(options);
                $('#razonstatusid').select2({
                    placeholder: "Selecciona uno o más conceptos",
                    allowClear: true,
                    width: '100%'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error cargando conceptos:', error);
                $('#razonstatusid').select2({
                    placeholder: "Error al cargar datos",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    });
</script>
