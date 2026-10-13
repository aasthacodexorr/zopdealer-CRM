<?php

namespace Tenant\app\Libraries\IndexService;

use Rs\JsonLines\JsonLines;

/**
 * Typesense This class handles formatting of data as well as calling Typsense API's.
 */
class Typesense
{

    /**
     * api This is the base URL of the API.
     *
     * @var string
     */
    private $api = '';

    /**
     * token This is the token that will be used for authentication.
     *
     * @var mixed
     */
    private $token;

    /**
     * intColumns This property contains columns which should be formatted into Int
     *
     * @var array
     */
    private $intColumns = [];

    /**
     * arrayColumns This property contains columns which should be formatted into array
     *
     * @var array
     */
    private $arrayColumns = [];

    /**
     * dateColumns This property contains columns which should be formatted into date.
     *
     * @var array
     */
    private $dateColumns = [];

    /**
     * stringColumns This property contains columns which should be always a string
     *
     * @var array
     */
    private $stringColumns = [];

    /**
     * collection
     *
     * @var string
     */
    private $collection = '';

    public function __construct()
    {
        $this->token = getenv('typesense.token');
        $this->api = getenv('typesense.api');
        $this->collection = 'inventory';
    }


    /**
     * formatSingleEntity
     *
     * @param  array $data
     * @return array
     */
    public function formatSingleEntity(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->intColumns)) {
                $data[$key] = $this->convertJsonStringToInteger($value);
            } elseif (in_array($key, $this->dateColumns)) {
                $default_value = $key === 'deleted_at' ? 0 : strtotime("now");
                $data[$key] = $this->convertJsonDateToInt($value,  $default_value);
            } elseif (in_array($key, $this->arrayColumns)) {
                $data[$key] = $this->convertJsonStringToArray($value);
            } elseif (in_array($key, $this->stringColumns)) {
                $data[$key] = $this->convertJsonDatatoString($value);
            }
        }
        return $data;
    }

    /**
     * bulkUpsert
     *
     * @param  array $data
     * @return void
     */
    public function bulkUpsert(array $data): void
    {
        if (empty($this->collection)) throw new \Exception("Collection Missing. Set Collection for request.", 1);

        if (empty($this->api)) throw new \Exception("Base API missing.", 1);

        if (empty($this->token)) throw new \Exception("Token missing.", 1);

        if (empty($data)) throw new \Exception("Data is missing.", 1);

        $jsonl = (new JsonLines())->enline($data);
        $url = $this->api . "/collections/" . $this->collection . "/documents/import?action=upsert&dirty_values=coerce_or_drop";
        $ch = curl_init($url);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: text/plain', // for define content type that is json
                'X-TYPESENSE-API-KEY: ' . $this->token, // send token in header request
            )
        );

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonl);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * purge This method purges all the documents in the collection.
     *
     * @return void
     */
    public function purge()
    {
        $url = $this->api . "/collections/" . $this->collection . "/documents?filter_by=inventory_id:>=0";
        $ch = curl_init($url);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: text/plain', // for define content type that is json
                'X-TYPESENSE-API-KEY: ' . $this->token, // send token in header request
            )
        );

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * create
     *
     * @param  mixed $collection_data
     * @return void
     */
    public function create($collection_data)
    {

        $url = $this->api . "/collections";
        $ch = curl_init($url);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: text/plain', // for define content type that is json
                'X-TYPESENSE-API-KEY: ' . $this->token, // send token in header request
            )
        );

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $collection_data);
        curl_exec($ch);
        curl_close($ch);
    }


    /**
     * delete This deletes the index of the client.
     *
     * @return void
     */
    public function delete()
    {
        $url = $this->api . "/collections/" . $this->collection;
        $ch = curl_init($url);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: text/plain', // for define content type that is json
                'X-TYPESENSE-API-KEY: ' . $this->token, // send token in header request
            )
        );

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * convertJsonStringToInteger
     *
     * @param  mixed $value
     * @return int
     */
    private function convertJsonStringToInteger($value): int
    {
        return is_numeric($value) ? intval($value) : 0;
    }

    /**
     * convertJsonStringToArray
     *
     * @param mixed $value
     * @return array
     */
    private function convertJsonStringToArray($value): array
    {
        $convertToJsonArray = json_decode($value);
        return is_array($convertToJsonArray) ? $convertToJsonArray : [];
    }


    /**
     * convertJsonDateToInt
     *
     * @param  mixed $value
     * @param  mixed $default_timevalue
     * @return int
     */
    private function convertJsonDateToInt($value, $default_timevalue = 0): int
    {
        return strtotime($value) ? strtotime($value) : $default_timevalue;
    }

    private function convertJsonDatatoString($value): string
    {
        if (empty($value)) {
            return "";
        }
        
        return is_string($value) ? $value : "";
    }


    /**
     * Set the value of intColumns
     *
     * @return  self
     */
    public function setIntColumns($intColumns)
    {
        $this->intColumns = $intColumns;

        return $this;
    }

    /**
     * Set arrayColumns This property contains columns which should be formatted into array
     *
     * @param  array  $arrayColumns  arrayColumns This property contains columns which should be formatted into array
     *
     * @return  self
     */
    public function setArrayColumns(array $arrayColumns)
    {
        $this->arrayColumns = $arrayColumns;

        return $this;
    }

    /**
     * Set dateColumns This property contains columns which should be formatted into date.
     *
     * @param  array  $dateColumns  dateColumns This property contains columns which should be formatted into date.
     *
     * @return  self
     */
    public function setDateColumns(array $dateColumns)
    {
        $this->dateColumns = $dateColumns;

        return $this;
    }

    /**
     * Set collection
     *
     * @param  string  $collection  collection
     *
     * @return  self
     */
    public function setCollection(string $collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Set stringColumns This property contains columns which should be always a string
     *
     * @param  array  $stringColumns  stringColumns This property contains columns which should be always a string
     *
     * @return  self
     */
    public function setStringColumns(array $stringColumns)
    {
        $this->stringColumns = $stringColumns;

        return $this;
    }
}
