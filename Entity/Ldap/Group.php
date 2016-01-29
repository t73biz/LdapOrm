<?php

namespace CarnegieLearning\LdapOrmBundle\Entity\Ldap;

use Doctrine\ORM\Mapping as ORM;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ArrayField;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Attribute;

/**
 * A superclass for GroupOfEntries and GroupOfNames
 * @codeCoverageIgnore
 * @author jgabler
 * @codeCoverageIgnore
 */
class Group extends LdapEntity {

    /**
     * @Attribute("businessCategory")
     * @var string
     */
    protected $businessCategory; 

    /**
     * @Attribute("description")
     * @var string
     */
    protected $description;
    
    /**
     * @Attribute("member")
     * @ArrayField()
     * @var array
     */
    protected $member; 

    /**
     * @Attribute("o")
     * @var string
     */
    protected $o; 
    
    /**
     * @Attribute("ou")
     * @var string
     */
    protected $ou;     
    
    /**
     * @Attribute("owner")
     * @ArrayField()
     * @var array
     */
    protected $owner; 
    
    /**
     * @Attribute("seeAlso")
     * @var string
     */
    protected $seeAlso;

    /**
     * @return string
     */
    function getBusinessCategory() {
        return $this->businessCategory;
    }

    /**
     * @return string
     */
    function getDescription() {
        return $this->description;
    }

    /**
     * @return array
     */
    function getMember() {
        return $this->member;
    }

    /**
     * @return string
     */
    function getO() {
        return $this->o;
    }

    /**
     * @return string
     */
    function getOu() {
        return $this->ou;
    }

    /**
     * @return array
     */
    function getOwner() {
        return $this->owner;
    }

    /**
     * @return string
     */
    function getSeeAlso() {
        return $this->seeAlso;
    }

    /**
     * @param $businessCategory
     * @return Group
     */
    function setBusinessCategory($businessCategory) {
        $this->businessCategory = $businessCategory;

        return $this;
    }

    /**
     * @param $description
     * @return Group
     */
    function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @param $member
     * @return Group
     */
    function setMember($member) {
        $this->member = $member;

        return $this;
    }

    /**
     * @param $o
     * @return Group
     */
    function setO($o) {
        $this->o = $o;

        return $this;
    }

    /**
     * @param $ou
     * @return Group
     */
    function setOu($ou) {
        $this->ou = $ou;

        return $this;
    }

    /**
     * @param $owner
     * @return Group
     */
    function setOwner($owner) {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @param $seeAlso
     * @return Group
     */
    function setSeeAlso($seeAlso) {
        $this->seeAlso = $seeAlso;

        return $this;
    }
}
