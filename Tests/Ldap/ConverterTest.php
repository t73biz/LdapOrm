<?php
namespace CarnegieLearning\LdapOrmBundle\Tests\Ldap;

use CarnegieLearning\LdapOrmBundle\Entity\DateTimeDecorator;
use CarnegieLearning\LdapOrmBundle\Ldap\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testAdDateConversion() {
        $adTimestamp = '130898490540000000.0Z';
        $decorator = Converter::fromAdDateTime($adTimestamp);
        $convertedAdTimestamp = Converter::toAdDateTime($decorator);
        $this->assertEquals($adTimestamp, $convertedAdTimestamp);
    }

    /**
     * @expectedException \Exception
     */
    public function testToLdapDateTime()
    {
        $date = 1.2;
        Converter::toLdapDateTime($date);
    }

    public function testToLdapDateTime_AcceptsInteger()
    {
        $date = 1234556;
        $returned = Converter::toLdapDateTime($date);

        $this->assertInstanceOf('CarnegieLearning\LdapOrmBundle\Entity\DateTimeDecorator', $returned);
    }

    public function testToLdapDateTime_AcceptsString()
    {
        $date = '8:30pm March 12, 2015';
        $returned = Converter::toLdapDateTime($date);

        $this->assertInstanceOf('CarnegieLearning\LdapOrmBundle\Entity\DateTimeDecorator', $returned);
    }

    public function testToLdapDateTime_AcceptsDateTimeObject()
    {
        $date = new \DateTime('8:30pm March 12, 2015');
        $returned = Converter::toLdapDateTime($date);

        $this->assertInstanceOf('DateTime', $returned);
    }

    public function testToLdapDateTime_AcceptsDateTimeDecoratorObject()
    {
        $date = new DateTimeDecorator('8:30pm March 12, 2015');
        $returned = Converter::toLdapDateTime($date);

        $this->assertInstanceOf('CarnegieLearning\LdapOrmBundle\Entity\DateTimeDecorator', $returned);
    }

    public function testToLdapDateTime_UTC()
    {
        $date = 1234556;
        $returned = Converter::toLdapDateTime($date, true);

        $this->assertEquals('19700115065556+0000', (string)$returned);
    }
}
