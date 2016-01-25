<?php
namespace CarnegieLearning\LdapOrmBundle\Entity\Ldap;


use Doctrine\ORM\Mapping as ORM;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Attribute;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ObjectClass;


/**
 * Class to represent a person within EDS ou=people,dc=CarnegieLearning,dc=edu
 *
 * @author jgabler
 * @author Ronald Chaplin <rchaplin@t73.biz>
 * @ObjectClass("organizationalUnit")
 */
class OrganizationalUnit extends LdapEntity {
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
     * @Attribute("destinationIndicator")
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
     * @Attribute("searchGuide")
     * @var string
     */
    protected $searchGuide;

    /**
     * @Attribute("seeAlso")
     * @var string
     */
    protected $seeAlso;
    
    /**
     * @Attribute("ou")
     * A MUST attribute
     * @var string
     */
    protected $ou;

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
     * @Attribute("telephoneNumber")
     * @var string
     */
    protected $telephoneNumber;

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
     * @Attribute("userPassword")
     * @var string
     */
    protected $userPassword;
    
    /**
     * @Attribute("x121Address")
     * @var string
     */
    protected $x121Address;

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
    function getSearchGuide() {
        return $this->searchGuide;
    }

    /**
     * @return string
     */
    function getSeeAlso() {
        return $this->seeAlso;
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
    function getTelephoneNumber() {
        return $this->telephoneNumber;
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
     * @return string
     */
    function getUserPassword() {
        return $this->userPassword;
    }

    /**
     * @return string
     */
    function getX121Address() {
        return $this->x121Address;
    }

    /**
     * @param $businessCategory
     * @return OrganizationalUnit
     */
    function setBusinessCategory($businessCategory) {
        $this->businessCategory = $businessCategory;

        return $this;
    }

    /**
     * @param $description
     * @return OrganizationalUnit
     */
    function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @param $destinationIndicator
     * @return OrganizationalUnit
     */
    function setDestinationIndicator($destinationIndicator) {
        $this->destinationIndicator = $destinationIndicator;

        return $this;
    }

    /**
     * @param $facsimileTelephoneNumber
     * @return OrganizationalUnit
     */
    function setFacsimileTelephoneNumber($facsimileTelephoneNumber) {
        $this->facsimileTelephoneNumber = $facsimileTelephoneNumber;

        return $this;
    }

    /**
     * @param $internationaliSDNNumber
     * @return OrganizationalUnit
     */
    function setInternationaliSDNNumber($internationaliSDNNumber) {
        $this->internationaliSDNNumber = $internationaliSDNNumber;

        return $this;
    }

    /**
     * @param $l
     * @return OrganizationalUnit
     */
    function setL($l) {
        $this->l = $l;

        return $this;
    }

    /**
     * @param $physicalDeliveryOfficeName
     * @return OrganizationalUnit
     */
    function setPhysicalDeliveryOfficeName($physicalDeliveryOfficeName) {
        $this->physicalDeliveryOfficeName = $physicalDeliveryOfficeName;

        return $this;
    }

    /**
     * @param $postalAddress
     * @return OrganizationalUnit
     */
    function setPostalAddress($postalAddress) {
        $this->postalAddress = $postalAddress;

        return $this;
    }

    /**
     * @param $postalCode
     * @return OrganizationalUnit
     */
    function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @param $postOfficeBox
     * @return OrganizationalUnit
     */
    function setPostOfficeBox($postOfficeBox) {
        $this->postOfficeBox = $postOfficeBox;

        return $this;
    }

    /**
     * @param $preferredDeliveryMethod
     * @return OrganizationalUnit
     */
    function setPreferredDeliveryMethod($preferredDeliveryMethod) {
        $this->preferredDeliveryMethod = $preferredDeliveryMethod;

        return $this;
    }

    /**
     * @param $registeredAddress
     * @return OrganizationalUnit
     */
    function setRegisteredAddress($registeredAddress) {
        $this->registeredAddress = $registeredAddress;

        return $this;
    }

    /**
     * @param $searchGuide
     * @return OrganizationalUnit
     */
    function setSearchGuide($searchGuide) {
        $this->searchGuide = $searchGuide;

        return $this;
    }

    /**
     * @param $seeAlso
     * @return OrganizationalUnit
     */
    function setSeeAlso($seeAlso) {
        $this->seeAlso = $seeAlso;

        return $this;
    }

    /**
     * @param $ou
     * @return OrganizationalUnit
     */
    function setOu($ou) {
        $this->ou = $ou;

        return $this;
    }

    /**
     * @param $st
     * @return OrganizationalUnit
     */
    function setSt($st) {
        $this->st = $st;

        return $this;
    }

    /**
     * @param $street
     * @return OrganizationalUnit
     */
    function setStreet($street) {
        $this->street = $street;

        return $this;
    }

    /**
     * @param $telephoneNumber
     * @return OrganizationalUnit
     */
    function setTelephoneNumber($telephoneNumber) {
        $this->telephoneNumber = $telephoneNumber;

        return $this;
    }

    /**
     * @param $teletexTerminalIdentifier
     * @return OrganizationalUnit
     */
    function setTeletexTerminalIdentifier($teletexTerminalIdentifier) {
        $this->teletexTerminalIdentifier = $teletexTerminalIdentifier;

        return $this;
    }

    /**
     * @param $telexNumber
     * @return OrganizationalUnit
     */
    function setTelexNumber($telexNumber) {
        $this->telexNumber = $telexNumber;

        return $this;
    }

    /**
     * @param $userPassword
     * @return OrganizationalUnit
     */
    function setUserPassword($userPassword) {
        $this->userPassword = $userPassword;

        return $this;
    }

    /**
     * @param $x121Address
     * @return OrganizationalUnit
     */
    function setX121Address($x121Address) {
        $this->x121Address = $x121Address;

        return $this;
    }
}