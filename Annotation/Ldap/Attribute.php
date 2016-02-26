<?php
namespace CarnegieLearning\LdapOrmBundle\Annotation\Ldap;

/**
 * Annotation to describe an Ldap objectClass
 * 
 * @Annotation
 * @author Mathieu GOULIN <mathieu.goulin@gadz.org>
 */
final class Attribute extends BaseAnnotation
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $isOperational = false;

    /**
     * Build the Attribute object
     * 
     * @param array $data
     * 
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        // Treatment of annotation data
        if (isset($data['value'])) {
            $this->name = $data['value'];
            unset($data['value']);
        }

        parent::__construct($data);
    }

    /**
     * Return the name of the Attribute
     * 
     *  @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the Attribute's name
     *
     * @param string $name
     *
     * @return Attribute
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $isOperational
     *
     * @return Attribute
     */
    public function setIsOperational($isOperational)
    {
        $this->isOperational = $isOperational;

        return $this;
    }

    public function getIsOperational()
    {
        return $this->isOperational;
    }
}
