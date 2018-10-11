<?php

namespace PagarMe\Sdk\Customer;

class Billing
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var PagarMe\Sdk\Customer\Address
     */
    private $address;

    /**
     * @var string
     */
    private $name;

    /**
     * @param array $arrayData
     */
    public function __construct($arrayData)
    {
        $this->fill($arrayData);
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

}
