// /js/selects/search-truck.js

export function initTruckLookup({
                                    searchBarcodeButtonId = "searchTruck",
                                    searchTruckNumberButtonId = "searchTruckNumber",
                                    barcodeInputId = "barcodetruck",
                                    truckNumberInputId = "trucknumber",
                                    ecoNumberInputId = "ecotruck"
                                } = {}) {

    // Verificar que jQuery esté disponible
    if (typeof $ === 'undefined') {
        return;
    }

    // Función para realizar la búsqueda
    const searchTruck = async (searchValue, searchType) => {

        
        if (!searchValue || searchValue.trim() === "") {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: searchType === "barcode" ? "Ingrese el CB del tracto" : "Ingrese la placa del tracto",
            });
            return;
        }

        const url = `/storage-label-truck/${encodeURIComponent(searchValue.trim())}/search`;

        try {
            const response = await fetch(url);

            if (!response.ok) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Error en la consulta del tracto.",
                });
                return;
            }

            const json = await response.json();


            if (!json?.Data) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No se encuentran registros del tracto!",
                });
                
                // Limpiar campos
                $("#" + barcodeInputId).val("");
                $("#" + truckNumberInputId).val("");
                $("#" + ecoNumberInputId).val("");
                return;
            }

            // Llenar los campos con los datos obtenidos
            $("#" + barcodeInputId).val(json.Data.barcode ?? "");
            $("#" + truckNumberInputId).val(json.Data.boxnumber ?? "");
            $("#" + ecoNumberInputId).val(json.Data.econumber ?? "");

        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al consultar el tracto.",
            });
        }
    };

    // Esperar a que el DOM esté listo
    $(document).ready(function() {
        const $btnSearchBarcode = $("#" + searchBarcodeButtonId);
        const $btnSearchTruckNumber = $("#" + searchTruckNumberButtonId);
        const $inputBarcode = $("#" + barcodeInputId);
        const $inputTruckNumber = $("#" + truckNumberInputId);

        if ($btnSearchBarcode.length === 0 && $btnSearchTruckNumber.length === 0) {
            return;
        }


        // Botón de búsqueda por código de barras
        if ($btnSearchBarcode.length > 0) {
            $btnSearchBarcode.on("click", function() {
                const barcode = $inputBarcode.val();
                searchTruck(barcode, "barcode");
            });
        }

        // Botón de búsqueda por número de placa
        if ($btnSearchTruckNumber.length > 0) {
            $btnSearchTruckNumber.on("click", function() {
                const truckNumber = $inputTruckNumber.val();
                searchTruck(truckNumber, "trucknumber");
            });
        }
    });
}
