<script>
    $("#centerid").change(event => {
        $.get(`../getCarriersForCenter/${event.target.value}`, function (res) {
            $("#carrierid").empty();
            res.forEach(element => {
                $("#carrierid").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    });
</script>
