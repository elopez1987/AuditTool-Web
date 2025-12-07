<?php

use App\Models\UiLabel;

if (! function_exists('ui_label')) {
    function ui_label($fieldName, $default = null)
    {
        return UiLabel::where('field_name', $fieldName)
            ->value('label_text')
            ?? $default
            ?? ucfirst($fieldName);
    }
}

if (! function_exists('ui_visible')) {
    function ui_visible($fieldName, $defaultVisible = true)
    {
        $uiLabel = UiLabel::where('field_name', $fieldName)->first();

        if (! $uiLabel) {
            return $defaultVisible;
        }

        return (bool) $uiLabel->visibled;
    }
}

if (! function_exists('ui_required')) {
    function ui_required($fieldName, $defaultRequired = false)
    {
        $uiLabel = UiLabel::where('field_name', $fieldName)->first();

        if (! $uiLabel) {
            return $defaultRequired;
        }

        return (bool) $uiLabel->required;
    }
}

if (! function_exists('ui_type')) {
    function ui_type($fieldName, $defaultType = 'text')
    {
        $uiLabel = UiLabel::where('field_name', $fieldName)->first();

        if (! $uiLabel || empty($uiLabel->type)) {
            return $defaultType;
        }

        return $uiLabel->type;
    }
}

if (! function_exists('ui_attributes')) {
    function ui_attributes($fieldName, $additionalAttributes = [])
    {
        $attributes = $additionalAttributes;

        if (ui_required($fieldName)) {
            $attributes['required'] = true;
        }

        $type = ui_type($fieldName);
        if ($type) {
            $attributes['type'] = $type;
        }

        // Convertir array a string de atributos HTML
        $attrString = '';
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $attrString .= " {$key}";
            } elseif ($value !== false && $value !== null) {
                $attrString .= " {$key}=\"{$value}\"";
            }
        }

        return trim($attrString);
    }
}
