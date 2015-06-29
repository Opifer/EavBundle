<?php

namespace Opifer\EavBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Opifer\EavBundle\Form\EventListener\ValuesSubscriber;

class ValuesType extends AbstractType
{
    protected $valuesSubscriber;

    /**
     * Constructor
     *
     * @param ValuesSubscriber $valueSubscriber
     */
    public function __construct(ValuesSubscriber $valuesSubscriber)
    {
        $this->valuesSubscriber = $valuesSubscriber;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this->valuesSubscriber);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'values_collection';
    }
}
