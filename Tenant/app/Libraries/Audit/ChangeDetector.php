<?php

namespace Tenant\app\Libraries\Audit;
class ChangeDetector
{
    protected array $ignored_fields = [
        'inventory' => [
            'title',
            'vehicle_description',
            'created_at',
            'updated_at',
            'modification_hash',
            'special_price_type',
            'special_price_date',
            'show_stripe',
            'phonetic_string',
            'kijiji_category',
            'autotrader_category',
            'facebook_category',
            'kijijinpv_category',
            'search_keyword',
            'odometer_numeric',
            'selling_price_numeric',
            'modification_hash',
            'days_in_stock',
            'custom_features',
            'technical_specification',
            'generic_equipment',
            'standard',
            'optional',
        ],
        'deal' => [
            'created_at',
            'updated_at',
        ],
        'common' => [
            'created_at',
            'updated_at',
        ]
    ];

    protected array $important_fields = [
        'inventory' => [
            'price' => 'inventory.price.updated',
            'special_price_on' => 'inventory.price.updated',
            'special_price' => 'inventory.price.updated',
            'odometer' => 'inventory.odometer.updated',
            'cost' => 'inventory.cost.updated',
            'status' => 'inventory.status.updated',
        ],

        'deal' => [
            'status' => 'deal.status.updated',
        ],
    ];

    public function detect(
        string $module,
        array  $before,
        array  $after
    ): array
    {
        $changes = [];
        $changed_fields = [];
        $change_types = [];
        $module = strtolower($module);

        foreach ($after as $field => $new_value) {

            if (isset($this->ignored_fields['common'])) {
                if (in_array($field, $this->ignored_fields['common'], true)) {
                    continue;
                }
            }

            if (isset($this->ignored_fields[$module])) {
                if (in_array($field, $this->ignored_fields[$module], true)) {
                    continue;
                }
            }

            if (!array_key_exists($field, $before)) {
                continue;
            }

            $old_value = $before[$field];

            if ($old_value == $new_value) {
                continue;
            }

            $changes[$field] = [
                'old' => $old_value,
                'new' => $new_value,
            ];

            $changed_fields[] = $field;

            if (isset($this->important_fields[$module][$field])) {
                $change_types[] =
                    $this->important_fields[$module][$field];
            }
        }

        return [
            'changes' => $changes,
            'changed_fields' => $changed_fields,
            'change_types' => array_values(
                array_unique($change_types)
            ),
        ];
    }
}