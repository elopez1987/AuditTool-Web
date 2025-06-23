<script>
    $("#warehouseid").change(event => {
        $.get(`../getDataProviders/${event.target.value}`, function (res) {
            $("#providerid").empty();
            res.forEach(element => {
                $("#providerid").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    });
</script>
