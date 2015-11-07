<?php

namespace Opifer\EavBundle\ValueProvider;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;

class AttachmentValueProvider extends AbstractValueProvider implements ValueProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', 'file', [
            'label' => $options['attribute']->getDisplayName(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntity()
    {
        return 'Opifer\EavBundle\Entity\AttachmentValue';
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return 'Attachment';
    }
}
