<?php
namespace CarnegieLearning\LdapOrmBundle\Tests\Fixtures;

use CarnegieLearning\LdapOrmBundle\Entity\Ldap\LdapEntity;
use CarnegieLearning\LdapOrmBundle\Annotation\Ldap as Ldap;

/**
 * Class RetrieveEntity
 * @package CarnegieLearning\LdapOrmBundle\Tests\Fixtures
 *
 * @Ldap\ObjectClass("Person")
 * @Ldap\SearchDn("dc=example,dc=com")
 * @Ldap\Dn("uid={{ entity.uid }}ou=People,dc=example,dc=com")
 */
class RetrieveEntity extends LdapEntity
{

}