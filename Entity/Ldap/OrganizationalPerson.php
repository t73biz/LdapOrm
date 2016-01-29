<?php

namespace CarnegieLearning\LdapOrmBundle\Entity\Ldap;

use Doctrine\ORM\Mapping as ORM;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ArrayField;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Attribute;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ObjectClass;
use IAM\DirectoryServicesBundle\Util\Phone;

/**
 * Standard LDAP OrganizationalPerson. May be used as a Symfony user.
 * 
 * @author jgabler
 * @ObjectClass("OrganizationalPerson")
 * @codeCoverageIgnore
 */
class OrganizationalPerson extends Person
{
    
    /**
     * @Attribute("description")
     * @var string
     */
    protected $description;
    
    /**
     * @Attribute("description")
     * @var string
     */
    protected $destinationIndicator;   
    
    /**
     * @Attribute("facsimileTelephoneNumber")
     * @var string
     */
    protected $facsimileTelephoneNumber;    
    
   /**
    * @Attribute("internationaliSDNNumber")
    * @var string
    */
    protected $internationaliSDNNumber;

    /**
     * @Attribute("l")
     * @var string
     */
    protected $l;
    
    /**
     * @Attribute("ou")
     * A MUST attribute
     * @var string
     */
    protected $ou;
    
    /**
     * @Attribute("physicalDeliveryOfficeName")
     * @var string
     */
    protected $physicalDeliveryOfficeName;
    
    /**
     * @Attribute("postalAddress")
     * @var string
     */
    protected $postalAddress;
    
    /**
     * @Attribute("postalCode")
     * @var string
     */
    protected $postalCode;
    
    /**
     * @Attribute("postOfficeBox")
     * @var string
     */
    protected $postOfficeBox;

    /**
     * @Attribute("preferredDeliveryMethod")
     * @var string
     */
    protected $preferredDeliveryMethod;
    
    /**
     * @Attribute("registeredAddress")
     * @var string
     */
    protected $registeredAddress;

    /**
     * @Attribute("st")
     * @var string
     */
    protected $st;
    
    /**
     * @Attribute("street")
     * @var string
     */
    protected $street;

    /**
     * @Attribute("teletexTerminalIdentifier")
     * @var string
     */
    protected $teletexTerminalIdentifier;

    /**
     * @Attribute("telexNumber")
     * @var string
     */
    protected $telexNumber;
    
    /**
     * @Attribute("title")
     * @ArrayField()
     * @var array
     */
    protected $title;
    
    /**
     * @Attribute("x121Address")
     * @var string
     */
    protected $x121Address;

    /**
     * OrganizationalPerson constructor.
     * @param null $username
     * @param null $roles
     */
    public function __construct($username = null, $roles = null)
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    function getDescription() {
        return $this->description;
    }

    /**
     * @return string
     */
    function getDestinationIndicator() {
        return $this->destinationIndicator;
    }

    /**
     * @return string
     */
    function getFacsimileTelephoneNumber() {
        return $this->facsimileTelephoneNumber;
    }

    /**
     * @return string
     */
    function getInternationaliSDNNumber() {
        return $this->internationaliSDNNumber;
    }

    /**
     * @return string
     */
    function getL() {
        return $this->l;
    }

    /**
     * @return string
     */
    function getOu() {
        return $this->ou;
    }

    /**
     * @return string
     */
    function getPhysicalDeliveryOfficeName() {
        return $this->physicalDeliveryOfficeName;
    }

    /**
     * @return string
     */
    function getPostalAddress() {
        return $this->postalAddress;
    }

    /**
     * @return string
     */
    function getPostalCode() {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    function getPostOfficeBox() {
        return $this->postOfficeBox;
    }

    /**
     * @return string
     */
    function getPreferredDeliveryMethod() {
        return $this->preferredDeliveryMethod;
    }

    /**
     * @return string
     */
    function getRegisteredAddress() {
        return $this->registeredAddress;
    }

    /**
     * @return string
     */
    function getSt() {
        return $this->st;
    }

    /**
     * @return string
     */
    function getStreet() {
        return $this->street;
    }

    /**
     * @return string
     */
    function getTeletexTerminalIdentifier() {
        return $this->teletexTerminalIdentifier;
    }

    /**
     * @return string
     */
    function getTelexNumber() {
        return $this->telexNumber;
    }

    /**
     * @return array
     */
    function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    function getX121Address() {
        return $this->x121Address;
    }

    /**
     * @param $description
     * @return $this
     */
    function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @param $destinationIndicator
     * @return $this
     */
    function setDestinationIndicator($destinationIndicator) {
        $this->destinationIndicator = $destinationIndicator;

        return $this;
    }

    /**
     * @param $facsimileTelephoneNumber
     * @return $this
     */
    function setFacsimileTelephoneNumber($facsimileTelephoneNumber) {
        $this->facsimileTelephoneNumber = $facsimileTelephoneNumber;

        return $this;
    }

    /**
     * @param $internationaliSDNNumber
     * @return $this
     */
    function setInternationaliSDNNumber($internationaliSDNNumber) {
        $this->internationaliSDNNumber = $internationaliSDNNumber;

        return $this;
    }

    /**
     * @param $l
     * @return $this
     */
    function setL($l) {
        $this->l = $l;

        return $this;
    }

    /**
     * @param $ou
     * @return $this
     */
    function setOu($ou) {
        $this->ou = $ou;

        return $this;
    }

    /**
     * @param $physicalDeliveryOfficeName
     * @return $this
     */
    function setPhysicalDeliveryOfficeName($physicalDeliveryOfficeName) {
        $this->physicalDeliveryOfficeName = $physicalDeliveryOfficeName;

        return $this;
    }

    /**
     * @param $postalAddress
     * @return $this
     */
    function setPostalAddress($postalAddress) {
        $this->postalAddress = $postalAddress;

        return $this;
    }

    /**
     * @param $postalCode
     * @return $this
     */
    function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @param $postOfficeBox
     * @return $this
     */
    function setPostOfficeBox($postOfficeBox) {
        $this->postOfficeBox = $postOfficeBox;

        return $this;
    }

    /**
     * @param $preferredDeliveryMethod
     * @return $this
     */
    function setPreferredDeliveryMethod($preferredDeliveryMethod) {
        $this->preferredDeliveryMethod = $preferredDeliveryMethod;

        return $this;
    }

    /**
     * @param $registeredAddress
     * @return $this
     */
    function setRegisteredAddress($registeredAddress) {
        $this->registeredAddress = $registeredAddress;

        return $this;
    }

    /**
     * @param $st
     * @return $this
     */
    function setSt($st) {
        $this->st = $st;

        return $this;
    }

    /**
     * @param $street
     * @return $this
     */
    function setStreet($street) {
        $this->street = $street;

        return $this;
    }

    /**
     * @param $teletexTerminalIdentifier
     * @return $this
     */
    function setTeletexTerminalIdentifier($teletexTerminalIdentifier) {
        $this->teletexTerminalIdentifier = $teletexTerminalIdentifier;

        return $this;
    }

    /**
     * @param $telexNumber
     * @return $this
     */
    function setTelexNumber($telexNumber) {
        $this->telexNumber = $telexNumber;

        return $this;
    }

    /**
     * @param $title
     * @return $this
     */
    function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * @param $x121Address
     * @return $this
     */
    function setX121Address($x121Address) {
        $this->x121Address = $x121Address;

        return $this;
    }
}
