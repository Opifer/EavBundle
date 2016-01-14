<?php

namespace Opifer\EavBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DateValue
 *
 * @ORM\Entity
 */
class DateValue extends Value
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $now = new \DateTime();
        $this->setValue($now);
    }

    /**
    * Turn value into string for form field value purposes
    *
    * @return string
    */
    public function __toString()
    {
        return date('d-m-Y', $this->getTimestamp());
    }

    /**
     * Get value
     *
     * @return \DateTime
     */
    public function getValue()
    {
        $datetime = new \DateTime();

        return $datetime->setTimestamp($this->value);
    }

    /**
     * Set value
     *
     * @param \DateTime $value
     *
     * @return DateValue
     */
    public function setValue($value)
    {
        if ($value instanceof \DateTime) {
            $this->value = $value->getTimestamp();
        } else {
            $this->value = $value;
        }

        return $this;
    }

    /**
     * Get raw value
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->value;
    }
}
