<script>
    $("#centerid").change(event => {
        $.get(`../getTrucksForCenter/${event.target.value}`, function (res) {
            $("#truckid").empty();
            res.forEach(element => {
                $("#truckid").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    });
</script>
