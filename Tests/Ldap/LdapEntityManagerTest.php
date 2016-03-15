<?php
/**
 * Created by PhpStorm.
 * User: jgabler
 * Date: 9/17/15
 * Time: 11:09 AM
 */

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
