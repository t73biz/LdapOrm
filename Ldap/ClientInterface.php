<?php
namespace CarnegieLearning\LdapOrmBundle\Ldap;

/**
 * Interface ClientInterface
 * @package CarnegieLearning\LdapOrmBundle\Ldap
 */
interface ClientInterface
{
    /**
     * @return null
     */
    public function connect();

    public function getLdapResource();

    public function getIsActiveDirectory();
}