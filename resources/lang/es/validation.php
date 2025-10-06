<?php

/**
 * Created by PhpStorm.
 * User: Edgar Prisciliano L
 * Date: 25/01/16
 * Time: 04:22 PM
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */
    'accepted' => ':attribute debe ser aceptado.',
    'active_url' => ':attribute no es una URL válida.',
    'after' => ':attribute debe ser una fecha posterior a :date.',
    'alpha' => ':attribute solo debe contener letras.',
    'alpha_dash' => ':attribute solo debe contener letras, números y guiones.',
    'alpha_num' => ':attribute solo debe contener letras y números.',
    'array' => ':attribute debe ser un conjunto.',
    'before' => ':attribute debe ser una fecha anterior a :date.',
    'between' => [
        'numeric' => ':attribute tiene que estar entre :min - :max.',
        'file' => ':attribute debe pesar entre :min - :max kilobytes.',
        'string' => ':attribute tiene que tener entre :min - :max caracteres.',
        'array' => ':attribute tiene que tener entre :min - :max ítems.',
    ],
    'boolean' => 'El campo :attribute debe tener un valor verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'date' => ':attribute no es una fecha válida.',
    'date_format' => ':attribute no corresponde al formato :format.',
    'different' => ':attribute y :other deben ser diferentes.',
    'digits' => ':attribute debe tener :digits dígitos.',
    'digits_between' => ':attribute debe tener entre :min y :max dígitos.',
    'email' => ':attribute no es un correo válido',
    'exists' => ':attribute es inválido.',
    'filled' => 'El campo :attribute es obligatorio.',
    'image' => ':attribute debe ser una imagen.',
    'in' => ':attribute es inválido.',
    'integer' => ':attribute debe ser un número entero.',
    'ip' => ':attribute debe ser una dirección IP válida.',
    'json' => 'El campo :attribute debe tener una cadena JSON válida.',
    'max' => [
        'numeric' => ':attribute no debe ser mayor a :max.',
        'file' => ':attribute no debe ser mayor que :max kilobytes.',
        'string' => ':attribute no debe ser mayor que :max caracteres.',
        'array' => ':attribute no debe tener más de :max elementos.',
    ],
    'mimes' => ':attribute debe ser un archivo con formato: :values.',
    'min' => [
        'numeric' => 'El tamaño de :attribute debe ser de al menos :min.',
        'file' => 'El tamaño de :attribute debe ser de al menos :min kilobytes.',
        'string' => ':attribute debe contener al menos :min caracteres.',
        'array' => ':attribute debe tener al menos :min elementos.',
    ],
    'not_in' => ':attribute es inválido.',
    'numeric' => ':attribute debe ser numérico.',
    'regex' => 'El formato de :attribute es inválido.',
    'required' => 'El campo :attribute es obligatorio.',
    'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all' => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_without' => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values estén presentes.',
    'same' => ':attribute y :other deben coincidir.',
    'size' => [
        'numeric' => 'El tamaño de :attribute debe ser :size.',
        'file' => 'El tamaño de :attribute debe ser :size kilobytes.',
        'string' => ':attribute debe contener :size caracteres.',
        'array' => ':attribute debe contener :size elementos.',
    ],
    'string' => 'El campo :attribute debe ser una cadena de caracteres.',
    'timezone' => 'El :attribute debe ser una zona válida.',
    'unique' => ':attribute ya ha sido registrado.',
    'url' => 'El formato :attribute es inválido.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
        'name' => 'Nombre',
        'auxiliary01' => 'Auxiliar 01',
        'auxiliary02' => 'Auxiliar 02',
        'auxiliary03' => 'Auxiliar 03',
        'auxiliary04' => 'Auxiliar 04',
        'auxiliary05' => 'Auxiliar 05',
        'auxiliary06' => 'Auxiliar 06',
        'auxiliary07' => 'Auxiliar 07',
        'auxiliary08' => 'Auxiliar 08',
        'auxiliary09' => 'Auxiliar 09',
        'auxiliary10' => 'Auxiliar 10',
        'centerid' => 'C.Distribución',
        'statusid' => 'Estatus',
        'subject' => 'Nombre del Correo',
        'warehouseid' => 'Bodega',
        'mailto' => 'Nombre de Contacto',
        'email' => 'Correo Electronico',
        'role_id' => 'Perfil de Usuario',
        'date_in' => 'F.Inicio',
        'date_out' => 'F.Final',
        'time' => 'Tiempo de traslado (Hrs)',
        'workday' => 'Fecha de trabajo',
        'turnid' => 'Turno',
        'truckid' => 'Tipo de unidad',
        'carrierid' => 'Linea de transporte',
        'destinationid' => 'Destino',
        'shipmentid' => 'Embarque',
        'customerid' => 'Cliente',
        'shipmenttypeid' => 'Tipo de embarque',
        'loadingdate' => 'Fecha de carga',
        'downloaddate' => 'Fecha de descarga',
        'boxnumber' => 'Placas de caja',
        'trucknumber' => 'Placas de tracto',
        'drivernumber' => 'Licencia',
        'phone' => 'Celular',
        'razonstatusid' => 'Evento',
        'containerseal' => 'Sellos',
        'zoneid' => 'Zona',
        'locationid' => 'Ubicación',
        'operatorname' => 'Nombre del operador',
        'containerseal01' => 'Sello 01',
        'containerseal02' => 'Sello 02',
        'containerseal03' => 'Sello 03',
        'receivername' => 'Recibió',
        'totalweight' => 'Peso Total',
        'totalvolume' => 'Volumen Total',
        'totalpieces' => 'Cajas pick',
        'totalcontainers' => 'Total de pallets',
        'providerid' => 'Proveedor',
        'unitstatusid' => 'Estatus de unidad',

        'prtnum' => 'Numero de Parte',
        'lngdsc' => 'Descripción',
        'abccod' => 'Código ABC',
        'shelflife' => 'Tiempo de Vida',
        'stkuom' => 'Unidad de Medida',
        'palnetwgt' => 'Peso Neto PAL',
        'palgrswgt' => 'Peso Bruto PAL',
        'caslvl' => 'Cajas por Cama',
        'untcas' => 'Unidades por Caja',
        'untpal' => 'Cajas por PAL',
        'palwid' => 'Ancho de PAL',
        'pallen' => 'Largo de PAL',
        'palhgt' => 'Alto de PAL',
        'category01id' => 'Categoria 01',
        'category02id' => 'Categoria 02',
        'quantity' => 'Cantidad',
        'productiondate' => 'F.Producción',
        'batch' => 'Lote',

        'barcodelocation' => 'CB.Ubicación',
        'barcodebox' => 'CB. de Caja',
        'whatsappid' => 'Clave de Notificación',
        'truckcategoryid' => 'Categoria de Caja',
        'description' => 'Descripción',
        'timetransfer' => 'Tiempo de tránsito',
        'timebulkassembly' => 'Armado Bulk',
        'timearmedpick' => 'Armando Pick',
        'timecheck' => 'Tiempo de verificación',
        'timeload' => 'Tiempo de carga',
        'timebilling' => 'Tipo de facturación',
        'shiptoid' => 'Embarcar a',
        'barcode' => 'Código de barra',
        'folionumber' => 'Folio',
        'partnumberid' => 'No. de parte',
        'responsible' => 'Responsable',
        'categoryincid' => 'Categoria',
        'responsiblecorr' => 'Responsable de corrección',
        'operatorid' => 'Código de operador',
        'boxnumberid' => 'Código de Caja',
        'trucknumberid' => 'Código de Tracto',
        'econumbertruck' => 'Número de tracto',
        'type' => 'Tipo',
        'workdayin' => 'Fecha de inicio',
        'workdayout' => 'Fecha final',

    ],
];
