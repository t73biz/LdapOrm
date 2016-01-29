<?php
/***************************************************************************
 * Copyright (C) 1999-2012 Gadz.org                                        *
 * http://opensource.gadz.org/                                             *
 *                                                                         *
 * This program is free software; you can redistribute it and/or modify    *
 * it under the terms of the GNU General Public License as published by    *
 * the Free Software Foundation; either version 2 of the License, or       *
 * (at your option) any later version.                                     *
 *                                                                         *
 * This program is distributed in the hope that it will be useful,         *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 * GNU General Public License for more details.                            *
 *                                                                         *
 * You should have received a copy of the GNU General Public License       *
 * along with this program; if not, write to the Free Software             *
 * Foundation, Inc.,                                                       *
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA                   *
 ***************************************************************************/
 
namespace CarnegieLearning\LdapOrmBundle\Mapping;

/**
 * Class ClassMetaDataCollection
 * @codeCoverageIgnore
 * @package CarnegieLearning\LdapOrmBundle\Mapping
 */
class ClassMetaDataCollection
{
    /**
     * @var array
     */
    private $metadatas;

    /**
     * @var string
     */
    private $repository;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array string
     */
    public $arrayOfLink;

    /**
     * @var string
     */
    public $sequences;

    /**
     * @var array string
     */
    public $dnRegex;

    /**
     * @var array string
     */
    public $parentLink;

    /**
     * @var string
     */
    public $objectClass;

    /**
     * @var string
     */
    public $searchDn;

    /**
     * @var string
     */
    public $dn;

    /**
     * @var array
     */
    public $arrayField;

    /**
     * @var array
     */
    public $must;

    /**
     * @var array
     */
    public $operational;

    /**
     * ClassMetaDataCollection constructor.
     */
    public function __construct()
    {
        $this->metadatas        = array();
        $this->reverseMetadatas = array();
        $this->arrayOfLink      = array();
        $this->dnRegex          = array();
        $this->parentLink       = array();
        $this->arrayField       = array();
        $this->must             = array();
        $this->operational      = array();
    }

    /**
     * @param $fieldName
     */
    public function addArrayField($fieldName)
    {
        $this->arrayField[$fieldName] = true;
    }

    /**
     * @param $fieldName
     */
    public function addMust($fieldName)
    {
        $this->must[$fieldName] = true;
    }

    /**
     * @param $fieldName
     */
    public function addOperational($fieldName)
    {
        $this->operational[$fieldName] = true;
    }

    /**
     * @param $fieldName
     * @return bool
     */
    public function isArrayField($fieldName)
    {
        if(isset($this->arrayField[$fieldName])) {
            return $this->arrayField[$fieldName];
        }

        return false;
    }

    /**
     * @param $objectClass
     * @return $this
     */
    public function setObjectClass($objectClass) {
        $this->objectClass = $objectClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectClass() {
        return $this->objectClass;
    }

    /**
     * @return string
     */
    function getSearchDn() {
        return $this->searchDn;
    }

    /**
     * @param $searchDn
     * @return $this
     */
    function setSearchDn($searchDn) {
        $this->searchDn = $searchDn;

        return $this;
    }

    /**
     * @return string
     */
    function getDn() {
        return $this->dn;
    }

    /**
     * @param $dn
     * @return $this
     */
    function setDn($dn) {
        $this->dn = $dn;

        return $this;
    }

    /**
     * @param $value
     * @return null
     */
    public function getKey($value)
    {
        if(isset($this->reverseMetadatas[$value])) {
            return $this->reverseMetadatas[$value];
        }

        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addMeta($key, $value)
    {
        $this->metadatas[$key] = $value;
        $this->reverseMetadatas[$value] = $key;
    }

    /**
     * @param $key
     * @return null
     */
    public function getMeta($key)
    {
        if(isset($this->metadatas[$key])) {
            return $this->metadatas[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getMetadatas()
    {
        return $this->metadatas;
    }

    /**
     * @param $metadatas
     * @return $this
     */
    public function setMetadatas($metadatas)
    {
        $this->metadatas = $metadatas;

        return $this;
    }

    /**
     * @param $key
     * @param $class
     */
    public function addArrayOfLink($key, $class)
    {
        $this->arrayOfLink[$key] = $class;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isArrayOfLink($key)
    {
        return isset($this->arrayOfLink[$key]);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getArrayOfLinkClass($key)
    {
        return $this->arrayOfLink[$key];
    }

    /**
     * @param $key
     * @param $dn
     */
    public function addSequence($key, $dn)
    {
        $this->sequence[$key] = $dn;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isSequence($key)
    {
        return isset($this->sequence[$key]);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getSequence($key)
    {
        return $this->sequence[$key];
    }

    /**
     * @param $key
     * @param $dn
     */
    public function addParentLink($key, $dn)
    {  
        $this->parentLink[$key] = $dn;
    }

    /**
     * @return array
     */
    public function getParentLink()
    {  
        return $this->parentLink;
    }

    /**
     * @param $key
     * @param $regex
     */
    public function addRegex($key, $regex)
    {
        $this->dnRegex[$key] = $regex;
    }

    /**
     * @return array
     */
    public function getDnRegex()
    {
        return $this->dnRegex;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param $repository
     * @return $this
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return array
     */
    function getMust() {
        return $this->must;
    }

    /**
     * @param $must
     * @return $this
     */
    function setMust($must) {
        $this->must = $must;

        return $this;
    }

    /**
     * @return array
     */
    public function getOperational()
    {
        return $this->operational;
    }

    /**
     * @param $operational
     * @return $this
     */
    public function setOperational($operational)
    {
        $this->operational = $operational;

        return $this;
    }


}
