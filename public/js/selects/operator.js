// public/js/selects/operator.js

export function initOperatorLookup(buttonId, inputBarcodeId, fieldsMap = {}, baseUrl = '') {

    document.addEventListener("DOMContentLoaded", () => {

        const btn = document.getElementById(buttonId);
        const barcodeInput = document.getElementById(inputBarcodeId);

        if (!btn || !barcodeInput) {
            return;
        }

        btn.addEventListener("click", async () => {
            const barcode = barcodeInput.value.trim();

            if (!barcode) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ingrese el número del operador!",
                });
                return;
            }

            const url = `${baseUrl}/storage-operator/${encodeURIComponent(barcode)}/search`;
            console.log('Operator URL:', url); // Debug

            try {
                const response = await fetch(url);

                if (!response.ok) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo obtener la información del operador.",
                    });
                    return;
                }

                const json = await response.json();

                if (!json?.Data) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No se encuentran registros del operador!",
                    });
                    return;
                }

                // Rellena los campos indicados
                Object.entries(fieldsMap).forEach(([fieldId, jsonKey]) => {
                    const fieldElement = document.getElementById(fieldId);
                    if (fieldElement) {
                        fieldElement.value = json.Data[jsonKey] ?? "";
                    }
                });

            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ocurrió un error al consultar el operador.",
                });
            }
        });

    });

}
