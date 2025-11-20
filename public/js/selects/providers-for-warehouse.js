// public/js/selects/providers-for-warehouse.js

export function initProviderSelector({
    warehouseSelectId = "warehouseid",
    providerSelectId = "providerid",
    baseUrl = "/receipt-providers"
} = {}) {

    console.log("Inicializando provider selector");
    console.log("- warehouseSelectId:", warehouseSelectId);
    console.log("- providerSelectId:", providerSelectId);
    console.log("- baseUrl:", baseUrl);

    $(document).ready(function () {
        const $warehouseSelect = $("#" + warehouseSelectId);
        const $providerSelect = $("#" + providerSelectId);

        if ($warehouseSelect.length === 0) {
            console.error("No se encontró el select de warehouse con id:", warehouseSelectId);
            return;
        }

        if ($providerSelect.length === 0) {
            console.error("No se encontró el select de provider con id:", providerSelectId);
            return;
        }

        console.log("Selects encontrados correctamente");

        // Evento change en warehouseid
        $warehouseSelect.on("change", async function () {
            const warehouseId = $(this).val();
            console.log("Warehouse cambiado a:", warehouseId);

            if (!warehouseId) {
                console.log("No hay warehouse seleccionado, limpiando providers");
                $providerSelect.empty();
                $providerSelect.append('<option value="">Seleccione un proveedor</option>');
                return;
            }

            const url = `${baseUrl}/${encodeURIComponent(warehouseId)}/search`;
            console.log("Consultando URL:", url);

            try {
                const response = await fetch(url);
                console.log("Respuesta recibida:", response.status, response.statusText);

                if (!response.ok) {
                    Swal.fire({
                        icon: "error",
                        title: "Error al consultar",
                        text: `Código de estado: ${response.status}`,
                    });
                    return;
                }

                const result = await response.json();
                console.log("Datos recibidos:", result);

                if (!result.Data || !Array.isArray(result.Data) || result.Data.length === 0) {
                    console.warn("No hay proveedores para este warehouse");
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
                console.log("Llenando select con", result.Data.length, "proveedores");
                $providerSelect.empty();
                $providerSelect.append('<option value="">Seleccione un proveedor</option>');

                result.Data.forEach(item => {
                    console.log("Agregando provider:", item.id, item.name);
                    $providerSelect.append(
                        $('<option></option>').val(item.id).text(item.name)
                    );
                });

                console.log("Select de providers llenado exitosamente");

            } catch (error) {
                console.error("Error en la petición:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión",
                    text: "No se pudo obtener la información desde el servidor.",
                });
            }
        });

        console.log("Provider selector inicializado correctamente");
    });
}
