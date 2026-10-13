<?php

namespace App\Models;

use CodeIgniter\Model;

abstract class ExactSearchableModel extends Model
{
    protected $beforeInsert = ['createKeyWordString'];
    protected $beforeUpdate = ['createKeyWordString'];
    
    /**
     * searchableColumns This variable should be overriden for searching the columns.
     *
     * @var array
     */
    protected $searchableColumns = [];
    
    /**
     * createKeyWordString This method should be overriden to create a column for searching using Exact Search
     *
     * @param  mixed $data
     * @return
     */
    public function createKeyWordString(array $data)
    {
        $keyword_digest = '';
        foreach ($this->searchableColumns as $key) {
            if (!empty($data['data'][$key])) {
                $append_phonetic_string = is_numeric($data['data'][$key]) ? $data['data'][$key] : strtolower($data['data'][$key]);
                $keyword_digest .= $append_phonetic_string;
                $keyword_digest .= ' ';
            }
        }

        $data['data']['search_keyword'] = $keyword_digest;
        return $data;
    }
}
