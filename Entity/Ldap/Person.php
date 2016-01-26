<?php
namespace CarnegieLearning\LdapOrmBundle\Entity\Ldap;

use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ArrayField;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Attribute;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ObjectClass;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Must;
use CarnegieLearning\LdapOrmBundle\Exception\UnknownUserPasswordTypeException;

/**
 * Standard LDAP Person entry
 * 
 * @author jgabler
 * @ObjectClass("Person")
 */
class Person extends LdapEntity
{
    
    /**
     * @Attribute("sn")
     * @Must()
     * @var string
     */
    protected $sn;

    /**
     * @Attribute("description")
     * @var string
     */
    protected $description;
    
    /**
     * @Attribute("seeAlso")
     * @var string
     */
    protected $seeAlso;

    /**
     * @Attribute("telephoneNumber")
     * @var string
     */
    protected $telephoneNumber;
    
    /**
     * @Attribute("userPassword")
     * @ArrayField()
     * @var array
     */
    protected $userPassword;

    /**
     * @codeCoverageIgnore
     * Person constructor.
     * @param null $username
     * @param null $roles
     */
    public function __construct($username = null, $roles = null)
    {
        parent::__construct();
        $this->roles = empty($roles) ? array() : $roles;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    function getSn() {
        return $this->sn;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    function getDescription() {
        return $this->description;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    function getSeeAlso() {
        return $this->seeAlso;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    function getTelephoneNumber() {
        return $this->telephoneNumber;
    }

    /**
     * @param null $type
     * @return array|string
     * @throws UnknownUserPasswordTypeException
     */
    function getUserPassword($type = null)
    {
        if (empty($this->userPassword) || is_scalar($this->userPassword) || $type == null) {
            return $this->userPassword;
        }

        foreach($this->userPassword as $password) {
            $needle = '{'.$type.'}';
            if (strpos($password, $needle) === 0) {
                return substr($password, strlen($needle));
            }
        }

        throw new UnknownUserPasswordTypeException('Unknown password type requested: "'.$type.'"');
    }

    /**
     * @codeCoverageIgnore
     * @param $sn
     * @return $this
     */
    function setSn($sn) {
        $this->sn = $sn;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @param $description
     * @return $this
     */
    function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @param $seeAlso
     * @return $this
     */
    function setSeeAlso($seeAlso) {
        $this->seeAlso = $seeAlso;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @param $telephoneNumber
     * @return $this
     */
    function setTelephoneNumber($telephoneNumber) {
        $this->telephoneNumber = $telephoneNumber;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @param null $userPassword
     * @return $this
     */
    function setUserPassword($userPassword = null) {
        if (empty($userPassword)) {
            unset($this->userPassword);
        } else {
            $this->userPassword = $userPassword;
        }

        return $this;
    }
}

