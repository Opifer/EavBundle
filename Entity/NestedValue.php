<?php

namespace Opifer\EavBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Opifer\EavBundle\Model\Nestable;

/**
 * Nested Value
 *
 * @ORM\Entity
 */
class NestedValue extends Value implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var <Nestable>
     *
     * @ORM\OneToMany(targetEntity="Opifer\EavBundle\Model\Nestable", mappedBy="nestedIn")
     * @ORM\OrderBy({"nestedSort" = "ASC"})
     */
    protected $nested;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->nested = new ArrayCollection();
        $this->allowedTemplates = new ArrayCollection();
    }

    /**
     * Turn value into string for form field value purposes
     *
     * @return string
     */
    public function __toString()
    {
        $ids = [];
        foreach ($this->nested as $nested) {
            if ($nested->getDeletedAt()) {
                continue;
            }

            array_push($ids, $nested->getId());
        }

        return (count($ids)) ? implode(',', $ids) : '';
    }

    /**
     * Get the value
     *
     * Overrides the parent getValue method
     *
     * @return ArrayCollection
     */
    public function getValue()
    {
        return $this->nested;
    }

    /**
     * Add nested
     *
     * @param \Opifer\EavBundle\Model\Nestable $nested
     *
     * @return Value
     */
    public function addNested(Nestable $nested)
    {
        $this->nested[] = $nested;

        return $this;
    }

    /**
     * Remove nested
     *
     * @param \Opifer\EavBundle\Model\Nestable $nested
     */
    public function removeNested(Nestable $nested)
    {
        $this->nested->removeElement($nested);
    }

    /**
     * Set nested
     *
     * @param ArrayCollection $nested
     */
    public function setNested(ArrayCollection $nested)
    {
        $this->nested = $nested;

        return $this;
    }

    /**
     * Get nested
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNested()
    {
        return $this->nested;
    }

    /**
     * Makes it possible to loop over this entity.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $values = explode(',', trim($this->value));

        return $this->getNested();
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return (count($this->nested) < 1) ? true : false;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->nested[] = $value;
        } else {
            $this->nested[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->nested[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->nested[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return isset($this->nested[$offset]) ? $this->nested[$offset] : null;
    }
}
