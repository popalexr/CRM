<?php

namespace App\Traits;

trait HasFormLabels
{
    /**
     * Get form labels with optional config overrides.
     * 
     * @param string $resourceName The name of the language resource (e.g., 'users', 'clients')
     * @param string|null $configKey Optional config key for additional labels
     * @param array $additionalLabels Optional additional labels to merge
     * @return array
     */
    protected function getFormLabels(string $resourceName, ?string $configKey = null, array $additionalLabels = []): array
    {
        $baseLabels = [
            'labels' => __("{$resourceName}.labels"),
            'placeholders' => __("{$resourceName}.placeholders"),
            'buttons' => __("{$resourceName}.buttons"),
            'messages' => __("{$resourceName}.messages"),
        ];

        if (__("{$resourceName}.tabs") !== "{$resourceName}.tabs") {
            $baseLabels['tabs'] = __("{$resourceName}.tabs");
        }

        if (__("{$resourceName}.headings") !== "{$resourceName}.headings") {
            $baseLabels['headings'] = __("{$resourceName}.headings");
        }

        if (__("{$resourceName}.client_types") !== "{$resourceName}.client_types") {
            $baseLabels['client_types'] = __("{$resourceName}.client_types");
        }

        if (__("{$resourceName}.status") !== "{$resourceName}.status") {
            $baseLabels['status'] = __("{$resourceName}.status");
        }

        if ($configKey && config($configKey)) {
            $configLabels = config($configKey);
            
            if (isset($configLabels['labels'])) {
                $baseLabels['labels'] = array_merge($baseLabels['labels'], $configLabels['labels']);
            }
            
            if (isset($configLabels['placeholders'])) {
                $baseLabels['placeholders'] = array_merge($baseLabels['placeholders'], $configLabels['placeholders']);
            }
            
            if (isset($configLabels['messages'])) {
                $baseLabels['messages'] = array_merge($baseLabels['messages'], $configLabels['messages']);
            }

            foreach ($configLabels as $key => $value) {
                if (!in_array($key, ['labels', 'placeholders', 'messages'])) {
                    $baseLabels[$key] = $value;
                }
            }
        }

        foreach ($additionalLabels as $key => $value) {
            if (isset($baseLabels[$key]) && is_array($baseLabels[$key]) && is_array($value)) {
                $baseLabels[$key] = array_merge($baseLabels[$key], $value);
            } else {
                $baseLabels[$key] = $value;
            }
        }

        return $baseLabels;
    }

    /**
     * Get form data configuration including labels and config.
     * 
     * @param string $resourceName The name of the language resource
     * @param string|null $configKey Optional config key for form configuration
     * @param string|null $labelConfigKey Optional config key for additional labels
     * @param array $additionalLabels Optional additional labels to merge
     * @return array
     */
    protected function getFormData(string $resourceName, ?string $configKey = null, ?string $labelConfigKey = null, array $additionalLabels = []): array
    {
        $formData = $this->getFormLabels($resourceName, $labelConfigKey, $additionalLabels);
        
        if ($configKey && config($configKey)) {
            $formData['config'] = config($configKey);
        }

        return $formData;
    }

    /**
     * Map form data to database fields using configuration.
     * 
     * @param array $validatedData The validated form data
     * @param string $operation The operation type ('create' or 'update')
     * @param string $configKey The config key for field mappings
     * @param array $specialHandlers Custom handlers for special fields
     * @return array
     */
    protected function mapFormDataToDatabase(array $validatedData, string $operation, string $configKey, array $specialHandlers = []): array
    {
        $fieldMappings = config("{$configKey}.field_mappings.{$operation}", []);
        $mappedData = [];

        foreach ($fieldMappings as $formField => $dbField) {
            if (isset($specialHandlers[$formField])) {
                // Handle special fields with custom logic
                $mappedData[$dbField] = $specialHandlers[$formField]($validatedData[$formField] ?? null);
            } elseif (array_key_exists($formField, $validatedData)) {
                // Standard field mapping
                $mappedData[$dbField] = $validatedData[$formField];
            }
        }

        return $mappedData;
    }
}
