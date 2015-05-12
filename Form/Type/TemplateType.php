<?php

namespace Opifer\EavBundle\Form\Type;

use Opifer\EavBundle\Model\TemplateInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class TemplateType extends AbstractType
{

    /**
     *
     * @var array 
     */
    private $entities;

    /**
     * @var  Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    protected $translator;

    /**
     * @var AttributeType
     */
    protected $attributeType;

    /**
     * @var
     */
    protected $templateClass;


    /**
     * Constructor
     *
     * @param TranslatorInterface $translator
     * @param AttributeType       $attributeType
     * @param string              $templateClass
     * @param array               $entities
     */
    public function __construct(TranslatorInterface $translator, AttributeType $attributeType, $templateClass, $entities)
    {
        $this->translator    = $translator;
        $this->attributeType = $attributeType;
        $this->templateClass = $templateClass;
        $this->entities = $entities;
    }


    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('displayName', 'text', [
            'label' => $this->translator->trans('template.display_name'),
            'attr'  => [
                'class'                  => 'slugify',
                'data-slugify-target'    => '.slugify-target',
                'data-slugify-separator' => '_',
                'placeholder'            => $this->translator->trans('form.display_name.placeholder'),
                'help_text'              => $this->translator->trans('form.display_name.help_text')
            ]
        ])->add('name', 'text', [
            'label' => $this->translator->trans('template.name'),
            'attr'  => [
                'class'       => 'slugify-target',
                'placeholder' => $this->translator->trans('form.name.placeholder'),
                'help_text'   => $this->translator->trans('form.name.help_text')
            ]
        ])->add('object_class', 'template_object_class', [
            'label' => $this->translator->trans('template.object_class'),
            'attr'  => [ 'help_text' => $this->translator->trans('form.object_class.help_text') ]
        ])->add('presentation', 'presentationeditor', [
            'label' => $this->translator->trans('template.presentation'),
            'attr'  => [ 'help_text' => $this->translator->trans('form.presentation.help_text') ]
        ]);
        
        if($this->entities['post'] == $builder->getData()->getObjectClass()) {
            $builder->add('postNotify', 'email', [
                'label' => $this->translator->trans('template.post_notify'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('form.post_notify.placeholder'),
                    'help_text' => $this->translator->trans('form.post_notify.help_text')
                ]
            ]);
        }
        
        $builder->add('attributes', 'bootstrap_collection', [
            'allow_add'    => true,
            'allow_delete' => true,
            'type'         => $this->attributeType
        ])->add('save', 'submit', [
            'label' => ucfirst($this->translator->trans('form.submit'))
        ]);
    }


    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => $this->templateClass,
            'validation_groups' => false,
        ));
    }


    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'eav_template';
    }
}