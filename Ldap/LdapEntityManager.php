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
 
namespace CarnegieLearning\LdapOrmBundle\Ldap;

use CarnegieLearning\LdapOrmBundle\Exception\MissingMustAttributeException;
use CarnegieLearning\LdapOrmBundle\Exception\MissingSearchDnException;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityRepository;
use Exception;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ArrayField;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Attribute;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Dn;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\DnLinkArray;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\DnPregMatch;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Must;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ObjectClass;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\ParentDn;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Repository as RepositoryAttribute;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\SearchDn;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Operational;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap\Sequence;
use CarnegieLearning\LdapOrmBundle\Components\GenericIterator;
use CarnegieLearning\LdapOrmBundle\Entity\DateTimeDecorator;
use CarnegieLearning\LdapOrmBundle\Ldap\Filter\LdapFilter;
use CarnegieLearning\LdapOrmBundle\Mapping\ClassMetaDataCollection;
use CarnegieLearning\LdapOrmBundle\Repository\Repository;
use ReflectionClass;
use Symfony\Bridge\Monolog\Logger;

/**
 * Entity Manager for LDAP
 * 
 * @author Mathieu GOULIN <mathieu.goulin@gadz.org>
 * @author Jason Gabler <jasongabler@gmail.com>
 * @author Ronald Chaplin <rchaplin@t73.biz>
 */
class LdapEntityManager
{
    /**
     *
     */
    const DEFAULT_MAX_RESULT_COUNT      = 100;

    /**
     * @var string
     */
    private $pageCookie;

    /**
     * @var
     */
    private $pageMore;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var null
     */
    private $iterator = Null;

    /**
     * @var Client
     */
    private $client;

    /**
     * Build the Entity Manager service
     *
     * @param Logger $logger
     * @param Reader $reader
     * @param Client $client
     */
    public function __construct(Logger $logger, Reader $reader, Client $client)
    {
        $this->logger = $logger;
        $this->reader = $reader;
        $this->client = $client;
        $this->client->connect();
    }

    /**
     * Find if an entity exists in LDAP without doing an LDAP search that generates
     * warnings regarding an non-existent DN if turns out that the entity does not exist.
     * @param $entity ... The entity to check for existence. Entity must have all MAY attributes.
     * @param bool $checkOnly
     * @return bool Returns true if the given entity exists in LDAP
     * @throws MissingMustAttributeException
     */
    public function entityExists($entity, $checkOnly = true) {
        $this->checkMust($entity);
        $entityClass = get_class($entity);
        $meta = $this->getClassMetadata($entityClass);
        $entityArray = array();


        $entityMethods = get_class_methods($entityClass);
        foreach ($entityMethods as $methodName) {
            // We just need getters
            if (strpos($methodName, 'get') === 0) {
                // We just need basic, parameter-less getters, also the twig->render() isn't
                // supplying a parameter to the getter, so render() will break if we let a
                // getter that takes a parameter slip through.
                if ((new \ReflectionMethod($entityClass,$methodName))->getNumberOfParameters() < 1) {
                    $variable = lcfirst(substr($methodName, 3));
                    $entityArray[$variable] = $entity->$methodName();
                }
            }
        }

        $dnSkel = $meta->getDn();
        $dn = $this->twig->render($dnSkel, array('entity' => $entityArray));

        $searchDnSkel = $meta->getSearchDn();
        $searchDn = $this->twig->render($searchDnSkel, array('entity' => $entityArray));

        $rdnString = preg_replace('/(,*)'.$searchDn.'/', '', $dn);
        $filter = array();
        foreach (explode(',', $rdnString) as $rdn) {
            $pair = explode('=', $rdn);
            if (!empty($pair[0])) {
                $filter[$pair[0]] = $pair[1];
            }
        }
        if (empty($filter)) {
            $filter = '(objectClass=*)';
        } else {
            $filter = array('&' => $filter);
        }

        $entities = $this->retrieve($entityClass, array(
            'searchDn' => $searchDn,
            'filter' => $filter,
        ));

        if ($checkOnly) {
            return (count($entities) > 0);
        } else {
            return $entities;
        }
    }

