<?php

namespace CarnegieLearning\LdapOrmBundle\Tests\Ldap\Filter;

use CarnegieLearning\LdapOrmBundle\Ldap\Filter\LdapFilter;
use Dreamscapes\Ldap\Core\Ldap;

/**
 * Class LdapFilterTest
 * @package CarnegieLearning\LdapOrmBundle\Tests\Ldap\Filter
 */
class LdapFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \CarnegieLearning\LdapOrmBundle\Ldap\Filter\LdapFilter::__construct
     * @expectedException \CarnegieLearning\LdapOrmBundle\Exception\Filter\InvalidLdapFilterException
     */
    public function testConstruct()
    {
        $filter = new LdapFilter();
        $this->assertEquals(['objectClass'=>'*'],$filter->getFilterArray());
        $filter = new LdapFilter('test');
        $filter = new LdapFilter(['objectClass'=>'Person']);
        $this->assertEquals(['objectClass'=>'Person'],$filter->getFilterArray());
    }

    /**
     * @covers \CarnegieLearning\LdapOrmBundle\Ldap\Filter\LdapFilter::escapeLdapValue
     */
    public function testEscapeLdapValue()
    {
        $filter = new LdapFilter();
        $this->assertEquals('\\3d',$filter->escapeLdapValue('='));
        $this->assertEquals('\\2c',$filter->escapeLdapValue(','));
        $this->assertEquals('\\28',$filter->escapeLdapValue('('));
        $this->assertEquals('\\29',$filter->escapeLdapValue(')'));
    }

    /**
     * @covers \CarnegieLearning\LdapOrmBundle\Ldap\Filter\LdapFilter::format
     */
    public function testFormat()
    {
        // Simple Test
        $filter = new LdapFilter(array('color' =>  'green*'));
        $filterString = $filter->format();
        $this->assertEquals('(color=green*)', $filterString);

        // Complex Test
        $attributeName = 'color';
        $attributeValue1= 'green';
        $attributeValue2= 'red';
        $attributeValue3= 'blue';
        $array = array(
            $attributeName => array(
                $attributeValue1,
                '*'.$attributeValue2.'*',
                '* '.$attributeValue3
            )
        );

        $filter = new LdapFilter($array);
        $filterString = $filter->format();
        $this->assertEquals('(|(color=green)(color=*red*)(color=* blue))', $filterString);

        // Super Complex Test
        $array = array(
            '&' => array(
                'key3' => 'val3',
                'key4' => 'val4',
                '|' => array(
                    'key1' => 'val1',
                    'key2' => array(
                        'val2a',
                        'val2b'
                    ),
                    array(
                        '&' => array(
                            'key5' => 'val5',
                            'key6' => 'val6',
                        )
                    ),
                    array(
                        '&' => array(
                            'key7' => 'val7',
                            'key8' => 'val8',
                        )
                    ),
                    array(
                        '&' => array(
                            'key9' => 'val9',
                            'key10' => 'val10',
                        )
                    )
                )
            )
        );

        $filter = new LdapFilter($array);
        $filterString = $filter->format();
        $expected = '(&(key3=val3)(key4=val4)(|(key1=val1)(|(key2=val2a)(key2=val2b))(&(key5=val5)(key6=val6))(&(key7=val7)(key8=val8))(&(key9=val9)(key10=val10))))';
        $this->assertEquals($expected, $filterString);

    }
}
