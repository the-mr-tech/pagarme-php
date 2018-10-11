<?php

namespace PagarMe\Sdk\Customer;

class Item
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $unitPrice;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var boolean
     */
    private $tangible;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $venue;

    /**
     * @var string
     */
    private $date;

    /**
     * @param array $arrayData
     */
    public function __construct($arrayData)
    {
        $this->fill($arrayData);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return bool
     */
    public function isTangible()
    {
        return $this->tangible;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }



}
