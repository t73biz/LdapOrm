<?php

namespace CarnegieLearning\LdapOrmBundle\Entity\Ldap;

use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ArrayField;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Attribute;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ObjectClass;

/**
 * Standard LDAP InetOrgPerson. May be used as a Symfony user.
 *
 * @codeCoverageIgnore
 *
 * @author jgabler
 *
 * @ObjectClass("OrganizationalPerson")
 */
class InetOrgPerson extends OrganizationalPerson
{
    /**
     * @Attribute("audio")
     * @var string
     */
    protected $audio;

    /**
     * @Attribute("businessCategory")
     * @var string
     */
    protected $businessCategory; 

    /**
     * @Attribute("carLicense")
     * @var string
     */
    protected $carLicense;

    /**
     * @Attribute("departmentNumber")
     * @ArrayField()
     * @var array
     */
    protected $departmentNumber;   
    
    /**
     * @Attribute("displayName")
     * @var string
     */
    protected $displayName;

    /**
     * @Attribute("employeeNumber")
     * @var string
     */
    protected $employeeNumber;

    /**
     * @Attribute("employeeType")
     * @var string
     */
    protected $employeeType;

    /**
     * @Attribute("givenName")
     * @var string
     */
    protected $givenName;

    /**
     * @Attribute("homePhone")
     * @var string
     */
    protected $homePhone;

    /**
     * @Attribute("homePostalAddress")
     * @var string
     */
    protected $homePostalAddress;

    /**
     * @Attribute("initials")
     * @var string
     */
    protected $initials;

    /**
     * @Attribute("jpegPhoto")
     * @var string
     */
    protected $jpegPhoto;

    /**
     * @Attribute("labeledURI")
     * @var string
     */
    protected $labeledURI;

    /**
     * @Attribute("mail")
     * @var string
     */
    protected $mail;

    /**
     * @Attribute("manager")
     * @ArrayField()
     * @var array
     */
    protected $manager;

    /**
     * @Attribute("mobile")
     * @var string
     */
    protected $mobile;

    /**
     * @Attribute("o")
     * @var string
     */
    protected $o;

    /**
     * @Attribute("pager")
     * @var string
     */
    protected $pager;

    /**
     * @Attribute("photo")
     * @var string
     */
    protected $photo;
    
    /**
     * @Attribute("preferredLanguage")
     * @var string
     */
    protected $preferredLanguage;

    /**
     * @Attribute("roomNumber")
     * @var string
     */
    protected $roomNumber;

    /**
     * @Attribute("secretary")
     * @var string
     */
    protected $secretary;

    /**
     * @Attribute("uid")
     * @var string
     */
    protected $uid;

    /**
     * @Attribute("userCertificate")
     * @var string
     */
    protected $userCertificate;
    
    /**
     * @Attribute("userPKCS12")
     * @var string
     */
    protected $userPKCS12;

    /**
     * @Attribute("userSMIMECertificate")
     * @var string
     */
    protected $userSMIMECertificate;
        
    /**
     * @Attribute("x500UniqueIdentifier")
     * @var string
     */
    protected $x500UniqueIdentifier;

    /**
     * InetOrgPerson constructor.
     * @param null $username
     * @param null $roles
     */
    public function __construct($username = null, $roles = null)
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    function getAudio() {
        return $this->audio;
    }

    /**
     * @return string
     */
    function getBusinessCategory() {
        return $this->businessCategory;
    }

    /**
     * @return string
     */
    function getCarLicense() {
        return $this->carLicense;
    }

    /**
     * @return array
     */
    function getDepartmentNumber() {
        return $this->departmentNumber;
    }

    /**
     * @return string
     */
    function getDisplayName() {
        return $this->displayName;
    }

    /**
     * @return string
     */
    function getEmployeeNumber() {
        return $this->employeeNumber;
    }

    /**
     * @return string
     */
    function getEmployeeType() {
        return $this->employeeType;
    }

    /**
     * @return string
     */
    function getGivenName() {
        return $this->givenName;
    }

    /**
     * @return string
     */
    function getHomePhone() {
        return $this->homePhone;
    }

    /**
     * @return string
     */
    function getHomePostalAddress() {
        return $this->homePostalAddress;
    }

    /**
     * @return string
     */
    function getInitials() {
        return $this->initials;
    }

    /**
     * @return string
     */
    function getJpegPhoto() {
        return $this->jpegPhoto;
    }

    /**
     * @return string
     */
    function getLabeledURI() {
        return $this->labeledURI;
    }

    /**
     * @return string
     */
    function getMail() {
        return $this->mail;
    }

    /**
     * @return array
     */
    function getManager() {
        return $this->manager;
    }

