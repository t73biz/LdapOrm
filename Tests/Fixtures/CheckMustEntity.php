<?php
namespace CarnegieLearning\LdapOrmBundle\Tests\Fixtures;

use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Attribute;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Must;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ObjectClass;
use CarnegieLearning\LdapOrmBundle\Entity\Ldap\LdapEntity;

/**
 * Standard LDAP Person entry
 *
 * @author Ronald Chaplin <rchaplin@t73.biz>
 *
 * @ObjectClass("CheckMustEntity")
 */
class CheckMustEntity extends LdapEntity
{
    /**
     * @Attribute("name")
     * @Must
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CheckMustEntity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

}