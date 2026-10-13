<?php

namespace App\Models;

use CodeIgniter\Model;

abstract class SearchableModel extends Model
{
    protected $beforeInsert = ['createPhoneticString'];
    protected $beforeUpdate = ['createPhoneticString'];
    
    /**
     * searchableColumns This variable should be overriden for searching the columns.
     *
     * @var array
     */
    protected $searchableColumns = [];
    
    /**
     * createPhoneticString This method should be overriden to create a column for searching using Phonetic Search
     *
     * @param  mixed $data
     * @return void
     */
    public function createPhoneticString(array $data)
    {
        $metaphone = '';
        foreach ($this->searchableColumns as $key) {
            if (!empty($data['data'][$key])) {
                $append_phonetic_string = is_numeric($data['data'][$key]) ? $data['data'][$key] : metaphone($data['data'][$key]);
                $metaphone .= $append_phonetic_string;
                $metaphone .= ' ';
            }
        }

        $data['data']['phonetic_string'] = $metaphone;
        return $data;
    }
}
