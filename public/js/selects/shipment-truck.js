// public/js/selects/shipment-truck.js

export function initShipmentTruckChange({
                                            selectId = "truckid",
                                            tractoSectionId = "tracto",
                                            barcodeInputId = "barcodetruck",
                                            truckNumberInputId = "trucknumber",
                                            ecoNumberInputId = "ecotruck",
                                            baseUrl = ""
                                        } = {}) {

    // Verificar que jQuery esté disponible
    if (typeof $ === 'undefined') {
        return;
    }
    // Esperar a que el DOM esté listo
    $(document).ready(function() {
        const $selectTruck = $("#" + selectId);
        const $tractoSection = $("#" + tractoSectionId);
        const $inputBarcode = $("#" + barcodeInputId);
        const $inputTruckNumber = $("#" + truckNumberInputId);
        const $inputEcoNumber = $("#" + ecoNumberInputId);

        if ($selectTruck.length === 0) {
            return;
        }
        $selectTruck.on("change", async function() {
            const truckId = $(this).val();
            if (!truckId || truckId === "") {
                return;
            }
            // Construir URL usando baseUrl
            const url = `${baseUrl}/shipment-truck/${encodeURIComponent(truckId)}/search`;
            console.log('Shipment Truck URL:', url); // Debug
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    return;
                }
                const json = await response.json();
                const isAuxN = json?.Data?.auxiliary02 === "N";
                if (isAuxN) {
                    // Ocultar sección de tracto usando jQuery
                    $tractoSection.hide();
                    // Establecer valores por defecto
                    $inputBarcode.val("0");
                    $inputTruckNumber.val("0");
                    $inputEcoNumber.val("0");
                } else {
                    // Mostrar sección de tracto
                    $tractoSection.show();
                    
                    // Limpiar valores
                    $inputBarcode.val("");
                    $inputTruckNumber.val("");
                    $inputEcoNumber.val("");
                }

            } catch (err) {
                console.error("Error consultando shipment-truck:", err);
            }
        });
    });
}
