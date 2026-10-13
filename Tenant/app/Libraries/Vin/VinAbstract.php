<?php

namespace Tenant\app\Libraries\Vin;

use Tenant\app\Interfaces\VinInterface;

abstract class VinAbstract implements VinInterface
{

    /**
     * url Saves Url of the request
     *
     * @var mixed
     */
    protected $url;
    /**
     * response This is the response of the request
     *
     * @var mixed
     */
    protected $response;

    /**
     * vin Vin whose data has be processed.
     *
     * @var mixed
     */
    protected $vin;

    /**
     * formatted_response This is the formatted response that will be sent 
     * for the request after formalizing.
     *
     * @var mixed
     */
    protected $formatted_response = [];

    /**
     * default_value    This will be the value set as default for the client.
     *
     * @var int
     */
    protected $default_value = 1;

    public function setDefaultValue(int $value)
    {
        $this->default_value = $value;
        return $this;
    }
}
