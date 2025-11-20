// public/js/selects/validate-razonstatusid.js

export function initRazonStatusBehavior({
                                            razonSelectId = "razonstatusid",
                                            unitStatusSelectId = "unitstatusid",
                                            proveedorDivId = "proveedor",
                                            selloDivId = "sello",
                                            containerSealId = "containerseal",
                                            shipmentIdId = "shipmentid"
                                        } = {}) {

    // Verificar que jQuery esté disponible
    if (typeof $ === 'undefined') {
        console.error("jQuery no está disponible para initRazonStatusBehavior");
        return;
    }

    // Función para llenar el select de unitstatus
    const loadUnitStatus = async (razonstatusid, $selectElement) => {
        let url;
        
        // Si razonstatusid es 1 o 2, consultar con parámetro
        if (razonstatusid === "1" || razonstatusid === "2") {
            url = `/storage-unitstatus/${encodeURIComponent(razonstatusid)}/search`;
        } else {
            // Para otros valores, consultar sin parámetro (0 significa todos)
            url = `/storage-unitstatus-search`;
        }
        
        console.log("Cargando unit status desde:", url);

        try {
            const response = await fetch(url);

            if (!response.ok) {
                console.error("Error al obtener unit status:", response.status);
                return;
            }

            const json = await response.json();
            
            console.log("Unit status recibidos:", json);

            if (!json?.Data || !Array.isArray(json.Data)) {
                console.error("Formato inesperado en respuesta de unit status");
                return;
            }

            // Limpiar y llenar el select
            $selectElement.empty();
            
            json.Data.forEach(item => {
                $selectElement.append(`<option value="${item.id}">${item.name}</option>`);
            });
            
            console.log("Select unitstatusid llenado con", json.Data.length, "opciones");

        } catch (error) {
            console.error("Error consultando unit status:", error);
        }
    };

    // Esperar a que el DOM esté listo
    $(document).ready(function() {
        const $razonSelect = $("#" + razonSelectId);
        const $unitStatusSelect = $("#" + unitStatusSelectId);
        const $divProveedor = $("#" + proveedorDivId);
        const $divSello = $("#" + selloDivId);
        const $inputSeal = $("#" + containerSealId);
        const $inputShipment = $("#" + shipmentIdId);

        console.log("Inicializando razonstatusid behavior");
        console.log("- razonSelect:", $razonSelect.length > 0);
        console.log("- unitStatusSelect:", $unitStatusSelect.length > 0);
        console.log("- divProveedor:", $divProveedor.length > 0);
        console.log("- divSello:", $divSello.length > 0);

        if ($razonSelect.length === 0) {
            console.warn("No se encontró el select razonstatusid");
            return;
        }

        // Ocultar al iniciar
        $divProveedor.hide();
        $divSello.hide();

        // Escuchar cambios en el select
        $razonSelect.on("change", async function() {
            const value = $(this).val();
            
            console.log("Razonstatusid cambiado a:", value);

            // Llenar unitstatusid según el valor
            if ($unitStatusSelect.length > 0) {
                await loadUnitStatus(value, $unitStatusSelect);
            }

            // Mostrar/ocultar proveedor y sello
            if (value === "2") {
                console.log("Mostrando proveedor y sello");
                $divProveedor.show();
                $divSello.show();
                $inputSeal.val("");
                $inputShipment.val("");
            } else {
                console.log("Ocultando proveedor y sello");
                $divProveedor.hide();
                $divSello.hide();
                $inputSeal.val("0");
                $inputShipment.val("0");
            }
        });

        console.log("Listener registrado en razonstatusid");
    });
}
