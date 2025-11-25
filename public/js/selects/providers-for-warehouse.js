// public/js/selects/providers-for-warehouse.js

export function initProviderSelector({
    warehouseSelectId = "warehouseid",
    providerSelectId = "providerid",
    baseUrl = "/receipt-providers"
} = {}) {


    $(document).ready(function () {
        const $warehouseSelect = $("#" + warehouseSelectId);
        const $providerSelect = $("#" + providerSelectId);
        if ($warehouseSelect.length === 0) {
            return;
        }

        if ($providerSelect.length === 0) {
            return;
        }



        // Evento change en warehouseid
        $warehouseSelect.on("change", async function () {
            const warehouseId = $(this).val();
            if (!warehouseId) {
                $providerSelect.empty();
                $providerSelect.append('<option value="">Seleccione un proveedor</option>');
                return;
            }

            const url = `${baseUrl}/${encodeURIComponent(warehouseId)}/search`;

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    Swal.fire({
                        icon: "error",
                        title: "Error al consultar",
                        text: `Código de estado: ${response.status}`,
                    });
                    return;
                }

                const result = await response.json();

                if (!result.Data || !Array.isArray(result.Data) || result.Data.length === 0) {
                    Swal.fire({
                        icon: "info",
                        title: "Sin proveedores",
                        text: "No existen proveedores para este almacén.",
                    });

                    $providerSelect.empty();
                    $providerSelect.append('<option value="">No hay proveedores</option>');
                    return;
                }

                // Limpiar select y llenarlo con los nuevos datos
                $providerSelect.empty();
                $providerSelect.append('<option value="">Seleccione un proveedor</option>');

                result.Data.forEach(item => {
                    $providerSelect.append(
                        $('<option></option>').val(item.id).text(item.name)
                    );
                });

            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión",
                    text: "No se pudo obtener la información desde el servidor.",
                });
            }
        });
    });
}
