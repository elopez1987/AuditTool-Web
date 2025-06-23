<script>
    $("#zoneid").change(event => {
        $.get(`../getLocationForZone/${event.target.value}`, function (res) {
            $("#locationid").empty();
            res.forEach(element => {
                $("#locationid").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    });
</script>
