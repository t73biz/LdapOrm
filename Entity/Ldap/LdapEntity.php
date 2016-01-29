<?php

namespace CarnegieLearning\LdapOrmBundle\Entity\Ldap;

use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ArrayField;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Attribute;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Must;

class LdapEntity
{

    /**
     * @Attribute("objectClass")
     * @Must()
     * @ArrayField()
     * @var string
     */
    protected $objectClass;

    /**
     * @Attribute("cn")
     * @Must()
     * @var string
     */
    protected $cn;

    /**
     * @Attribute("dn")
     * @var string
     */
    protected $dn;


    /**
     * LdapEntity constructor.
     */
    public function __construct() {
        $this->setObjectClass(lcfirst((new \ReflectionClass(get_class($this)))->getShortName()));
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getObjectClass() {
        return $this->objectClass;
    }

    /**
     * @codeCoverageIgnore
     * @param $objectClasses
     * @return LdapEntity
     */
    public function setObjectClass($objectClasses) {
        $this->objectClass = $objectClasses;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    function getCn() {
        return $this->cn;
    }

    /**
     * @codeCoverageIgnore
     * @param $cn
     * @return LdapEntity
     */
    function setCn($cn) {
        $this->cn = $cn;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getDn()
    {
        return $this->dn;
    }

    /**
     * @codeCoverageIgnore
     * @param $dn
     * @return LdapEntity
     */
    public function setDn($dn)
    {
        $this->dn = $dn;

        return $this;
    }
}
