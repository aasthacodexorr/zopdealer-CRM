<?php

namespace App\Validations;

class Rules extends \CodeIgniter\Validation\Rules
{
    /**
     * Checks if the key is unique in a subset or not. 
     * This is helpful for validation when using the foreign key
     *
     * Example:
     *    is_unique_in_subset[table.field,ignore_fk_field,ignore_pk_field,ignore_fk_value,ignore_pk_value]
     *    is_unique_in_subset[payments.status,order_id,payment_id,5,1]
     *
     * @param string $str
     * @param string $field
     * @param array  $data
     *
     * @return boolean
     */
    public function is_unique_in_subset(string $str = null, string $field, array $data): bool
    {
        // Grab any data for exclusion of a single row.
        list($field, $filterFKField, $ignorePrimaryField, $filterFKValue, $ignorePrimaryValue) = array_pad(explode(',', $field), 5, null);

        // Break the table and field apart
        sscanf($field, '%[^.].%[^.]', $table, $field);

        $db = \Config\Database::connect();

        $row = $db->table($table)
            ->select('1')
            ->where($field, $str)
            ->limit(1);

        if (!empty($filterFKField) && !empty($filterFKValue)) {
            $row = $row->where("{$filterFKField} =", $filterFKValue);
        }

        //Update has Primary Key so it needs to be filtered out
        if (!empty($ignorePrimaryField) && !empty($ignorePrimaryValue)) {
            $row = $row->where("{$ignorePrimaryField} !=", $ignorePrimaryValue);
        }

        return (bool) ($row->get()
            ->getRow() === null);
    }

    /**
     * The field is required when any of the other required fields are present
     * in the data.
     *
     * Example (field is required when the enable_flag field is present):
     *
     *     required_with_a_toggle[enable_flag]
     *
     * @param $str
     * @param string $fields List of fields that we should check if present
     * @param array  $data   Complete list of fields from the form
     *
     * @return boolean
     */
    public function required_with_a_toggle($str = null, string $fields, array $data, &$error = null): bool
    {
        $fields = explode(',', $fields);

        // If the field is present we can safely assume that
        // the field is here, no matter whether the corresponding
        // search field is present or not.
        $present = $this->required($str ?? '');

        if ($present) {
            return true;
        }

        // Still here? Then we fail this test if
        // any of the fields are present in $data
        // as $fields is the lis
        $requiredFields = [];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $requiredFields[] = $field;
            }
        }

        // Remove any keys with empty values since, that means they
        // weren't truly there, as far as this is concerned.
        $requiredFields = array_filter($requiredFields, function ($item) use ($data) {
            return intval($data[$item]) === 1;
        });

        $valid = empty($requiredFields);

        if (!$valid) {
            $error = 'This is required.';
        }

        return empty($requiredFields);
    }
    
    /**
     * json_required_without
     *
     * @param  mixed $str
     * @param  mixed $fields
     * @param  mixed $data
     * @return bool
     */
    public function json_required_without($str = null, string $fields, array $data, &$error): bool
    {
        $fields = explode(',', $fields);

        $json = json_decode($str, true);

        //Pass if atleast one value is already present
        if (!empty($json)) {
            if (count($json) > 0) {
                return true;
            }
        }

        // Still here? Then we fail this test if
        // any of the fields are not present in $data
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data)) {
                return false;
            }
        }

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $compared_json_filed = json_decode($data[$field], true);
                if (empty($compared_json_filed)) {
                    $error = 'This field is required without ' . str_replace('_', ' ', $field) . '.';
                    return false;
                } else {
                    if (count($compared_json_filed) === 0) {
                        $error = 'This field is required without ' . str_replace('_', ' ', $field) . '.';
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
