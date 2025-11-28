
export function initBoxSearch(baseUrl = '') {

    const btnSearch   = document.getElementById("searchBox");
    const inputBox    = document.getElementById("barcodebox");

    const boxNumber   = document.getElementById("boxnumber");
    const ecoBox      = document.getElementById("ecobox");
    const carrierSel  = document.getElementById("carrierid");
    const truckSel    = document.getElementById("truckid");

    if (!btnSearch) return;

    btnSearch.addEventListener("click", async () => {

        const barcode = inputBox.value.trim();

        if (!barcode) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ingrese el número de caja",
            });
            return;
        }

        const url = `${baseUrl}/storage-label-box/${encodeURIComponent(barcode)}/search`;
        console.log('Box URL:', url); // Debug

        try {
            const response = await fetch(url);

            if (!response.ok) {
                Swal.fire({
                    icon: "error",
                    title: "Error en servidor",
                    text: `Código de estado: ${response.status}`,
                });
                return;
            }

            const result = await response.json();

            if (!result?.Data) {
                Swal.fire({
                    icon: "error",
                    title: "No encontrado",
                    text: "No existen registros de la caja.",
                });
                return;
            }

            const data = result.Data;

            boxNumber.value = data.boxnumber ?? "";
            ecoBox.value    = data.econumber ?? "";

            carrierSel.innerHTML = "";
            if (data.carrier) {
                const opt = document.createElement("option");
                opt.value = data.carrier.id;
                opt.textContent = data.carrier.name;
                carrierSel.appendChild(opt);
            }

            truckSel.innerHTML = "";
            if (data.truck) {
                const opt = document.createElement("option");
                opt.value = data.truck.id;
                opt.textContent = data.truck.name;
                truckSel.appendChild(opt);
            }

        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo consultar la información.",
            });
        }
    });
}