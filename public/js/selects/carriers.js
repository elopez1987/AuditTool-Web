document.addEventListener("DOMContentLoaded", () => {

    const selectCenter = document.getElementById("centerid");
    const selectCarrier = document.getElementById("carrierid");

    if (!selectCenter || !selectCarrier) return;

    selectCenter.addEventListener("change", async (event) => {
        const centerId = event.target.value;
        if (!centerId) return;

        const url = `/shipment-carriers/${centerId}/search`;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se pudo obtener la lista de almacenes.",
                });
                return;
            }

            const result = await response.json();

            if (!result.Data || !Array.isArray(result.Data)) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Formato inesperado del servidor.",
                });
                return;
            }

            selectCarrier.innerHTML = "";

            result.Data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.id;
                option.textContent = item.name;
                selectCarrier.appendChild(option);
            });

        } catch (error) {
            console.error("Error realizando la petición:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al consultar almacenes.",
            });
        }
    });

});
