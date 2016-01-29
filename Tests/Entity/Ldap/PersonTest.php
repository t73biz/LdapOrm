<?php
namespace CarnegieLearning\LdapOrmBundle\Tests\Entity\Ldap;

use CarnegieLearning\LdapOrmBundle\Entity\Ldap\Person;

class PersonTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \CarnegieLearning\LdapOrmBundle\Entity\Ldap\Person::getUserPassword
     */
    public function testGetUserPasswordReturnsNull()
    {
        $person = new Person();
        $password = $person->getUserPassword(null);
        $this->assertNull($password);
    }

    /**
     * @covers \CarnegieLearning\LdapOrmBundle\Entity\Ldap\Person::getUserPassword
     */
    public function testGetUserPasswordReturnsEmpty()
    {
        $person = new Person();
        $password = $person->getUserPassword('');
        $this->assertEmpty($password);
    }

    /**
     * @covers \CarnegieLearning\LdapOrmBundle\Entity\Ldap\Person::getUserPassword
     */
    public function testGetUserPasswordReturnsScalar()
    {
        $person = new Person();
        $person->setUserPassword('test');
        $password = $person->getUserPassword('');
        $this->assertEquals($password, 'test');
    }

    /**
     * @covers \CarnegieLearning\LdapOrmBundle\Entity\Ldap\Person::getUserPassword
     * @expectedException \CarnegieLearning\LdapOrmBundle\Exception\UnknownUserPasswordTypeException
     */
    public function testGetUserPasswordThrowsException()
    {
        $person = new Person();
        $person->setUserPassword(['test']);
        $person->getUserPassword('sha1');
    }

    /**
     * @covers \CarnegieLearning\LdapOrmBundle\Entity\Ldap\Person::getUserPassword
     */
    public function testGetUserPassword()
    {
        $person = new Person();
        $person->setUserPassword(['{sha1}test']);
        $password = $person->getUserPassword('sha1');
        $this->assertEquals($password,'test');
    }
}
