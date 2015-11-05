<?php

namespace Opifer\EavBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Opifer\EavBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class AttachmentValue extends Value
{
    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * @var MediaInterface
     *
     * @ORM\OneToOne(targetEntity="Opifer\EavBundle\Model\MediaInterface", cascade={"persist"})
     */
    protected $attachment;

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return MediaInterface
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param MediaInterface $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        $this->getAttachment();
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        $this->setAttachment($value);
    }

    public function __toString()
    {
        return ($this->getValue()) ? 'true' : 'false';
    }
}
