<?php

/* * *************************************************************************
 * Copyright (C) 1999-2013 Gadz.org                                        *
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
 * ************************************************************************* */

namespace CarnegieLearning\LdapOrmBundle\Repository;

use CarnegieLearning\LdapOrmBundle\Ldap\LdapEntityManager;
use CarnegieLearning\LdapOrmBundle\Mapping\ClassMetaDataCollection;
use CarnegieLearning\LdapOrmBundle\Ldap\Filter\LdapFilter;

/**
 * Repository for fetching ldap entity
 */
class Repository {

    /**
     * @var LdapEntityManager
     */
    protected $em;

    /**
     * @var
     */
    protected $it;

    /**
     * @var ClassMetaDataCollection
     */
    private $class;

    /**
     * @var string
     */
    private $entityName;

    /**
     * Build the LDAP repository for the given entity type (i.e. class)
     *
     * @param LdapEntityManager $em
     * @param ClassMetaDataCollection $class
     * @internal param ReflectionClass $reflectorClass
     */
    public function __construct(LdapEntityManager $em, ClassMetaDataCollection $class) {
        $this->em = $em;
        $this->class = $class;
        $this->entityName = $class->name;
    }


    /**
     * Adds support for magic finders.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return array|object The found entity/entities.
     * @throws \BadMethodCallException  If the method called is an invalid find* method
     *                                 or no find* method at all and therefore an invalid
     *                                 method call.
     */
    public function __call($method, $arguments) {
        switch (true) {
            case (0 === strpos($method, 'findBy')):
                $by = lcfirst(substr($method, 6));
                $method = 'findBy';
                break;

            case (0 === strpos($method, 'findOneBy')):
                $by = lcfirst(substr($method, 9));
                if ($this->class->getMeta($by) == null) {
                    throw new \BadMethodCallException("No such ldap attribute $by in $this->entityName");
                }
                $method = 'findOneBy';
                break;

            default:
                throw new \BadMethodCallException(
                    "Undefined method '$method'. The method name must start with " .
                    "either findBy or findOneBy!"
                );
        }

        return $this->$method(
            $by, // attribute name
            $arguments[0], // attribute value
            empty($arguments[1]) ? null : $arguments[1] // attribute list
        );
    }

    /**
     * Create a simple LDAP filter for the current respository by specifying
     * the analogous objectClass. If attribute name & value are supplied
     * the filter will also contain a constraint for that attribute.
     *
     * Also saves previously used filters objects to prevent excessive memory footprint usage
     *
     * @param bool $varname
     * @param bool $value
     * @return array An LdapFilter
     */
    private function getFilter($varname = false, $value = false) {
        static $allFilters = array();
        if ($varname === false) {
            $attribute = 'objectClass';
            $value = $this->class->getObjectClass();
        } else {
            $attribute = $this->class->getMeta($varname);
        }
        if (!isset($allFilters[$key = base64_encode($this->class->getObjectClass() . $attribute . $value)])) {
            $allFilters[$key] = new LdapFilter(array(
                $attribute => $value,
                'objectClass' => $this->class->getObjectClass(),
            ));
        }
        return $allFilters[$key];
    }

    /**
     * Simple LDAP search for all entries within the current repository
     * @return An array of LdapEntity objects
     */
    public function findAll($attributes = null) {    
        $options = array();
        if ($attributes != null) {
            $options['attributes'] = $attributes;
        }
        return $this->em->retrieve($this->entityName, $options);        
    }

    /**
     * Simple LDAP search with a single attribute name/value pair
     * within the current repository
     * @param string $varname LDAP attribute name
     * @param string $value LDAP attribute value
     * @param null $attributes
     * @return array An array of LdapEntity objects
     */
    public function findBy($varname, $value, $attributes = null) {
        $options = array();
        $options['filter'] = new LdapFilter(array($varname => $value));
        if ($attributes != null) {
            $options['attributes'] = $attributes;
        }
        return $this->em->retrieve($this->entityName, $options);
    }

    
    /**
     * Return an object or objects with corresponding varname as Criteria.
     * @todo This should return an error when more than one is found
     * @param string $varname LDAP attribute name
     * @param string $value LDAP vattribute value
     * @return An LdapEntity

     */
    public function findOneBy($varname, $value, $attributes) {
        $r = $this->findBy($varname, $value, $attributes);
        if (empty($r[0])) {
            return array();
        } else {
            return $r[0];
        }        
    }


    /**
     * @param $filterArray
     * @param array $attributes
     * @return string LDAP filter string
     * @see \CarnegieLearning\LdapOrmBundle\Ldap\Filter\LdapFilter::createComplexLdapFilter($mixed)
     */
    public function findByComplex($filterArray, $attributes = array()) {
        $options = [];
        $options['filter'] = new LdapFilter($filterArray);

        foreach ($attributes as $key => $value) {
            $options[$key] = $value;
        }

        return $this->em->retrieve($this->entityName, $options);
    }

    /**
     * @return string
     */
    public function getSearchDn()
    {
        return$this->class->getSearchDn();
    }

   /**
     * Uses the new Iterator in LdapEntityManager to return the first element of a search
     * 
     * Returns false if there are no more objects in the iterator
     */
    public function itFindFirst($varname = false, $value = false) {
        if (empty($this->it)) {
            $this->it = $this->em->getIterator($this->getFilter($varname, $value), $this->entityName);
        }
        return $this->it->first();
    }

    /**
     * Uses the new Iterator in LdapEntityManager to return the next element of a search
     * 
     * Returns false if there are no more objects in the iterator
     */
    public function itGetNext($varname = false, $value = false) {
        if (empty($this->it)) {
            $this->it = $this->em->getIterator($this->getFilter($varname, $value), $this->entityName);
        }
        return $this->it->next();
    }

    /**
     * Verify that we are at the beggining of the iterator
     *
     * @return boolean 
     */
    public function itBegins() {
        return isset($this->it) ? $this->it->isFirst() : false;
    }

    /**
     * Verify that we are at the end of the iterator
     *
     * @return boolean 
     */
    public function itEnds() {
        return isset($this->it) ? $this->it->isLast() : false;
    }

    /**
     * Removes an iterator 
     */
    public function itReset() {
        unset($this->it);
    }
    
    
}
