// public/js/selects/providers-for-warehouse.js

export function initProviderSelector({
    warehouseSelectId = "warehouseid",
    providerSelectId = "providerid",
    baseUrl = ""
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
                // Actualizar Select2
                if ($providerSelect.hasClass('select2-hidden-accessible')) {
                    $providerSelect.trigger('change');
                }
                return;
            }

            // Construir URL completa
            const url = `${baseUrl}/receipt-providers/${encodeURIComponent(warehouseId)}/search`;
            // console.log('Provider URL:', url); // Debug

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
                    $providerSelect.empty();
                    $providerSelect.append('<option value="">No hay proveedores</option>');
                    // Actualizar Select2
                    if ($providerSelect.hasClass('select2-hidden-accessible')) {
                        $providerSelect.trigger('change');
                    }
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
                
                // Actualizar Select2
                if ($providerSelect.hasClass('select2-hidden-accessible')) {
                    $providerSelect.trigger('change');
                }

            } catch (error) {
                // console.error('Error al cargar proveedores:', error);
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión",
                    text: "No se pudo obtener los proveedores desde el servidor. " + error.message,
                });
                $providerSelect.empty();
                $providerSelect.append('<option value="">Error al cargar</option>');
                if ($providerSelect.hasClass('select2-hidden-accessible')) {
                    $providerSelect.trigger('change');
                }
            }
        });
    });
}
