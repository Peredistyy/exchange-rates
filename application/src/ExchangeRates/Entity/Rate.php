<?php

namespace ExchangeRates\Entity;

/**
 * @Entity
 * @Table(name="rate")
 **/
class Rate
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;

    /**
     * @Column(type="float")
     **/
    protected $rate;

    /**
     * @ManyToOne(targetEntity="Currency", inversedBy="assignedRates")
     **/
    protected $currencyOf;

    /**
     * @ManyToOne(targetEntity="Currency", inversedBy="assignedRates")
     **/
    protected $currencyIn;

    public function getId()
    {
        return $this->id;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    public function getCurrencyOf()
    {
        return $this->currencyOf;
    }

    public function setCurrencyOf(Currency $currencyOf)
    {
        $this->currencyOf = $currencyOf;
    }

    public function getCurrencyIn()
    {
        return $this->currencyIn;
    }

    public function setCurrencyIn(Currency $currencyIn)
    {
        $this->currencyIn = $currencyIn;
    }
}
