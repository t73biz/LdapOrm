<?php

namespace CarnegieLearning\LdapOrmBundle\Tests\Ldap;

use CarnegieLearning\LdapOrmBundle\Ldap\Client;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\PropertyInfo\Tests\Fixtures\Dummy;

/**
 * Class ClientTest
 * @package CarnegieLearning\LdapOrmBundle\Tests\Ldap
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Logger
     */
    protected $loggerMock;

    public function setUp()
    {
        $config = [
            'connection' => [
                'bind_dn' => 'uid=kvaughan,ou=People,dc=example,dc=com',
                'is_active_directory' => false,
                'password' => 'bribery',
                'password_type' => 'plaintext',
                'uri' => 'ldap://localhost:1234',
                'use_tls' => false
            ]
        ];

        $this->loggerMock = $this->getMockBuilder('\Symfony\Bridge\Monolog\Logger')
            ->setConstructorArgs(['name' => 'mockLogger'])
            ->getMock();
        $this->client = new Client($this->loggerMock, $config);
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::__construct
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testConstruct()
    {
        new Client($this->loggerMock, []);
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     */
    public function testConnect()
    {
        $this->assertNotFalse($this->client->connect());
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     */
    public function testConnectDoesNotReconnect()
    {
        $this->client->connect();
        $resource = $this->client->getLdapResource();
        $this->client->connect();
        $resource2 = $this->client->getLdapResource();

        $this->assertEquals($resource, $resource2);
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     * @expectedException \CarnegieLearning\LdapOrmBundle\Exception\InvalidLdapConnectionException
     */
    public function testConnectThrowsInvalidLdapConnectionException()
    {

        $coreMock = $this->getMockBuilder('\CarnegieLearning\LdapOrmBundle\Ldap\Core')
            ->getMock();

        $coreMock->expects($this->once())
            ->method('connect')
            ->will($this->returnValue(false));

        $this->client->setLdap($coreMock);
        $this->client->connect();

        $this->assertFalse($this->client->getLdapResource());
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     * @expectedException \CarnegieLearning\LdapOrmBundle\Exception\InvalidLdapTlsConnectionException
     */
    public function testConnectThrowsInvalidLdapTlsConnectionException()
    {

        $coreMock = $this->getMockBuilder('\CarnegieLearning\LdapOrmBundle\Ldap\Core')
            ->getMock();

        $coreMock->expects($this->once())
            ->method('startTls')
            ->will($this->returnValue(false));

        $coreMock->expects($this->once())
            ->method('connect')
            ->will($this->returnValue(null));

        $this->client->setUseTls(true);
        $this->client->setLdap($coreMock);
        $this->client->connect();
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     * @expectedException \CarnegieLearning\LdapOrmBundle\Exception\InvalidLdapBindException
     */
    public function testConnectThrowsInvalidLdapBindException()
    {

        $coreMock = $this->getMockBuilder('\CarnegieLearning\LdapOrmBundle\Ldap\Core')
            ->getMock();

        $coreMock->expects($this->once())
            ->method('bind')
            ->will($this->returnValue(false));

        $coreMock->expects($this->once())
            ->method('connect')
            ->will($this->returnValue(null));

        $this->client->setLdap($coreMock);
        $this->client->connect();
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::setLdap
     */
    public function testSetLdapHandlesNull()
    {
        $ldap = $this->client->setLdap();

        $this->assertInstanceOf('\CarnegieLearning\LdapOrmBundle\Ldap\Core', $ldap);
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::setLdap
     */
    public function testSetLdapHandlesSuppliedObject()
    {
        $ldap = $this->client->setLdap(new Dummy());

        $this->assertInstanceOf('\Symfony\Component\PropertyInfo\Tests\Fixtures\Dummy', $ldap);
    }

}
