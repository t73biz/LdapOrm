<?php

namespace CarnegieLearning\LdapOrmBundle\Tests\Ldap;

use CarnegieLearning\LdapOrmBundle\Ldap\Client;
use CarnegieLearning\LdapOrmBundle\Tests\SymfonyKernel;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class ClientTest
 * @package CarnegieLearning\LdapOrmBundle\Tests\Ldap
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{

    use SymfonyKernel;

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::__construct
     * @expectedException \Exception
     */
    public function testConstruct()
    {
        new Client($this->logger, []);
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::__construct
     */
    public function testConnectReturnsResource()
    {
        $config = array(
            'connection' => array(
                'uri' => 'ldap://ldaphost.carnegielearning.com/',
                'bind_dn' => 'cn=nobody,ou=hosted,dc=carnegielearning,dc=com',
                'password' => 'easy2guess',
                'password_type' => 'sha1',
                'use_tls' => false,
                'is_active_directory' => false,
            )
        );

        $client = new Client($this->logger, $config);

        $resource = $client->getLdapResource();

        $this->assertEquals($resource, $client->connect());
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     */
    public function testConnect()
    {
        $config = array(
            'connection' => array(
                'uri' => 'ldap://ldaphost.carnegielearning.com/',
                'bind_dn' => 'cn=nobody,ou=hosted,dc=carnegielearning,dc=com',
                'password' => 'easy2guess',
                'password_type' => 'sha1',
                'use_tls' => false,
                'is_active_directory' => false,
            )
        );

        new Client($this->logger, $config);
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     * @expectedException \Exception
     */
    public function testConnectTlsFailure()
    {
        $config = array(
            'connection' => array(
                'uri' => 'ldap://ldaphost.carnegielearning.com/',
                'bind_dn' => 'cn=nobody,ou=hosted,dc=carnegielearning,dc=com',
                'password' => 'easy2guess',
                'password_type' => 'sha1',
                'use_tls' => true,
                'is_active_directory' => false,
            )
        );

        new Client($this->logger, $config);
    }
}
