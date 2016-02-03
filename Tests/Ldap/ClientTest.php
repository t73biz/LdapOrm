<?php

namespace CarnegieLearning\LdapOrmBundle\Tests\Ldap;

use CarnegieLearning\LdapOrmBundle\Ldap\Client;
use CarnegieLearning\LdapOrmBundle\Tests\SymfonyKernel;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\PropertyInfo\Tests\Fixtures\Dummy;

/**
 * Class ClientTest
 * @package CarnegieLearning\LdapOrmBundle\Tests\Ldap
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    use SymfonyKernel;

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::__construct
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testConstruct()
    {
        new Client($this->logger, []);
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     */
    public function testConnect()
    {
        $client = $this->container->get('carnegie_learning_ldap_orm.client');

        $this->assertNotFalse($client->connect());
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::connect
     */
    public function testConnectDoesNotReconnect()
    {
        $client = $this->container->get('carnegie_learning_ldap_orm.client');
        $client->connect();
        $resource = $client->getLdapResource();
        $client->connect();
        $resource2 = $client->getLdapResource();

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

        $client = $this->container->get('carnegie_learning_ldap_orm.client');

        $client->setLdap($coreMock);
        $client->connect();

        $this->assertFalse($client->getLdapResource());
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

        $client = $this->container->get('carnegie_learning_ldap_orm.client');
        $client->setUseTls(true);
        $client->setLdap($coreMock);
        $client->connect();
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

        $client = $this->container->get('carnegie_learning_ldap_orm.client');
        $client->setLdap($coreMock);
        $client->connect();
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::setLdap
     */
    public function testSetLdapHandlesNull()
    {
        $client = $this->container->get('carnegie_learning_ldap_orm.client');
        $ldap = $client->setLdap();

        $this->assertInstanceOf('\CarnegieLearning\LdapOrmBundle\Ldap\Core', $ldap);
    }

    /**
     * @covers CarnegieLearning\LdapOrmBundle\Ldap\Client::setLdap
     */
    public function testSetLdapHandlesSuppliedObject()
    {
        $client = $this->container->get('carnegie_learning_ldap_orm.client');
        $ldap = $client->setLdap(new Dummy());

        $this->assertInstanceOf('\Symfony\Component\PropertyInfo\Tests\Fixtures\Dummy', $ldap);
    }

}
