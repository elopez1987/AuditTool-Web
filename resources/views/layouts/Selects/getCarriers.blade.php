<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{url('getCarriersSelect')}}',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var options = '';
                $.each(response, function (indice, row) {
                    options += "<option value='" + row.id + "'>" + row.name + "</option>";
                });
                $("#carrierid").html(options);
                $('#carrierid').select2({
                    placeholder: "Selecciona una o más líneas",
                    allowClear: true,
                    width: '100%'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error cargando carriers:', error);
                $('#carrierid').select2({
                    placeholder: "Error al cargar datos",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    });
</script>
