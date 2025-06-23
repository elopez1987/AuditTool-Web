<script>
    $("#searchOperator").click(function () {
        if ($("#barcodeoperator").val().length === 0) {
            Swal.fire({
                type: 'error',
                title: 'Error...',
                text: 'Ingrese el numero del operador!',
            })
        } else {
            $.getJSON('../getDataOperator/' + $("#barcodeoperator").val(), function (values) {
                if (0 === values.length) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'No se encuentran registros del operador!',
                    })
                } else {
                    $("#operatorname").attr("value", values[0].name);
                    $("#drivernumber").attr("value", values[0].drivernumber);
                    $("#phone").attr("value", values[0].phone);
                }
            });
        }
    });
</script>
