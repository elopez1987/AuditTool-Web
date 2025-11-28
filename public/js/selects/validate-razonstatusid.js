// public/js/selects/validate-razonstatusid.js

export function initRazonStatusBehavior({
                                            razonSelectId = "razonstatusid",
                                            unitStatusSelectId = "unitstatusid",
                                            proveedorDivId = "proveedor",
                                            selloDivId = "sello",
                                            containerSealId = "containerseal",
                                            shipmentIdId = "shipmentid",
                                            baseUrl = ""
                                        } = {}) {

    // Verificar que jQuery esté disponible
    if (typeof $ === 'undefined') {
        return;
    }

    // Función para llenar el select de unitstatus
    const loadUnitStatus = async (razonstatusid, $selectElement) => {
        let url;
        
        // Si razonstatusid es 1 o 2, consultar con parámetro
        if (razonstatusid === "1" || razonstatusid === "2") {
            url = `${baseUrl}/storage-unitstatus/${encodeURIComponent(razonstatusid)}/search`;
        } else {
            // Para otros valores, consultar sin parámetro (0 significa todos)
            url = `${baseUrl}/storage-unitstatus-search`;
        }
        
        console.log('Unit Status URL:', url); // Debug


        try {
            const response = await fetch(url);

            if (!response.ok) {

                return;
            }

            const json = await response.json();

            if (!json?.Data || !Array.isArray(json.Data)) {
                return;
            }

            // Limpiar y llenar el select
            $selectElement.empty();
            $selectElement.append('<option value="">-- Estado de unidad --</option>');
            
            json.Data.forEach(item => {
                $selectElement.append(`<option value="${item.id}">${item.name}</option>`);
            });
            
            // Actualizar Select2 si está inicializado
            if ($selectElement.hasClass('select2-hidden-accessible')) {
                $selectElement.trigger('change');
            }


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

        if ($razonSelect.length === 0) {
            return;
        }

        // Ocultar al iniciar
        $divProveedor.hide();
        $divSello.hide();

        // Escuchar cambios en el select
        $razonSelect.on("change", async function() {
            const value = $(this).val();


            // Llenar unitstatusid según el valor
            if ($unitStatusSelect.length > 0) {
                await loadUnitStatus(value, $unitStatusSelect);
            }

            // Mostrar/ocultar proveedor y sello
            if (value === "2") {
                $divProveedor.show();
                $divSello.show();
                $inputSeal.val("");
                $inputShipment.val("");
            } else {
                $divProveedor.hide();
                $divSello.hide();
                $inputSeal.val("0");
                $inputShipment.val("0");
            }
        });
    });
}
