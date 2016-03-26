<?php
namespace CarnegieLearning\LdapOrmBundle\Tests\Ldap;

use CarnegieLearning\LdapOrmBundle\Ldap\Converter;

class LdapEntityManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testAdDateConversion() {
        $adTimestamp = '130898490540000000.0Z';
        $decorator = Converter::fromAdDateTime($adTimestamp);
        $convertedAdTimestamp = Converter::toAdDateTime($decorator);
        $this->assertEquals($adTimestamp, $convertedAdTimestamp);
    }
}