    /**
     * Return the class metadata instance
     * 
     * @param string $entityName
     * 
     * @return ClassMetaDataCollection
     */
    public function getClassMetadata($entityName)
    {
        $r = new ReflectionClass($entityName);
        $instanceMetadataCollection = new ClassMetaDataCollection();
        $instanceMetadataCollection->name = $entityName;
        $classAnnotations = $this->reader->getClassAnnotations($r);

        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof RepositoryAttribute) {
                $instanceMetadataCollection->setRepository($classAnnotation->getValue());
            }
            if ($classAnnotation instanceof ObjectClass) {
                $instanceMetadataCollection->setObjectClass($classAnnotation->getValue());
            }
            if ($classAnnotation instanceof SearchDn) {
                $instanceMetadataCollection->setSearchDn($classAnnotation->getValue());
            }
            if ($classAnnotation instanceof Dn) {
                $instanceMetadataCollection->setDn($classAnnotation->getValue());
            }
        }

        foreach ($r->getProperties() as $publicAttr) {
            $annotations = $this->reader->getPropertyAnnotations($publicAttr);
            
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Attribute) {
                    $varname = $publicAttr->getName();
                    $attribute=$annotation->getName();
                    $instanceMetadataCollection->addMeta($varname, $attribute);
                }
                if ($annotation instanceof DnLinkArray) {
                    $varname=$publicAttr->getName();
                    $instanceMetadataCollection->addArrayOfLink($varname, $annotation->getValue());
                }
                if ($annotation instanceof Sequence) {
                    $varname=$publicAttr->getName();
                    $instanceMetadataCollection->addSequence($varname, $annotation->getValue());
                }
                if ($annotation instanceof DnPregMatch) {
                    $varname=$publicAttr->getName();
                    $instanceMetadataCollection->addRegex($varname, $annotation->getValue());
                }
                if ($annotation instanceof ParentDn) {
                    $varname=$publicAttr->getName();
                    $instanceMetadataCollection->addParentLink($varname, $annotation->getValue());
                }
                if ($annotation instanceof ArrayField) {
                    $instanceMetadataCollection->addArrayField($varname);
                }
                if ($annotation instanceof Must) {
                    $instanceMetadataCollection->addMust($varname);
                }
                if ($annotation instanceof Operational) {
                    $instanceMetadataCollection->addOperational($varname);
                }
            }
        }

        return $instanceMetadataCollection;
    }

    /**
     * Convert an entity to array using annotation reader
     * 
     * @param $instance
     * 
     * @return array
     */
    private function entityToEntry($instance)
    {
        $instanceClassName = get_class($instance);
        $arrayInstance=array();

        $r = new ReflectionClass($instanceClassName);
        $instanceMetadataCollection = $this->getClassMetadata($instance);
        $classAnnotations = $this->reader->getClassAnnotations($r);

        $arrayInstance['objectClass'] = array('top');

        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof ObjectClass && ($classAnnotationValue = $classAnnotation->getValue()) !== '' ) {
                array_push($arrayInstance['objectClass'], $classAnnotationValue);
            }
        }

        foreach($instanceMetadataCollection->getMetadatas() as $varname) {
            $getter = 'get' . ucfirst($instanceMetadataCollection->getKey($varname));
            $setter = 'set' . ucfirst($instanceMetadataCollection->getKey($varname));

            $value  = $instance->$getter();
            if($value == null) {
                if($instanceMetadataCollection->isSequence($instanceMetadataCollection->getKey($varname))) {

                    $sequence = $instanceMetadataCollection->getSequence($instanceMetadataCollection->getKey($varname));

                    $value = (int) $this->generateSequenceValue($sequence);
                    $instance->$setter($value);
                }
            }
            // Specificity of ldap (incopatibility with ldap boolean)
            if(is_bool($value)) {
                if($value) {
                    $value = "TRUE";
                } else {
                    $value = "FALSE";
                }
            }

            if(is_object($value)) {
                if ($value instanceof DateTime) {
                    $arrayInstance[$varname] = Converter::toLdapDateTime($value, false);
                }
                elseif ($value instanceof DateTimeDecorator) {
                    $arrayInstance[$varname] = (string)$value;
                }
                else {
                    $arrayInstance[$varname] = $this->buildEntityDn($value);
                }
            } elseif(is_array($value) && !empty($value) && isset($value[0]) && is_object($value[0])) {
                    $valueArray = array();
                    foreach($value as $val) {
                        $valueArray[] = $this->buildEntityDn($val);
                    }
                    $arrayInstance[$varname] = $valueArray;
            } elseif(strtolower($varname) == "userpassword") {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $needle = '{CLEAR}';
                        if (strpos($val, $needle) === 0) {
                            $arrayInstance[$varname] =  substr($val, strlen($needle));
                        }
                    }
                } else {
                    $arrayInstance[$varname] = $value;
                }
            }  else {
                $arrayInstance[$varname] = $value;
            }
        }

        return $arrayInstance;
    }

    public function renderString($string, $vars)
    {
        return $this->twig->render($string, $vars);
    }

    /**
     * Build a DN for an entity with the use of dn annotation
     * 
     * @param $instance
     * 
     * @return string
     */
    public function buildEntityDn($instance)
    {
        $instanceClassName = get_class($instance);

        $r = new ReflectionClass($instanceClassName);

        $classAnnotations = $this->reader->getClassAnnotations($r);

        $entityDn = '';
        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof Dn) {
                $entityDn = $classAnnotation->getValue();
                break;
            }
        }

        return $entityDn;
    }

    /**
     * Persist an instance in Ldap
     * @param unknown_type $entity
     * @param bool $checkMust
     * @throws MissingMustAttributeException
     */
    public function persist($entity, $checkMust = true)
    {
        if ($checkMust) {
            $this->checkMust($entity);
        }
        $entry= $this->entityToEntry($entity);
        $this->logger->info('to array : ' . serialize($entry));

        $dn = $this->buildEntityDn($entity);

        // test if entity already exist

        $currentEntity = $this->entityExists($entity, false);
        if(count($currentEntity) > 0)
        {
            unset($entry['objectClass']);
            $this->ldapUpdate($dn, $entry, $currentEntity[0]);
            return;
        }
        $this->ldapPersist($dn, $entry);
        return;
    }

    /**
     * Delete an instance in Ldap
     * @param unknown_type $instance
     */
    public function delete($instance)
    {  
        $dn = $this->buildEntityDn($instance);
        $this->logger->info('Delete in LDAP: ' . $dn );
	$this->deleteByDn($dn, true);
        return;
    }

    /**
     * Delete an entry in ldap by Dn
     * @param string $dn
     * @param bool $recursive
     * @return bool
     * @throws Exception
     */
    public function deleteByDn($dn, $recursive=false)
    {
        // Connect if needed
        $this->connect();

        $this->logger->info('Delete (recursive=' . $recursive . ') in LDAP: ' . $dn );

        if($recursive == false) {
            return(ldap_delete($this->client->getLdapResource(), $dn));
        } else {
            //searching for sub entries
            $sr=ldap_list($this->client->getLdapResource(), $dn, "ObjectClass=*", array(""));
            $info = ldap_get_entries($this->client->getLdapResource(), $sr);

            for($i = 0; $i < $info['count']; $i++) {
                //deleting recursively sub entries
                $result=$this->deleteByDn($info[$i]['dn'], true);
                if(!$result) {
                    //return result code, if delete fails
                    return($result);
                }
            }
            return(ldap_delete($this->client->getLdapResource(), $dn));
        }
    }

    /**
     * Send entity to database
     */
    public function flush()
    {
        return;
    }

    /**
     * Gets the repository for an entity class.
     *
     * @param string $entityName The name of the entity.
     * 
     * @return EntityRepository The repository class.
     */
    public function getRepository($entityName)
    {
        $metadata = $this->getClassMetadata($entityName);
        if($metadata->getRepository()) {
            $repository = $metadata->getRepository();
            return new $repository($this, $metadata);
        }
        return new Repository($this, $metadata);
    }


    /**
     * Check the MUST attributes for the given object according to its LDAP
     * objectClass. If all MUST attributes are satisfied checkMust() will return
     * a boolean true, otherwise it returns the offending attribute name.
     * @param $instance
     * @return TRUE or the name of the offending attribute
     * @throws MissingMustAttributeException
     */
    public function checkMust($instance) {
        $emptyAttribute = null;
        $classMetaData = $this->getClassMetaData(get_class($instance));
        
        foreach ($classMetaData->getMust() as $mustAttributeName => $existence) {
            $getter = 'get'.ucfirst($mustAttributeName);
            $value = $instance->$getter();
            if (empty($value)) {
                throw new MissingMustAttributeException($mustAttributeName);
            }
        }
        return true;
    }


    /**
     * Persist an array using ldap function
     * 
     * @param unknown_type $dn
     * @param array        $arrayInstance
     */
    private function ldapPersist($dn, Array $arrayInstance)
    {
        // Connect if needed
        $this->connect();
        
        list($toInsert,) = $this->splitArrayForUpdate($arrayInstance);

        $this->logger->info("Insert $dn in LDAP : " . json_encode($toInsert));
        ldap_add($this->client->getLdapResource(), $dn, $toInsert);
    }

    /**
     * Splits modified and removed attributes and make sure they are compatible with ldap_modify & insert
     *
     * @param array        $entry
     * 
     * @return array
     */
    private function splitArrayForUpdate($entry, $currentEntity = null)
    {
        $toModify = array_filter(
            $entry,
            function ($elm) { // removes NULL, FALSE and '' ; keeps everything else (like 0's)
                return !is_null($elm) && $elm!==false && $elm!=='';
            }
        );
        $toDelete = array_fill_keys(array_keys(array_diff_key($entry, $toModify)), array());
        if ($currentEntity != null) {
            $currentEntry = $this->entityToEntry($currentEntity);
            foreach (array_keys($entry) as $key) {
                if (empty($entry[$key]) && empty($currentEntry[$key])) {
                    unset($toDelete[$key]);
                }
            }
        }
        foreach ($toModify as &$val) {
            if (is_array($val)) {
                list($val,) = $this->splitArrayForUpdate($val); // Multi-dimensional arrays are also fixed
            }
            elseif(is_string($val)) {
                #$val = utf8_encode($val);
            }
            elseif($val instanceof \Datetime) { // It shouldn't happen, but tests did reveal such cases
                $val = new DateTimeDecorator($val);
            }
        }
        return array(array_merge($toModify), array_merge($toDelete)); // array_merge is to re-index gaps in keys
    }
    
    /**
     * Update an object in ldap with array
     *
     * @param unknown_type $dn
     * @param array        $entry
     */
    private function ldapUpdate($dn, Array $entry, $entity)
    {
        // Connect if needed
        $this->connect();

        list($toModify, $toDelete) = $this->splitArrayForUpdate($entry, $entity);

        // Do not attempt to modify operational attributes
        foreach($this->getClassMetadata($entity)->getOperational() as $operationalAttributeName => $status) {
            if ($status) {
                unset($toModify[$operationalAttributeName]);
                unset($toDelete[$operationalAttributeName]);
            }
        }

        if (!empty($toModify)) {
            unset($toModify['dn']);
            $this->logger->info("Modify $dn in LDAP : " . json_encode($toModify));
            ldap_modify($this->client->getLdapResource(), $dn, $toModify);
        }




        if (!empty($toDelete)) {
            $this->logger->info("Suppress from $dn these attributs in LDAP : " . json_encode($toDelete));
            try {
                ldap_mod_del($this->client->getLdapResource(), $dn, $toDelete);
            }
            catch(Exception $e) {
            }
        }
    }

    /**
     * The core of ORM behavior for this bundle: retrieve data
     * from LDAP and convert results into objects.
     *
     * Options maybe:
     *
     * attributes (array): array of attribute types (strings)
     * filter (LdapFilter): a filter array or a correctly formatted filter string
     * max (integer): the maximum limit of entries to return
     * searchDn (string): the search DN
     * subentryNodes (array): parameters for the left hand side of a searchDN, useful for mining subentries.
     * pageSize (integer): employ pagination and return pages of the given size
     * pageCookie (opaque): The opaque stucture sent by the LDAP server to maintain pagination state. Defaults is empty string.
     * pageCritical (boolean): if pagination employed, force paging and return no results on service which do not provide it. Default is true.
     * checkOnly (boolean): Only check result existence; don't convert search results to Symfony entities. Default is false.
     *
     * @param string $entityName
     * @param array $options
     * @return array
     * @throws MissingSearchDnException
     */
    public function retrieve($entityName, $options = array())
    {
        $paging = !empty($options['pageSize']);
        $instanceMetadataCollection = $this->getClassMetadata($entityName);

        // Discern max result size
        $max = empty($options['max']) ? self::DEFAULT_MAX_RESULT_COUNT : $options['max'];

        // Employ results paging if requested with pageSize option
        if ($paging) {
            if (!isset($options['pageCritical'])) {
                $options['pageCritical'] = FALSE;
            }
            if (isset($options['pageCookie'])) {
                $this->pageCookie = $options['pageCookie'];
            }

            ldap_control_paged_result($this->client->getLdapResource(), $options['pageSize'], $options['pageCritical'], $this->pageCookie);
        }

        // Discern subentryNodes for substituing into searchDN
        $subentryNodes = empty($options['subentryNodes']) ? array() : $options['subentryNodes'];


        // Discern search DN
        if (isset($options['searchDn'])) {
            $searchDn = $options['searchDn'];
        } else {
            $searchDn = $instanceMetadataCollection->getSearchDn();
        }

        if (empty($searchDn)) {
             throw new MissingSearchDnException('Could not discern search DN while searching for ' . $entityName);
        }
        
        // Discern LDAP filter
        $objectClass = $instanceMetadataCollection->getObjectClass();
        if (empty($options['filter'])) {
            $filter = '(objectClass='.$objectClass.')';
        } else {
            if (is_array($options['filter'])) {
                $options['filter'] = array(
                    '&' => array(
                        'objectClass' => $objectClass,
                        $options['filter']
                    )
                );
                $ldapFilter = new LdapFilter($options['filter']);
                $filter = $ldapFilter->format();
            } else if (is_a ($options['filter'], LdapFilter::class)){
                $options['filter']->setFilterArray(
                    array(
                        '&' => array(
                            'objectClass' => $objectClass,
                            $options['filter']->getFilterArray()
                        )
                    )
                );
                $filter = $options['filter']->format();
            } else { // assume pre-formatted scale/string filter value
                $filter = '(&(objectClass='.$objectClass.')'.$options['filter'].')';
            }
        }

        // Discern attributes to retrieve
        if (empty($options['attributes'])) {
            $attributes = array_values($instanceMetadataCollection->getMetadatas());
        } else {
            $attributes = $options['attributes'];
        }

        // Search LDAP
        $searchResult = $this->doRawLdapSearch($filter, $attributes, $max, $searchDn);

        $entries = @ldap_get_entries($this->client->getLdapResource(), $searchResult);
        if (!empty($options['checkOnly']) && $options['checkOnly'] == true) {
            return ($entries['count'] > 0);
        }
        $entities = array();
        foreach ($entries as $entry) {
            if(is_array($entry)) {
                    $entities[] = $this->entryToEntity($entityName, $entry);
            }
        }

        if ($paging) {
            ldap_control_paged_result_response($this->client->getLdapResource(), $searchResult, $this->pageCookie);
            $this->pageMore = !empty($this->pageCookie);
        }

        if($entries['count'] == 1) {
            return $entities[0];
        }

        return $entities;
    }

    /**
     * Get the PHP LDAP pagination cookie
     * @return string
     */
    public function getPageCookie()
    {
        return $this->pageCookie;
    }

    /**
     * Check if the results pager has more results to return
     * @return boolean
     */
    public function pageHasMore()
    {
        return $this->pageMore;
    }



    /**
     * retrieve object from dn
     *
     * @param string     $dn
     * @param string     $entityName
     * @param integer    $max
     *
     * @return array
     */
    public function retrieveByDn($dn, $entityName, $max = self::DEFAULT_MAX_RESULT_COUNT, $objectClass = "*")
    {

        $instanceMetadataCollection = $this->getClassMetadata($entityName);

        $data = array();
        $this->logger->info('Search in LDAP: ' . $dn . ' query (ObjectClass=*)');
        try {
            $sr = ldap_search($this->client->getLdapResource(),
                $dn,
                '(ObjectClass=' . $objectClass . ')',
                array_values($instanceMetadataCollection->getMetadatas()),
                0
            );
            $infos = ldap_get_entries($this->client->getLdapResource(), $sr);
            foreach ($infos as $entry) {
                if(is_array($entry)) {
                    $data[] = $this->entryToEntity($entityName, $entry);
                        }
                    }
        } catch(Exception $e) {
            $data = array();
        }
 
        return $data;
    }

    public function doRawLdapGetDn($rawResult)
    {
        return ldap_get_dn($this->client->getLdapResource(), $rawResult);
    }

    public function doRawLdapGetAttributes($rawResult)
    {
        return ldap_get_attributes($this->client->getLdapResource(), $rawResult);
    }

    public function doRawLdapCountEntries($rawResult)
    {
        return ldap_count_entries($this->client->getLdapResource(), $rawResult);
    }

    public function doRawLdapFirstEntry($rawResult)
    {
        return ldap_first_entry($this->client->getLdapResource(), $rawResult);
    }

    public function doRawLdapNextEntry($rawResult)
    {
        return ldap_next_entry($this->client->getLdapResource(), $rawResult);
    }

    public function doRawLdapSearch($rawFilter, $attributes, $count, $searchDN)
    {
        $this->logger->info(sprintf("Request on ldap root:%s with filter:%s", $searchDN, $rawFilter));

        return ldap_search($this->client->getLdapResource(),
            $searchDN,
            $rawFilter,
            $attributes,
            0);
    }

    public function getIterator(LdapFilter $filter, $entityName) {
        if (empty($this->iterator)) {
            $this->iterator = new LdapIterator($filter, $entityName, $this);
        }
        return $this->iterator;
    }


    public function cleanArray($array)
    {  
        $newArray = array();
        foreach(array_keys($array) as $key) {
            $newArray[strtolower($key)] = $array[$key];
        }

        return $newArray;
    }


    public function entryToEntity($entityName, $entryData)
    {

        $instanceMetadataCollection = $this->getClassMetadata($entityName);

        $entryData = $this->cleanArray($entryData);
        $dn = $entryData['dn'];
        $entity = new $entityName();
        $metaDatas = $instanceMetadataCollection->getMetadatas();

        // The 'cn' attribite is at the heart of LDAP entries and entities and is often required for
        // many other processes. Make this this gets applied from the entry to the entity first.
        if (!empty($entryData['cn'][0])) {
            $entity->setCn($entryData['cn'][0]);
        }
        foreach($metaDatas as $attrName => $attrValue) {
            $attrValue = strtolower($attrValue);
            if($instanceMetadataCollection->isArrayOfLink($attrName))
            {
                $entityArray = array();
                if(!isset($entryData[$attrValue])) {
                    $entryData[$attrValue] = array('count' => 0);
                }
                $linkArray = $entryData[$attrValue];
                $count = $linkArray['count'];
                for($i = 0; $i < $count; $i++) {
                    if($linkArray[$i] != null) {
                        $targetArray = $this->retrieveByDn($linkArray[$i], $instanceMetadataCollection->getArrayOfLinkClass($attrName), 1);
                        $entityArray[] = $targetArray[0];
                    }
                }
                $setter = 'set' . ucfirst($attrName);
                $entity->$setter($entityArray);
            } else {
                $setter = 'set' . ucfirst($attrName);
                if (!isset($entryData[$attrValue])) {
                    continue; // Don't set the atribute if not exit
                }
                try {
                    if(preg_match('/^\d{14}/', $entryData[$attrValue][0])) {
                        if ($this->client->getIsActiveDirectory()) {
                            $datetime = Converter::fromAdDateTime($entryData[$attrValue][0], false);
                        } else {
                            $datetime = Converter::fromLdapDateTime($entryData[$attrValue][0], false);
                        }
                        $entity->$setter($datetime);
                    } elseif ($instanceMetadataCollection->isArrayField($attrName)) {
                        unset($entryData[$attrValue]["count"]);
                        $entity->$setter($entryData[$attrValue]);
                    } else {
                        $entity->$setter($entryData[$attrValue][0]);
                    }
                } catch (Exception $e) {
                    $this->logger->err(sprintf("Exception in ldap to entity mapping : %s", $e->getMessage()));
                }
           }
        }
        foreach($instanceMetadataCollection->getDnRegex() as $attrName => $regex) {
            preg_match_all($regex, $entryData['dn'], $matches);
            $setter = 'set' . ucfirst($attrName);
            $entity->$setter($matches[1]);
        }
        if($dn != '') {
            $entity->setDn($dn);
            foreach($instanceMetadataCollection->getParentLink() as $attrName => $parentClass) {
                $setter = 'set' . ucfirst($attrName);
                $parentDn = preg_replace('/^[a-zA-Z0-9]*=[a-zA-Z0-9]*,/', '', $dn);
                $link = $this->retrieveByDn($parentDn, $parentClass);
                if(count($link) > 0) {
                    $entity->$setter($link[0]);
                }
            }
        }

        return $entity;
    }

    private function generateSequenceValue($dn)
    {
        $sr = ldap_search($this->client->getLdapResource(),
            $dn,
            '(objectClass=integerSequence)'
        );
        $infos = ldap_get_entries($this->client->getLdapResource(), $sr);
        $sequence = $infos[0];
        $return = $sequence['nextvalue'][0];
        $newValue = $sequence['nextvalue'][0] + $sequence['increment'][0];
        $entry = array(
            'nextvalue' => array($newValue),
        );
        ldap_modify($this->client->getLdapResource(), $dn, $entry);
        return $return;
    }
}