// /js/selects/warehouse-for-center.js

export function initializeWarehouseLookup(centerSelectId, warehouseSelectId) {
    
    // Función para inicializar el lookup
    const setupLookup = () => {
        // Verificar que jQuery esté disponible
        if (typeof $ === 'undefined') {
            return;
        }

        // Usar jQuery para obtener los elementos
        const $selectCenter = $("#" + centerSelectId);
        const $selectWarehouse = $("#" + warehouseSelectId);

        if ($selectCenter.length === 0 || $selectWarehouse.length === 0) {
            return;
        }



    // Usar el evento change con jQuery (compatible con Select2)
    $selectCenter.on("change", async function() {
        const centerId = $(this).val();

        if (!centerId || centerId === "") {
            // Limpiar el select si no hay centro seleccionado
            $selectWarehouse.empty();
            $selectWarehouse.append('<option value="">--- Seleccione un almacén ---</option>');
            // Actualizar Select2 si está inicializado
            if ($selectWarehouse.hasClass('select2-hidden-accessible')) {
                $selectWarehouse.trigger('change');
            }
            return;
        }

        const url = `/system-warehouses/${encodeURIComponent(centerId)}/search`;
        


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
                $selectWarehouse.empty();
                return;
            }

            // Limpiar select
            $selectWarehouse.empty();
            $selectWarehouse.append('<option value="">--- Seleccione un almacén ---</option>');

            // Agregar opciones
            result.Data.forEach(item => {
                $selectWarehouse.append(`<option value="${item.warehouseid}">${item.warehousename}</option>`);
            });

            // Actualizar Select2 si está inicializado
            if ($selectWarehouse.hasClass('select2-hidden-accessible')) {
                $selectWarehouse.trigger('change');
            }

        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al consultar almacenes.",
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
//     import { initializeWarehouseLookup } from "public/js/selects/warehouse-for-center.js";
//     initializeWarehouseLookup("centerid", "warehouseid");
// </script>

