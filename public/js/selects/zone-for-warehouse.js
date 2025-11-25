// /js/selects/zone-for-warehouse.js

export function initializeZoneLookup(warehouseSelectId, zoneSelectId) {
    
    // Función para inicializar el lookup
    const setupLookup = () => {
        if (typeof $ === 'undefined') {
            return;
        }

        // Usar jQuery para obtener los elementos
        const $selectWarehouse = $("#" + warehouseSelectId);
        const $selectZone = $("#" + zoneSelectId);

        if ($selectWarehouse.length === 0 || $selectZone.length === 0) {
            return;
        }
        // Usar el evento change con jQuery (compatible con Select2)
        $selectWarehouse.on("change", async function() {
            const warehouseId = $(this).val();
            if (!warehouseId || warehouseId === "") {
                // Limpiar el select si no hay warehouse seleccionado
                $selectZone.empty();
                $selectZone.append('<option value="">--- Seleccione una zona ---</option>');
                // Actualizar Select2 si está inicializado
                if ($selectZone.hasClass('select2-hidden-accessible')) {
                    $selectZone.trigger('change');
                }
                return;
            }

            const url = `/storage-zones/${encodeURIComponent(warehouseId)}/search`;
            try {
                const response = await fetch(url);

                if (!response.ok) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo obtener la lista de zonas.",
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
                    $selectZone.empty();
                    return;
                }

                // Limpiar select
                $selectZone.empty();
                $selectZone.append('<option value="">--- Seleccione una zona ---</option>');

                // Agregar opciones usando el id de la zona
                result.Data.forEach(item => {
                    $selectZone.append(`<option value="${item.id}">${item.name}</option>`);
                });


                // Actualizar Select2 si está inicializado
                if ($selectZone.hasClass('select2-hidden-accessible')) {
                    $selectZone.trigger('change');
                }

            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ocurrió un error al consultar zonas.",
                });
            }
        });
    }; // Fin de setupLookup

    // Ejecutar cuando el DOM esté listo
    if (typeof $ !== 'undefined' && $.fn) {
        $(document).ready(function() {
            setupLookup();
        });
    } else {
        document.addEventListener('DOMContentLoaded', setupLookup);
    }
}

// <script type="module">
//     import { initializeZoneLookup } from "/js/selects/zone-for-warehouse.js";
//     initializeZoneLookup("warehouseid", "zoneid");
// </script>

