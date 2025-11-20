// /js/selects/locations-for-zone.js

export function initializeLocationLookup(zoneSelectId, locationSelectId) {
    
    // Función para inicializar el lookup
    const setupLookup = () => {
        // Verificar que jQuery esté disponible
        if (typeof $ === 'undefined') {
            console.error("jQuery no está disponible para initializeLocationLookup");
            return;
        }

        // Usar jQuery para obtener los elementos
        const $selectZone = $("#" + zoneSelectId);
        const $selectLocation = $("#" + locationSelectId);

        if ($selectZone.length === 0 || $selectLocation.length === 0) {
            return;
        }



        // Usar el evento change con jQuery (compatible con Select2)
        $selectZone.on("change", async function() {
            const zoneId = $(this).val();

            if (!zoneId || zoneId === "") {
                // Limpiar el select si no hay zona seleccionada
                $selectLocation.empty();
                $selectLocation.append('<option value="">--- Seleccione una ubicación ---</option>');
                // Actualizar Select2 si está inicializado
                if ($selectLocation.hasClass('select2-hidden-accessible')) {
                    $selectLocation.trigger('change');
                }
                return;
            }

            const url = `/storage-locations/${encodeURIComponent(zoneId)}/search`;


            try {
                const response = await fetch(url);

                if (!response.ok) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo obtener la lista de ubicaciones.",
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
                    $selectLocation.empty();
                    return;
                }

                // Limpiar select
                $selectLocation.empty();
                $selectLocation.append('<option value="">--- Seleccione una ubicación ---</option>');

                // Agregar opciones usando el id de la ubicación
                result.Data.forEach(item => {
                    $selectLocation.append(`<option value="${item.id}">${item.name}</option>`);
                });

                // Actualizar Select2 si está inicializado
                if ($selectLocation.hasClass('select2-hidden-accessible')) {
                    $selectLocation.trigger('change');
                }

            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ocurrió un error al consultar ubicaciones.",
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
        // Fallback si jQuery no está disponible aún
        document.addEventListener('DOMContentLoaded', setupLookup);
    }
}

// <script type="module">
//     import { initializeLocationLookup } from "/js/selects/locations-for-zone.js";
//     initializeLocationLookup("zoneid", "locationid");
// </script>