    /**
     * @return string
     */
    function getMobile() {
        return $this->mobile;
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
    function getPager() {
        return $this->pager;
    }

    /**
     * @return string
     */
    function getPhoto() {
        return $this->photo;
    }

    /**
     * @return string
     */
    function getPreferredLanguage() {
        return $this->preferredLanguage;
    }

    /**
     * @return string
     */
    function getRoomNumber() {
        return $this->roomNumber;
    }

    /**
     * @return string
     */
    function getSecretary() {
        return $this->secretary;
    }

    /**
     * @return string
     */
    function getUid() {
        return $this->uid;
    }

    /**
     * @return string
     */
    function getUserCertificate() {
        return $this->userCertificate;
    }

    /**
     * @return string
     */
    function getUserPKCS12() {
        return $this->userPKCS12;
    }

    /**
     * @return string
     */
    function getUserSMIMECertificate() {
        return $this->userSMIMECertificate;
    }

    /**
     * @return string
     */
    function getX500UniqueIdentifier() {
        return $this->x500UniqueIdentifier;
    }

    /**
     * @param $audio
     * @return $this
     */
    function setAudio($audio) {
        $this->audio = $audio;

        return $this;
    }

    /**
     * @param $businessCategory
     * @return $this
     */
    function setBusinessCategory($businessCategory) {
        $this->businessCategory = $businessCategory;

        return $this;
    }

    /**
     * @param $carLicense
     * @return $this
     */
    function setCarLicense($carLicense) {
        $this->carLicense = $carLicense;

        return $this;
    }

    /**
     * @param $departmentNumber
     * @return $this
     */
    function setDepartmentNumber($departmentNumber) {
        $this->departmentNumber = $departmentNumber;

        return $this;
    }

    /**
     * @param $displayName
     * @return $this
     */
    function setDisplayName($displayName) {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @param $employeeNumber
     * @return $this
     */
    function setEmployeeNumber($employeeNumber) {
        $this->employeeNumber = $employeeNumber;

        return $this;
    }

    /**
     * @param $employeeType
     * @return $this
     */
    function setEmployeeType($employeeType) {
        $this->employeeType = $employeeType;

        return $this;
    }

    /**
     * @param $givenName
     * @return $this
     */
    function setGivenName($givenName) {
        $this->givenName = $givenName;

        return $this;
    }

    /**
     * @param $homePhone
     * @return $this
     */
    function setHomePhone($homePhone) {
        $this->homePhone = $homePhone;

        return $this;
    }

    /**
     * @param $homePostalAddress
     * @return $this
     */
    function setHomePostalAddress($homePostalAddress) {
        $this->homePostalAddress = $homePostalAddress;

        return $this;
    }

    /**
     * @param $initials
     * @return $this
     */
    function setInitials($initials) {
        $this->initials = $initials;

        return $this;
    }

    /**
     * @param $jpegPhoto
     * @return $this
     */
    function setJpegPhoto($jpegPhoto) {
        $this->jpegPhoto = $jpegPhoto;

        return $this;
    }

    /**
     * @param $labeledURI
     * @return $this
     */
    function setLabeledURI($labeledURI) {
        $this->labeledURI = $labeledURI;

        return $this;
    }

    /**
     * @param $mail
     * @return $this
     */
    function setMail($mail) {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @param $manager
     * @return $this
     */
    function setManager($manager) {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @param $mobile
     * @return $this
     */
    function setMobile($mobile) {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * @param $o
     * @return $this
     */
    function setO($o) {
        $this->o = $o;

        return $this;
    }

    /**
     * @param $pager
     * @return $this
     */
    function setPager($pager) {
        $this->pager = $pager;

        return $this;
    }

    /**
     * @param $photo
     * @return $this
     */
    function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @param $preferredLanguage
     * @return $this
     */
    function setPreferredLanguage($preferredLanguage) {
        $this->preferredLanguage = $preferredLanguage;

        return $this;
    }

    /**
     * @param $roomNumber
     * @return $this
     */
    function setRoomNumber($roomNumber) {
        $this->roomNumber = $roomNumber;

        return $this;
    }

    /**
     * @param $secretary
     * @return $this
     */
    function setSecretary($secretary) {
        $this->secretary = $secretary;

        return $this;
    }

    /**
     * @param $uid
     * @return $this
     */
    function setUid($uid) {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @param $userCertificate
     * @return $this
     */
    function setUserCertificate($userCertificate) {
        $this->userCertificate = $userCertificate;

        return $this;
    }

    /**
     * @param $userPKCS12
     * @return $this
     */
    function setUserPKCS12($userPKCS12) {
        $this->userPKCS12 = $userPKCS12;

        return $this;
    }

    /**
     * @param $userSMIMECertificate
     * @return $this
     */
    function setUserSMIMECertificate($userSMIMECertificate) {
        $this->userSMIMECertificate = $userSMIMECertificate;

        return $this;
    }

    /**
     * @param $x500UniqueIdentifier
     * @return $this
     */
    function setX500UniqueIdentifier($x500UniqueIdentifier) {
        $this->x500UniqueIdentifier = $x500UniqueIdentifier;

        return $this;
    }
}
